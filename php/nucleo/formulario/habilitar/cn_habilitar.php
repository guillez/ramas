<?php
   /*
	*    Controlador de negocio para una Habilitacion de Formularios
	*    y grupos de encuestados habilitados para responder.
	*/
class cn_habilitar extends cn_entidad
{
	//------------------------------------------------------------------------------------
	//---- Setters y getters de las tablas -----------------------------------------------
	//------------------------------------------------------------------------------------
	
	//---- habilitacion ---------------------------------------------------------------
	
	function get_datos_habilitacion()
	{
		return $this->tabla('habilitacion')->get();
	}
	
	function set_datos_habilitacion($datos)
	{
		$this->validar_datos_habilitacion($datos);
		$this->tabla('habilitacion')->set($datos);
        
	}

	function set_datos_configuracion_habilitacion($datos)
    {
        $this->tabla('habilitacion')->set($datos);
    }
	
	//---- formulario_habilitado ------------------------------------------------------
	
	function get_datos_form_habilitado()
	{
		$datos = $this->tabla('formulario_habilitado')->get_filas(null, false, true);
        
		foreach ($datos as $key => $dato) {
			$filtro = "sge_formulario_atributo.nombre = '".$dato['nombre']."'";
			$datos_formulario = toba::consulta_php('consultas_formularios')->get_formularios($filtro);
			$this->tabla('formulario_habilitado')->set_cursor($dato['x_dbr_clave']);
			$grupo = $this->tabla('grupo_habilitado')->get_filas();
			
            if (!empty($grupo)) {
				$datos[$key]['grupo']        = isset($grupo) ? $grupo[0]['grupo'] : '';
				$datos[$key]['grupo_nombre'] = isset($grupo) ? $grupo[0]['grupo_nombre'] : '';
				$datos[$key]['formulario']   = isset($datos_formulario[0]) ? $datos_formulario[0]['formulario'] : '';
			}
		}
        
		return $datos;
	}
	
    function toggle_estado_form_habilitado()
	{
        $fila = $this->tabla('formulario_habilitado')->get();
        $this->tabla('formulario_habilitado')->set(array('estado' => $fila['estado'] == 'A' ? 'B' : 'A'));
    }

    function toggle_archivada()
    {
        $fila = $this->tabla('habilitacion')->get();
        $this->tabla('habilitacion')->set(array('archivada' => $fila['archivada'] == 'S' ? 'N' : 'S'));
    }

    function toggle_destacada()
    {
        $fila = $this->tabla('habilitacion')->get();

        // Si la habilitacioón esta "archivada" no permito que sea "destacada"
        if ($fila['archivada'] == 'N') {
            $this->tabla('habilitacion')->set(array('destacada' => $fila['destacada'] == 'S' ? 'N' : 'S'));
        }
    }

    function sacar_destacada()
    {
        $this->tabla('habilitacion')->set(array('destacada' => 'N'));
    }

    function get_archivada()
    {
	    $fila = $this->tabla('habilitacion')->get();
	    return $fila['archivada'];
    }

    function get_destacada()
    {
        $fila = $this->tabla('habilitacion')->get();
        return $fila['destacada'];
    }
    
    function set_estado_baja_form_habilitados()
    {
        $datos = $this->tabla('formulario_habilitado')->get_filas(null, false, true);
		foreach ($datos as $key => $dato) {
            $this->tabla('formulario_habilitado')->set_cursor($dato['x_dbr_clave']);
            $this->tabla('formulario_habilitado')->set(array('estado' => 'B'));
        }
        $this->resetear_cursor_form_habilitado();
    }
    
    function set_estado_activo_form_habilitados()
    {
        $datos = $this->tabla('formulario_habilitado')->get_filas(null, false, true);
		foreach ($datos as $key => $dato) {
            $this->tabla('formulario_habilitado')->set_cursor($dato['x_dbr_clave']);
            $this->tabla('formulario_habilitado')->set(array('estado' => 'A'));
        }
        $this->resetear_cursor_form_habilitado();
    }
    
	function set_datos_form_habilitado($datos)
	{
        return $this->tabla('formulario_habilitado')->nueva_fila($datos);
	}
	
	function eliminar_formulario_habilitado($fila)
	{
		if ($this->tabla('formulario_habilitado')->existe_fila($fila)) {
			$this->tabla('formulario_habilitado')->eliminar_fila($fila);
		}
	}
	
    function eliminar_formularios_habilitados()
	{
		$this->tabla('formulario_habilitado')->eliminar_filas();
	}
    
    function eliminar_grupos_habilitados()
	{
		$this->tabla('grupo_habilitado')->eliminar_filas();
	}
    
    function eliminar_formularios_habilitados_detalles()
	{
		$this->tabla('formulario_habilitado_detalle')->eliminar_filas();
	}
    
	function set_cursor_form_habilitado($cursor)
	{
        $this->tabla('formulario_habilitado')->set_cursor($cursor);
	}
	
	function resetear_cursor_form_habilitado()
	{
		if ($this->hay_cursor_form_habilitado()) {
			$this->tabla('formulario_habilitado')->resetear_cursor();
		}
	}
	
	function hay_cursor_form_habilitado()
	{
		return $this->tabla('formulario_habilitado')->hay_cursor();
	}
    
    function eliminar_grupo_publico($anonimo_predefinido)
    {
        $form_habilitado = $this->get_datos_form_habilitado();
        
		foreach ($form_habilitado as $formulario) {
            $this->tabla('formulario_habilitado')->set_cursor($formulario['x_dbr_clave']);
            
            if (isset($formulario['grupo']) && isset($anonimo_predefinido['grupo']) && $formulario['grupo'] == $anonimo_predefinido['grupo']) {
                $this->eliminar_grupos_habilitados();
                $this->eliminar_formularios_habilitados_detalles();
                $this->tabla('formulario_habilitado')->eliminar_fila($formulario['x_dbr_clave']);
                break;
            }
        }
    }
	
	//---- formulario_habilitado_detalle ----------------------------------------------
	
	function get_datos_form_habilitado_detalle()
	{
		return $this->tabla('formulario_habilitado_detalle')->get_filas();
	}
	
	function set_datos_form_habilitado_detalle($datos)
	{
		$this->tabla('formulario_habilitado_detalle')->nueva_fila($datos);
	}
	
	//---- grupo_habilitado -----------------------------------------------------------
	
	function get_datos_grupo_habilitado()
	{
		return $this->tabla('grupo_habilitado')->get_filas(null, false, false);
	}
	
	function set_datos_grupo_habilitado($datos)
	{
		$this->tabla('grupo_habilitado')->nueva_fila($datos);
	}
	
	function sincronizar()
	{
		$this->dep('datos')->sincronizar();
	}
	
	function es_habilitacion_nueva()
	{
		$habilitacion = $this->tabla('habilitacion')->get_columna('habilitacion');
		return empty($habilitacion);
	}
    
    //---- log_formulario_definicion_habilitacion ----------------------------------------
    
    function get_datos_log_formulario()
    {
        return $this->tabla('log_formulario_definicion_habilitacion')->get_filas(null, false, false);
    }
    
    function set_datos_log_formulario($datos)
    {
        foreach ($datos as $form) {
            $this->tabla('log_formulario_definicion_habilitacion')->nueva_fila($form);
        }
    }
	
	//------------------------------------------------------------------------------------
	//---- Validaciones ------------------------------------------------------------------
	//------------------------------------------------------------------------------------
	
	function validar_datos_habilitacion($habilitacion)
	{
		if (kolla_fecha::es_mayor($habilitacion['fecha_desde'], $habilitacion['fecha_hasta'])) {
			$this->set_error('control_fechas', array('Desde', 'mayor', 'Hasta'));
		}
		
		if ($this->fecha_modificada($habilitacion['fecha_hasta'], 'fecha_hasta')) {
			if (kolla_fecha::es_menor_a_fecha_actual($habilitacion['fecha_hasta'])) {
				$this->set_error('fecha_hasta_erronea');
			}
		}
			
		if ($this->fecha_modificada($habilitacion['fecha_desde'], 'fecha_desde')) {
			if (kolla_fecha::es_menor_a_fecha_actual($habilitacion['fecha_desde'])) {
				$this->set_error('fecha_desde_erronea');
			}
		}
		
		if ($this->hay_errores()) {
			throw new toba_error($this->get_mensaje_error());
		}
	}
	
	function fecha_modificada($fecha, $campo)
	{
		return !kolla_fecha::es_igual($this->tabla('habilitacion')->get_columna($campo), $fecha);
	}
	
	function validar_formularios_habilitados($formularios_habilitados)
	{
		$this->validar_existencia_datos($formularios_habilitados, 'datos_faltantes_ml', array('un formulario y concepto'));
		$this->validar_filas_duplicadas_formulario_habilitado($formularios_habilitados);
		
		if ($this->hay_errores()) {
			throw new toba_error($this->get_mensaje_error());
		}
	}
	
	function validar_existencia_datos($datos, $descripcion_error, $parametros)
	{
		if (empty($datos)) {
			$this->set_error($descripcion_error, $parametros);
		}
	}
	
	function validar_filas_duplicadas_formulario_habilitado($formularios_habilitados)
	{
		$ids_filas = array();
		$formularios_habilitados = $this->aplanar_datos_sin_bajas($formularios_habilitados);
        
		foreach ($formularios_habilitados as $clave => $valor) {
			$id_fila = $valor['formulario'].'_'.$valor['concepto'].'_'.$valor['grupo'];
			if (in_array($id_fila, $ids_filas)) {
				$this->set_error('filas_duplicadas');
				break;
			}
			array_push($ids_filas, $id_fila);
		}
	}
	
	function validar_formulario_duplicado($formulario)
	{
		$id_nuevo = $formulario['concepto'].'_'.$formulario['grupo'];
		$formularios_habilitados = $this->get_datos_form_habilitado();
		
		foreach ($formularios_habilitados as $clave => $valor) {
			$id_fila = $valor['concepto'].'_'.$valor['grupo'];
			
			if ($id_fila == $id_nuevo) {
				$this->set_error('filas_duplicadas');
				break;
			}
		}
		
		if ($this->hay_errores()) {
			throw new toba_error($this->get_mensaje_error());
		}
	}
    
    function aplanar_datos_sin_bajas($datos)
	{
		foreach ($datos as $clave => $valor) {
			if ($datos[$clave]['apex_ei_analisis_fila'] == 'B') {
				unset($datos[$clave]);
			}
		}
		
		return $datos;
	}
    
    //------------------------------------------------------------------------------------
	//---- Auxiliares --------------------------------------------------------------------
	//------------------------------------------------------------------------------------
    
    function posee_encuestas_implementadas()
    {
        $implementadas = false;
        
        if (!$this->tabla('formulario_habilitado')->esta_cargada()) {
			return false;
		}
        
        $this->tabla('formulario_habilitado')->resetear_cursor();
        $detalle = $this->get_datos_form_habilitado_detalle();
        
        foreach ($detalle as $encuesta) {
            $encuesta_implementada = toba::consulta_php('consultas_encuestas')->get_implementada_encuesta($encuesta['encuesta']);
            
            if ($encuesta_implementada == 'S') {
                $implementadas = true;
                break;
            }
        }
        
        $this->resetear_cursor_form_habilitado();
        return $implementadas;
    }
	
}
?>