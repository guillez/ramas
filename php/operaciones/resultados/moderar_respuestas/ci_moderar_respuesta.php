<?php

require 'modelo/comunes/datos.php';

class ci_moderar_respuesta extends ci_navegacion_por_ug_reportes
{    
    
    private $definicion_tabla_resultados = "resultados (
                    habilitacion integer,
                    formulario_habilitado integer,
                    formulario_nombre text,
                    respondido_formulario integer, 
                    ingreso integer, 
                    fecha_inicio date, 
                    terminado_formulario character(1), 
                    fecha_terminado date, 
                    respondido_encuesta integer, 
                    respondido_detalle integer, 
                    moderada character(1), 
                    respuesta_codigo integer, 
                    respuesta_valor character varying, 
                    encuesta_definicion integer,
                    encuesta integer, 
                    orden_encuesta integer, 
                    orden_bloque smallint, 
                    bloque integer, 
                    bloque_nombre character varying(255), 
                    orden_pregunta smallint, 
                    pregunta integer, 
                    pregunta_nombre character varying(4096), 
                    componente character varying(35),
                    tabla_asociada character varying(100), 
                    concepto integer, 
                    encuesta_nombre character varying, 
                    elemento integer, 
                    elemento_nombre text, 
                    respondido_encuestado integer,
                    encuestado integer,
                    usuario character varying(60),
                    respondido_por character varying(60),
                    ignorado char,
                    concepto_nombre text,
                    concepto_externo character varying(100),
                    elemento_externo character varying(100),
                    pregunta_tabla_codigo character varying(50),
                    pregunta_tabla_descripcion character varying(50),
                    numero integer,
                    respondido_por_encuestado integer,
                    codigo_columna character varying)";
    
	protected $s__datos_filtro;
	protected $s__filtro;
	protected $s__seleccion;
	protected $s__valor_original; //lo guardo porque no se puede cambiar!
    
	function evt__agregar()
	{
		$this->set_pantalla('pant_edicion');
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(toba_ei_cuadro $cuadro) 
	{
		if (isset($this->s__datos_filtro) && isset($this->s__datos_filtro['formulario_habilitado'])) {
            $where = $this->get_filtro_where()." AND respuesta_valor != '' ";
            //AGREGO FILTRO PARA TIPOS DE PREGUNTA PORQUE SOLO DEBERÍAN PODER MODERARSE RESPUESTAS LIBRES
            $where .= " AND componente ILIKE 'text%' ";
			$datos = toba::consulta_php('consultas_formularios')->get_respuestas_completas_formulario_habilitado($this->s__datos_filtro['formulario_habilitado'], $this->definicion_tabla_resultados, $where);
			$cuadro->set_datos($datos);
		}
	}

	function evt__cuadro__seleccion($seleccion) 
    {
		$this->s__seleccion = $seleccion;
		$this->set_pantalla('pant_edicion');
		$t = $this->get_relacion();
		$t->cargar($this->s__seleccion);
	}

	//-----------------------------------------------------------------------------------
	//---- filtro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
	
	function conf__filtro(toba_ei_formulario $filtro)
	{
		if (isset($this->s__filtro)) {
			return $this->s__filtro;
		}
	}
	
	function evt__filtro__filtrar($datos)
	{
		if (isset($datos)) {
			$this->s__filtro = $datos;
			
			//validar los fechas del filtro
			if ($this->fechas_validas($datos)) {
				$this->s__datos_filtro = $datos;
			} else {
				unset($this->s__datos_filtro);
				toba::notificacion()->agregar('Verifique que las fechas estén dentro del rango de la habilitación.', 'info');
			}
		}
	}
	
	function evt__filtro__cancelar()
	{
		unset($this->s__filtro);
		unset($this->s__datos_filtro);
	}
	
	function get_filtro_where()
	{
		$where = '';
		if (isset($this->s__filtro)) {
			if ($this->s__filtro['habilitacion'] != '') 
				$where .= " AND habilitacion = ".$this->s__filtro['habilitacion'];
			
			if ($this->s__filtro['formulario_habilitado'] != '')
				$where .= " AND formulario_habilitado = ".$this->s__filtro['formulario_habilitado'];
			
			if ($this->s__filtro['fecha_desde'] != '')
				$where .= " AND fecha_inicio >= '".$this->s__filtro['fecha_desde']."'";
			
			if ($this->s__filtro['fecha_hasta'] != '')
				$where .= " AND fecha_terminado <= '".$this->s__filtro['fecha_hasta']."'";
			
			if ($this->s__filtro['solo_moderadas'])
				$where .= " AND moderada = 'S' ";
			
			if ($this->s__filtro['respuesta_like'] != '')
				$where .= " AND respuesta_valor ILIKE '%".$this->s__filtro['respuesta_like']."%'";
		}
		return $where;
	}
	
	function fechas_validas($datos)
	{
		$filtro_fd = null;
		$filtro_fh = null;
		if ($datos['fecha_desde'] != '')
			$filtro_fd = mktime(0,0,0,substr($datos['fecha_desde'],5,2),substr($datos['fecha_desde'],8,2),substr($datos['fecha_desde'],0,4));			
		if ($datos['fecha_desde'] != '')
			$filtro_fh = mktime(0,0,0,substr($datos['fecha_hasta'],5,2),substr($datos['fecha_hasta'],8,2),substr($datos['fecha_hasta'],0,4));
		$validas = true;
		$datos_habilitacion = toba::consulta_php('consultas_habilitaciones')->get_datos_habilitacion($datos['habilitacion']);
		
		if (isset($filtro_fd)) {
			$hab_fd = mktime(0,0,0,substr($datos_habilitacion[0]['fecha_desde'],5,2),substr($datos_habilitacion[0]['fecha_desde'],8,2),substr($datos_habilitacion[0]['fecha_desde'],0,4));
			$validas = ($filtro_fd >= $hab_fd);
		}
		
		if (isset($filtro_fh)) {
			$hab_fh = mktime(0,0,0,substr($datos_habilitacion[0]['fecha_hasta'],5,2),substr($datos_habilitacion[0]['fecha_hasta'],8,2),substr($datos_habilitacion[0]['fecha_hasta'],0,4));
			$validas = $validas && ($filtro_fh <= $hab_fh);
		}
		
		return $validas; 
	}
	
	//-----------------------------------------------------------------------------------
	//---- formulario--------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__formulario(toba_ei_formulario $e)
	{
		if (isset($this->s__seleccion)) {
			$datos = $this->get_tabla_respuestas()->get();
            $this->s__valor_original = $datos['respuesta_valor'];
			
			$moderadas = $this->get_tabla_moderadas();
			$cant = $moderadas->get_cantidad_filas();
			toba::logger()->debug("Existen $cant registros de moderación. Se obtiene el que este en alta si existe");
			
			if ($cant >= 1) {
				
				//hay varios registros. Tengo que buscar si hay uno en alta
				toba::logger()->debug("Existen varios registros de moderación. Se obtiene el que este en alta si existe");
				$fila_editable = -1;
				
				foreach ($moderadas->get_filas() as $id_fila => $fila) {
					if (is_null($fila['fecha_baja'])) {
						$fila_editable = $id_fila;
						break;
					}
				}
				
				if ($fila_editable != -1) {
					
					//encontre uno en alta
					$e->agregar_notificacion("Las moderaciones no pueden modificarse. Solo se pueden dar de baja, y crear una nueva. Si presiona 'Guardar' se realizará esta operación.", 'info');
					//$e->ef('motivo')->set_solo_lectura(true);
					//$e->ef('texto_nuevo')->set_solo_lectura(true);
					$moderadas->set_cursor($fila_editable);
					$data = $moderadas->get();
					
					$data['fecha'] = explode(' ', $data['fecha']);
					$data['fecha'][1] = substr($data['fecha'][1], 0 ,5 ); //HH:mm
					return $data;
				}
			}
			
			toba::logger()->debug("Registro de moderación nuevo");
			$e->set_datos_defecto($this->get_valores_defecto());
			return null;		
        }
	}
	

	/**
	 * Al confirmar que se modera el contenido de una respuesta cambiando la respuesta
	 * original por otro texto se deberán registrar los cambios de la siguiente manera:
	 * Se debe guardar la respuesta real dada por el encuestado en la tabla sge_respuesta_moderada, 
	 * registrando Texto Original, Texto Nuevo, Motivo, Fecha Hora de Alta, Usuario Alta
	 * Se debe actualizar en la tabla sge_respondido_detalle el campo valor con el Texto Nuevo.
	 */
	function evt__formulario__alta($datos_)
	{
		$datos = array_merge($datos_, $this->get_valores_defecto()); //usuario, fecha, texto_original
		$datos['fecha'] = implode(' ', $this->get_fecha()); //actualizo la fecha de YA!
        $respuestas = $this->get_tabla_respuestas();
		$respuestas->set(array('moderada'=>'S', 'respuesta_valor'=> $datos['texto_nuevo']));      
        
		$moderadas = $this->get_tabla_moderadas();
		$moderadas->nueva_fila($datos);
	    
		try {
			$this->get_relacion()->sincronizar();
		} catch (toba_error $e) {
			toba::notificacion()->agregar('Error insertando'.$e->getMessage());
			toba::logger()->error($e->getMessage());
		}
		
		$this->resetear();
	}

	/**
	 * Si se desea cambiar el texto en una respuesta ya moderada, al confirmar se deberá 
	 * registrar el cambio de la siguiente manera:
	 * 
	 * Se debe registrar en la tabla sge_respuesta_moderada para el registro correspondiente a la
	 * respuesta moderada, los campos Motivo de Baja con el texto "Actualización", Fecha Hora de Baja, Usuario Baja.
	 * 
	 * Se debe insertar un nuevo registro correspondiente a la misma respuesta moderada 
	 * manteniendo el Texto Original de la respuesta moderada, y con los nuevos
	 * valores de los campos Texto Nuevo, Motivo, Fecha Hora de Alta, Usuario Alta.
	 * @param type $datos
	 */
	function evt__formulario__modificacion($datos_)
	{
		$datos = array_merge($datos_, $this->get_valores_defecto()); //usuario, fecha, texto_original
		$datos['fecha'] = implode(' ', $this->get_fecha()); //fecha de baja, y de nueva alta.
		$respuestas = $this->get_tabla_respuestas();
		$respuestas->set(array('moderada'=>'S', 'respuesta_valor'=> $datos['texto_nuevo'])); //sigue moderada, con el nuevo texto
		
		$moderadas = $this->get_tabla_moderadas();
		$reg = $moderadas->get();
		$texto_original = $reg['texto_original']; 
		//tengo que pisar el de los valores por defecto, que toma el de la respuesta
		//y hay que tomar el de la respuesta moderada
		
		$datos_baja['usuario_baja'] = $datos['usuario'];
		$datos_baja['fecha_baja'] = $datos['fecha'];
		$datos_baja['motivo_baja'] = "Actualizacion";
		$moderadas->set($datos_baja); // doy de baja el actual

		$datos['texto_original'] = $texto_original; //aca piso el texto original de la rta con el de la moderada
		$moderadas->nueva_fila($datos); //creo un nuevo registro con los nuevos datos
		
		try {
			$this->get_relacion()->sincronizar();
		} catch (toba_error $e) {
			toba::notificacion()->agregar('Error eliminado');
			toba::logger()->error($e->getMessage());
		}
		
		$this->resetear();
	}

	function evt__formulario__baja()
	{
		$moderacion = $this->get_tabla_moderadas();
		$respuestas = $this->get_tabla_respuestas();
		$reg = $moderacion->get();
		$texto_original = $reg['texto_original'];

		$vals = $this->get_valores_defecto();
		$vals['fecha'] = implode(' ', $vals['fecha']); //fecha de baja, y de nueva alta.

		$datos['usuario_baja'] = $vals['usuario'];
		$datos['fecha_baja'] = $vals['fecha'];
		$datos['motivo_baja'] = "Eliminación";
		$moderacion->set($datos); // le pongo los datos para darlo de baja

        $respuestas->set(array('moderada' => 'N', 'respuesta_valor' => $texto_original));
		
		try {
			$this->get_relacion()->sincronizar();
		} catch (toba_error $e) {
			toba::notificacion()->agregar('Error eliminado');
			toba::logger()->error($e->getMessage());
		}
		
		$this->resetear();
	}
		
	function evt__formulario__cancelar()
	{
		$this->resetear();
	}

	function resetear()
	{
		$this->get_relacion()->resetear();
		$this->set_pantalla('pant_inicial');
		if (isset($this->s__seleccion)) {
			unset($this->s__seleccion);
		}
	}

	private function get_relacion()
	{
		return $this->dependencia('relacion');
	}
	
	private function get_tabla_respuestas()
	{
		return $this->dependencia('relacion')->tabla('respuesta_valores');
	}
	
	private function get_tabla_moderadas()
	{
		return $this->dependencia('relacion')->tabla('respuestas_moderadas');
	}

	private function get_valores_defecto()
	{
		return array('fecha' => $this->get_fecha(), 'usuario' => $this->get_usuario(), 'texto_original' => $this->s__valor_original);
	}

	private function get_usuario()
	{
		return toba::usuario()->get_id();
	}
	
	private function get_fecha()
	{
		return array(date("Y-m-d"), date("H:i"));
	}
	
	function ajax__es_anonima($habilitacion, toba_ajax_respuesta $respuesta)
	{
        if ($habilitacion != 'nopar') {
            $hab_anonima = toba::consulta_php('consultas_habilitaciones')->es_habilitacion_anonima($habilitacion);
            $estructura = array('hab_anonima' => $hab_anonima);
            $respuesta->set($estructura);
        } else {
            $estructura = array('hab_anonima' => 'NO_EXISTE');
            $respuesta->set($estructura);
        }
		
	}
    
}
?>