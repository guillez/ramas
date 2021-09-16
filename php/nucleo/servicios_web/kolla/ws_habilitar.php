<?php
include('ws_habilitar_mjes.php'); 
include('ws_habilitar_parametros.php'); 

/**
 * Es bastante ineficiente esta clase. Ya que se están estabilizando los requerimientos
 * se puede optimizar bastante si se traen los datos a memoria de la bd, y se batchean
 * la mayor cantidad posible de consultas. 
 * Esto se podria lograr enmascarando esto en consultas_ws_habilitar.
 * 
 * Como paso anterior, se puede micro-optimizar, por ej un bulk insert del detalle
 * de los formularios.
 */
class ws_habilitar
{
	private $parametros;
	private $cargador; //carga y valida los parametros
	private $sistema;
	private static $log; //estatico para facilitar el acceso.
	private static $es_debug;
	private $puede_modificar_formularios = false; //defecto
	
	//auxiliares
	private $elementos_ids = array(); //mapeo ids interno y externo

	function __construct($arreglo_params, $sistema)
	{
		self::$log = new ws_habilitar_mjes(); //mjes de logueo
		$this->sistema = (int)$sistema;
		$this->cargador = new ws_habilitar_parametros();
		$this->parametros = $this->cargador->cargar_parametros($arreglo_params);
		self::$es_debug = $this->cargador->es_debug;
	}

	function ejecutar()
	{
		try {
			toba::db()->abrir_transaccion();
			$resultado = $this->ejecutar_();
			if (self::$es_debug) {
				toba::db()->abortar_transaccion();
			} else {
				toba::db()->cerrar_transaccion();
			}
			return $resultado;
		} catch (toba_error_servicio_web $e) {
			toba::db()->abortar_transaccion();
			toba::logger()->debug('Error en la operación: ' . $e->get_mensaje());
			throw new toba_error_servicio_web($e->get_mensaje(), $e->get_codigo());
		} catch (Exception $e) {
			toba::db()->abortar_transaccion();
			toba::logger()->debug('Error en la operación: ' . $e->getMessage());
			$this->throw_error('error_indefinido', $e->getMessage());
		}
	}
	
	private function ejecutar_()
	{
		$id_habilitacion = $this->upsert_habilitacion();
		$password = $this->obtener_password($id_habilitacion);
		$this->upsert_conceptos_elementos($id_habilitacion);
		
		return $this->generar_resultado($id_habilitacion, $password);
	}

	private function generar_resultado($id_habilitacion, $password)
	{
		$resultado = array(
			'id_hab' => $id_habilitacion,
			'url' => url_encuestas::param_op_toba,
			'password' => $password,
		);
		if(self::$es_debug){
			$resultado['log'] = self::$log->info;
		}else{
			toba::logger()->debug(var_export(self::$log->info, true));
		}
		
		return $resultado;
	}
	
	private function upsert_habilitacion()
	{
		if ($this->es_modificacion_habilitacion()) {
			$id_hab = $this->parametros['habilitacion'];
			$this->validar_fechas($id_hab);
			if($this->habilitacion_tiene_respuestas($id_hab)){
				$this->log_info('operacion', operacion_log::codigo_modificar_limitado);
				$this->modificar_habilitacion_iniciada($id_hab);
			}else{
				$this->puede_modificar_formularios = true;
				$this->log_info('operacion', operacion_log::codigo_modificar_libre);
				$this->modificar_habilitacion_no_iniciada($id_hab);
			}
		} else {
			$id_hab = $this->crear_habilitacion();
		}
		return $id_hab;
	}
	
	private function validar_fechas($id_hab)
	{
		$hab_actual = toba::consulta_php('consultas_ws_habilitar')->obtener_habilitacion($id_hab, $this->sistema);
		if(!isset($hab_actual['fecha_desde'])){
			//la hab no existe
			$this->throw_error('error_modif_habilitacion',$id_hab);
		}
		$fecha_desde_act = $hab_actual['fecha_desde'];
		//$fecha_hasta_act = $hab_actual['fecha_hasta'];
		$hoy = date("Y-m-d");
		$fecha_desde_nueva = $this->parametros['fecha_desde'];
		//$fecha_hasta_nueva = $this->parametros['fecha_hasta'];
		//ya viene validado que desde <= hasta
		
		if($fecha_desde_act <= $hoy){ //NO puede modificar fecha desde
			if($fecha_desde_act != $fecha_desde_nueva){
				$this->throw_error('error_modificacion_fechas', 
						$fecha_desde_nueva . " - " . $fecha_desde_act);
			}
		}
	}
	
	/**
	 * Guarda una habilitacion. Se asume que todos los parametros han sido controlados.
	 * @param array $params arreglo asociativo con las columas de la tabla sge_habilitacion
	 */
	private function crear_habilitacion()
	{
		$params = $this->obtener_arreglo_habilitacion();
		$params['externa'] = "'S'";
		$id = toba::consulta_php('consultas_ws_habilitar')->insertar_habilitacion($params);
		$this->log_info('operacion', operacion_log::codigo_creacion, $id, $id);
		return $id;
	}

	private function modificar_habilitacion_no_iniciada($id_hab)
	{
		$params = $this->obtener_arreglo_habilitacion();
		$params['habilitacion'] = $id_hab;
		$cant = toba::consulta_php('consultas_ws_habilitar')->modificar_habilitacion_no_iniciada($params);
		if ($cant != 1) {//tiene que modificar una sola fila.
			$this->throw_error('error_modif_habilitacion_no_ini');
		}
	}

	/**
	 * @param type $params
	 */
	private function modificar_habilitacion_iniciada($id_hab)
	{
		$params = $this->obtener_arreglo_habilitacion();
		$params['habilitacion'] = $id_hab;
		$cant = toba::consulta_php('consultas_ws_habilitar')->modificar_habilitacion_iniciada($params);
		if ($cant != 1) {//tiene que modificar una sola fila.
			$this->throw_error('error_modif_habilitacion_ini');
		}
	}
	
	private function upsert_conceptos_elementos($habilitacion)
	{
		if(isset($this->parametros['elementos'])){
			$this->upsert_elementos_base($this->parametros['elementos']);
		}
		if(isset($this->parametros['formularios'])){
			$this->upsert_formularios($habilitacion, $this->parametros['formularios']);
		}
	}

	private function upsert_elementos_base($elementos)
	{
        $ug = $this->parametros['unidad_gestion'];
		foreach ($elementos as $elem) {
			$res = toba::consulta_php('consultas_ws_habilitar')->upsert_elemento_sp($elem['id_ex'], $elem['dsc'], $elem['url'], $this->sistema, $ug);
			$codigo = $this->validar_sp_elem($res);
			$this->log_info('elementos', $codigo, $elem['id_ex'], $elem['id_ex'], $elem['ug']);
			$this->elementos_ids[$elem['id_ex']] = $res[0]['id']; //lo uso para mantener id_externo/interno de los elementos 
		}
	}
	
	private function upsert_formularios($habilitacion, &$formularios)
	{
        $ug = $this->parametros['unidad_gestion'];
		foreach($formularios as &$form) {
			$res = toba::consulta_php('consultas_ws_habilitar')->upsert_concepto_sp($form['id_ex'], $form['dsc'], $this->sistema, $ug);
			$codigo = $this->validar_sp_concepto($res);//codigo no se usa porque no se loguea nada
			$form['concepto'] = $res[0]['id']; 
			$this->upsert_formulario_habilitado($habilitacion, $form);
		}
	}

	private function upsert_formulario_habilitado($habilitacion, &$form)
	{
		$res = toba::consulta_php('consultas_ws_habilitar')->obtener_formulario_habilitado($form['concepto'], $habilitacion);
		if(isset($form['eliminar'])){
			if(isset($res[0]['formulario_habilitado'])){
				toba::consulta_php('consultas_ws_habilitar')->dar_baja_formulario($res[0]['formulario_habilitado'], $habilitacion);
				$this->log_info('formularios', formulario_log::codigo_eliminacion, $form['id_ex'], $form['id_ex']);
				return;
			}else {
				toba::logger()->warning("Formulario para eliminar no existe {$form['id_ex']}");
				return;
			}
		}
		
		if(isset($res[0]['formulario_habilitado'])){
			if(!$this->puede_modificar_formularios) {
                toba::logger()->debug('No se pueden modificar formularios en la habilitacion - Form: "' . $form['id_ex'] . '"');
                return;
            }
			$id_form = $res[0]['formulario_habilitado'];
			$res = toba::consulta_php('consultas_ws_habilitar')->actualizar_formulario_habilitado($id_form, $form['concepto'], $habilitacion, $form['dsc']);
			if($res != 1)
				 $this->throw_error('error_mod_form', $form['id_ext']);
			$this->log_info('formularios', formulario_log::codigo_actualizacion, $form['id_ex'], $form['id_ex']);
			toba::consulta_php('consultas_ws_habilitar')->eliminar_formulario_detalle($id_form); //lo limpio para agregarle lo nuevo.
		}else{
			//NUEVA
			$id_form = toba::consulta_php('consultas_ws_habilitar')->insertar_formulario_habilitado($form['concepto'], $habilitacion, $form['dsc']);
			$this->log_info('formularios', formulario_log::codigo_creacion, $form['id_ex'], $form['id_ex']);	
            //HABILITAR AL GRUPO EXTERNO PARA ESTE FORMULARIO HABILITADO DE HABILITACION EXTERNA
            toba::consulta_php('consultas_ws_habilitar')->insertar_grupo_habilitado($id_form, $this->sistema);
		}
		$this->insertar_formulario_detalle($id_form, $form);
	}
	
	protected function insertar_formulario_detalle($id_form, $form)
	{
		$o = 0;
		if(is_array($form['enc_elemento'])) //los ws convierten mal los arreglos vacios
		foreach($form['enc_elemento'] as $fila){
			$encuesta = $this->cargador->obtener_encuesta($fila[0]);
			$elem = $fila[1];
			if(is_null($elem) || !strcmp('null', strtolower($elem)) || !strlen($elem)){
				$id_elem = null;
				$elem = 'NULL';
			}else{
				if(!isset($this->elementos_ids[$elem])){
					$this->throw_error('error_form', 'No se encontró '.$elem);
				}
				$id_elem = $this->elementos_ids[$elem];
			} 
			toba::consulta_php('consultas_ws_habilitar')->insertar_fila_formulario_detalle($id_form, $encuesta, $id_elem, $o++);
			$this->log_info('filas_form', $form['id_ex'], $encuesta, $elem);
		}
	}

	private function obtener_password($id_hab)
	{
		if ($this->es_modificacion_habilitacion()) {
			$password = toba::consulta_php('consultas_ws_habilitar')->get_password($id_hab, $this->sistema);
		} else{
			$password = url_encuestas::gen_password($id_hab);
			toba::consulta_php('consultas_ws_habilitar')->update_password($password, $id_hab, $this->sistema);
		}
		return $password;
	}
	
	private function habilitacion_tiene_respuestas($id)
	{
		$id = (int) $id;
		return toba::consulta_php('consultas_encuestas')->tiene_respuestas_habilitacion($id);
	}
	
	/////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////
	/////////////////////  carga de datos y validaciones de params  /////////////
	/////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////

	private function es_modificacion_habilitacion()
	{
		return $this->parametros['habilitacion'] > 0;
	}
	
	/**
	 * Se encarga de manejar el arreglo info que se envia como respuesta
	 * al cliente. Usar esta funcion para enviar inforamcion al cliente.
	 * @param type $key la categoria del mensaje
	 * @param type $code el codigo dentro de la categoria
	 * @param type $str  cadena adicional a anexar al mensaje
	 */
	static function log_info($key, $code = 0, $str = '', $id = '')
	{
		if (self::$es_debug)
			self::$log->log_info($key, $code, $str, $id);
	}

	/**
	 *	Se encarga de manejar los errores de acuerdo al arreglo errores, y mantiene
	 *  consistencia de formato para enviarlos al cliente.
	 * @param type $codigo el codigo de error segun el arreglo errores
	 * @param string $params mensaje adicional a enviar en el error
	 * @throws toba_error_servicio_web envia el error en forma de excepcion al cliente
	 */
	static function throw_error($codigo, $params ='')
	{
		self::$log->throw_error($codigo, $params);
	}
	
	private function validar_sp_elem($resultset)
	{
		if (count($resultset) <= 0) {	$this->throw_error('error_item', $param); }
			if ($resultset[0]['codigo'] == 0) {  $codigo = items_log::codigo_creacion;}
			else if ($resultset[0]['codigo'] == 1) { $codigo = items_log::codigo_actualizacion;}
			else { $this->throw_error('error_item', $param);}
			return $codigo;
	}
	
	private function validar_sp_concepto($res)
	{
		if (!(isset($res[0]['id']))) {$this->throw_error('error_alcance');}
		if ($res[0]['codigo'] == 0) {$codigo = formulario_log::codigo_creacion;	}
		else if ($res[0]['codigo'] == 1) {$codigo = formulario_log::codigo_actualizacion;}
		else {$this->throw_error('error_alcance'); }//el sp me devolvio cualquier cosa
		return $codigo;
	}

	private function obtener_arreglo_habilitacion()
	{
		$array = array(
				//'encuesta' => $this->parametros['encuesta'],
				//numerico
				'fecha_desde' => quote($this->parametros['fecha_desde']),
				'fecha_hasta' => quote($this->parametros['fecha_hasta']),
				'paginado' => quote($this->parametros['paginado']),
				'estilo' => $this->parametros['estilo'],
				//numerico
				'anonima' => quote($this->parametros['anonima']),
				//'alcance' => $this->id_alcance,
				'url_imagenes_base' => quote($this->parametros['url_imagenes_base']),
				'sistema' => $this->sistema,
				'unidad_gestion' => quote($this->parametros['unidad_gestion']),
				);
		return $array;
	}
	
}
?>
