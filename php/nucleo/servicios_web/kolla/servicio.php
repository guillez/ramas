<?php

class servicio extends toba_servicio_web
{
	static function get_opciones()
	{
		return array(
				'seguro'	=> true,		//Explicitamente se hace publico el servicio
				'requestXOP'	=> false,

				'useMTOM'		=> false, //NO PONER EN TRUE!! --->segmentation fault (con payload grande) -.-
				'useWSA'        => true,
				'actions'       => array(
						'http://siu.edu.ar/kolla/habilitaciones/habilitar' => 'habilitar',
						'http://siu.edu.ar/kolla/habilitaciones/encuestasDisponibles' => 'encuestasDisponibles',
						'http://siu.edu.ar/kolla/habilitaciones/encuestasRespondidas' => 'encuestasRespondidas'
				)
		);
	}
	
	/**
	 * Para debuguear sin seguridad. Agregar el atributo nombre igual al nombre
	 * del sistema externo que se quiere testear (tabla sge_sistema_externo)
	 * @see toba_servicio_web::get_id_cliente()
	 */
	function get_id_cliente($parametro=null){
		$id_cliente = parent::get_id_cliente();
		if(empty($id_cliente)) throw new toba_error_servicio_web("Error en la identificación");
		//$id_cliente = array('usuario' =>'ue_sistema_externo');//PARA DEBUG SIN SEGURIDAD.
		return $id_cliente;
	}


	function op__encuestasDisponibles(toba_servicio_web_mensaje $mensaje)
	{
		toba::logger()->debug('WS-EncuestasDisponibles');
		$params = $this->ws_obtener_arreglo_de_parametros($mensaje);
		$sql = "SELECT	encuesta, estado, nombre AS desc
				FROM 	sge_encuesta_atributo
				WHERE 	(estado = 'I' OR  estado = 'A')
				AND		unidad_gestion = ".kolla_db::quote($params['unidad_gestion']);
		$res = kolla_db::consultar($sql);
		return $this->ws_obtener_respuesta_de_arreglo($res);
	}

	/**
	 * 
	 * @param toba_servicio_web_mensaje $mensaje.
	 * Un arreglo con este formato:
	 *   nuevas_respuestas => array( array(habilitacion, cui, formulario),
	 *						         array(habilitacion, cui, formulario)...)
	 * 
	 * Notar que es un arreglo posicional, para evitar expandir el tamaño.
	 * (Ver igual el mensaje generado por toba, que creo que igual le agrega
	 * un nombre a la fila).
	 * 	 * @return type
	 */
	function op__encuestasRespondidas(toba_servicio_web_mensaje $mensaje)
	{
		$params = $this->ws_obtener_arreglo_de_parametros($mensaje);
		$sistema = $this->obtener_sistema();
		
		//EL @ es porque no chequeo que esten seteados, se encarga el objeto.
	//	$servicio = @new ws_encuestas_respondidas($params['habilitacion'], $params['cui'], $params['formulario'], $sistema);
		$servicio = @new ws_encuestas_respondidas($params, $sistema);
		$array = $servicio->ejecutar();
		return $this->ws_obtener_respuesta_de_arreglo($array);
	}


	
	function op__habilitar(toba_servicio_web_mensaje $mensaje)
	{
		ini_set('memory_limit', '512000000'); //512MB!
		//ini_set('memory_limit', '-1');
		$params = $this->ws_obtener_arreglo_de_parametros($mensaje);

		$sistema = $this->obtener_sistema();
		$id_cliente = $this->get_id_cliente();

		$servicio = new ws_habilitar($params, $sistema);
		$array = $servicio->ejecutar();
		
		return $this->ws_obtener_respuesta_de_arreglo($array);
	}

	/**
	 * Obtiene el sistema a partir del parametro 'nombre'=nombre_usuario de la
	 * configuracion de los certificados.
	 */
	private function obtener_sistema()
	{
		$id_cliente = $this->get_id_cliente();
		$sql = 'SELECT sistema 
				from sge_sistema_externo 
				WHERE usuario = ' . quote($id_cliente['usuario']);
		toba::logger()->debug($sql);
		$id = kolla_db::consultar($sql);

		if (count($id) == 1) {
			$res = $id[0]['sistema'];
			$log = 'Retornando el id de sistema ' . $res;
			toba::logger()->debug($log);
			return $res;
		} else {
			$log = 'No se encuentra el sistema ';// . $id_cliente;
			toba::logger()->debug($log);
			$this->throw_error('error_autenticacion', 'No se encuentra la identificacion del sistema');
		}
	}

	/**
	 *  Se encarga de manejar los errores de acuerdo al arreglo errores, y mantiene
	 *  consistencia de formato para enviarlos al cliente.
	 * @param type $codigo el codigo de error segun el arreglo errores
	 * @param string $params mensaje adicional a enviar en el error
	 * @throws toba_error_servicio_web envia el error en forma de excepcion al cliente
	 */
	private function throw_error($codigo, $params = '')
	{
		toba::logger()->debug('ERROR:-' .$codigo . ": " . $params);
		//toba_error_servicio_web($mensaje_visible, $codigo_error=null, $mensaje_solo_debug='')
		throw new toba_error_servicio_web($codigo . ": " . $params, $codigo);
	}
	
	
	private function ws_obtener_arreglo_de_parametros(toba_servicio_web_mensaje $mensaje)
	{
		$params = $mensaje->get_array();
		toba::logger()->debug("Arreglo de parametros");
	
		toba::logger()->var_dump($params);
		//$params = $mensaje->get_array_de_payload_estricto();
		return $params;
	}
	
	private function ws_obtener_respuesta_de_arreglo($rta)
	{
		//$mi_xml = toba_servicio_web_mensaje::get_payload_estricto_de_array($rta);
		//$mi_xml = toba_servicio_web_mensaje::
		toba::logger()->var_dump($rta);
		//toba::logger()->var_dump($mi_xml);
		return new toba_servicio_web_mensaje($rta);
	}

}

?>