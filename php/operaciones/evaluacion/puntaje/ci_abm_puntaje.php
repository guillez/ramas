<?php
use ext_bootstrap\componentes\bootstrap_ci;

class ci_abm_puntaje extends bootstrap_ci
{
    //Variables de sesión
	protected $s__encuesta;
	protected $s__puntaje;
    protected $s__datos_ml_preguntas;
    
    //Variables temporales
    protected $datos_puntaje_temp;
    protected $pregunta_seleccionda;
	
    //-----------------------------------------------------------------------------------
    //---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------
    
	function conf()
	{
        $this->s__datos_ml_preguntas = $this->dep('ml_preguntas')->get_datos();
	}
    
    //-----------------------------------------------------------------------------------
    //---- Auxiliares -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
    
	function get_encuesta_seleccionada()
    {
		if (!isset($this->s__encuesta)) {
            $this->s__encuesta = toba::consulta_php('consultas_encuestas')->get_encuesta($this->controlador()->get_encuesta_filtrada());
        }
        
		return $this->s__encuesta;
	}
	
	function get_puntaje_seleccionado()
    {
		$temp = $this->controlador()->get_seleccion();
		return isset($temp) ? $temp['puntaje'] : null;
	}
    
    function get_datos_tabla($tabla)
    {
        if ($tabla == 'sge_puntaje') {
            return $this->controlador()->dep('datos')->tabla($tabla)->get();
        } else {
            return $this->controlador()->dep('datos')->tabla($tabla)->get_filas();
        }
    }
    
    function set_datos_tabla($tabla, $datos)
    {
        if ($tabla == 'sge_puntaje') {
            $this->controlador()->dep('datos')->tabla($tabla)->set($datos);
        } else {
            $this->controlador()->dep('datos')->tabla($tabla)->procesar_filas($datos);
        }
    }
    
    function setear_cursor_tabla($tabla, $seleccion)
    {
        $this->controlador()->dep('datos')->tabla($tabla)->set_cursor($seleccion);
    }
    
    function resetear_cursor_tabla($tabla)
    {
        $this->controlador()->dep('datos')->tabla($tabla)->resetear_cursor();
    }
    
    function get_cantidad_filas_tabla($tabla)
    {
        return $this->controlador()->dep('datos')->tabla($tabla)->get_cantidad_filas();
    }
    
    function get_datos_puntaje_pregunta()
    {
        $datos = $this->controlador()->dep('datos')->tabla('sge_puntaje_pregunta')->nueva_busqueda();
        $datos->set_columnas_orden(array('puntaje_pregunta' => SORT_ASC));
        return $datos->buscar_filas();
    }
    
    function get_datos_puntaje_respuesta()
    {
        $datos = $this->controlador()->dep('datos')->tabla('sge_puntaje_respuesta')->nueva_busqueda();
        $datos->set_columnas_orden(array('puntaje_respuesta' => SORT_ASC));
        return $datos->buscar_filas();
    }

	//-----------------------------------------------------------------------------------
	//---- form_puntaje -----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_puntaje(bootstrap_formulario $form)
	{
		if (isset($this->datos_puntaje_temp)) {
			$datos = $this->datos_puntaje_temp;
		} elseif ($this->s__puntaje) {  // Es una modificación
			$datos = $this->get_datos_tabla('sge_puntaje');
            
            if ($datos['implementado'] == 'S') {
                $form->set_solo_lectura(['nombre']);
            }
		} else {                        // Es un alta
			$datos = [];
		}
		
		$encuesta_sel = $this->get_encuesta_seleccionada();
		$datos['encuesta'] = $encuesta_sel['encuesta'];
		$datos['nombre_encuesta']= $encuesta_sel['nombre'];
		$form->set_datos($datos);
        $form->set_solo_lectura(['encuesta', 'nombre_encuesta']);
	}

	function evt__form_puntaje__modificacion($datos)
	{
		try {
            $this->datos_puntaje_temp = $datos;
            $this->set_datos_tabla('sge_puntaje', $datos);
		} catch (toba_error $e) {
			throw $e;
		}
	}

	//-----------------------------------------------------------------------------------
	//---- ml_preguntas -----------------------------------------------------------------
	//-----------------------------------------------------------------------------------
    
    function conf__ml_preguntas(bootstrap_ml_formulario $form_ml)
	{
        $this->s__puntaje =  $this->get_puntaje_seleccionado();
        $cant_filas = $this->get_cantidad_filas_tabla('sge_puntaje_pregunta');
        
        if ($cant_filas > 0) {
            $datos = $this->get_datos_puntaje_pregunta();
            
            foreach ($datos as $key => $value) {
                $datos_pregunta = toba::consulta_php('consultas_evaluacion')->get_datos_encuesta_definicion($value['encuesta_definicion']);
                $datos[$key]['nombre_bloque']   = $datos_pregunta['nombre_bloque'];
                $datos[$key]['nombre_pregunta'] = $datos_pregunta['nombre_pregunta'];
            }
        } else {
            $encuesta = $this->get_encuesta_seleccionada();
            $datos = toba::consulta_php('consultas_evaluacion')->get_preguntas_puntaje_encuesta($encuesta['encuesta'], $this->s__puntaje);
            
            foreach ($datos as $key => $value ) {
                $datos[$key]['apex_ei_analisis_fila'] = 'A';
            }
        }
        
        $puntaje = $this->get_datos_tabla('sge_puntaje');
        
        if ($puntaje['implementado'] == 'S') {
            $form_ml->set_solo_lectura();
            $form_ml->eliminar_evento('asignar_puntajes_respuestas');
        }
        
		$form_ml->set_datos($datos);
	}
    
    function evt__ml_preguntas__modificacion($datos)
	{
		try {
            $this->set_datos_tabla('sge_puntaje_pregunta', $datos);
		} catch (toba_error $e) {
			throw $e;
		}
	}

	function conf_evt__ml_preguntas__asignar_puntajes_respuestas(toba_evento_usuario $evento, $fila)
	{
        $cant_filas = $this->get_cantidad_filas_tabla('sge_puntaje');
        
        if ($cant_filas == 0) {
            $evento->ocultar();
        } else {
            $evento->mostrar();
        }
	}
    
    function evt__ml_preguntas__asignar_puntajes_respuestas($seleccion)
	{
        $this->pregunta_seleccionda = $this->s__datos_ml_preguntas[$seleccion]['pregunta'];
        $this->pantalla('pant_pregunta')->agregar_dependencia('ml_respuestas', 'kolla', '38000893');
        $this->setear_cursor_tabla('sge_puntaje_pregunta', $seleccion);
	}
	
    //-----------------------------------------------------------------------------------
	//---- ml_respuestas ----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__ml_respuestas(bootstrap_ml_formulario $form_ml)
	{
        $cant_filas = $this->get_cantidad_filas_tabla('sge_puntaje_respuesta');
        
        if ($cant_filas > 0) {          
            $datos = $this->get_datos_tabla('sge_puntaje_respuesta');
            
            $respuestas = encuesta::get_respuestas_pregunta($this->pregunta_seleccionda);
            foreach ($respuestas as $key => $value) {
                $nombre_respuesta[$value['respuesta']] = $value['valor_tabulado'];
            }
            
            foreach ($datos as $key => $value) {
                $datos[$key]['valor_tabulado'] = $nombre_respuesta[$value['respuesta']];
            }
        } else {
            $datos = encuesta::get_respuestas_pregunta($this->pregunta_seleccionda);
            
            foreach ($datos as $key => $value) {
                $datos[$key]['pregunta'] = $this->pregunta_seleccionda;
                $datos[$key]['apex_ei_analisis_fila'] = 'A';
            }
        }
        
        $form_ml->set_datos($datos);
	}

	function evt__ml_respuestas__guardar($datos)
	{
        try {
            $this->set_datos_tabla('sge_puntaje_respuesta', $datos);
            $this->resetear_cursor_tabla('sge_puntaje_pregunta');
        } catch (toba_error $e) {
            throw $e;
        }
	}
    
    function evt__ml_respuestas__cancelar()
	{
        $this->resetear_cursor_tabla('sge_puntaje_pregunta');
	}
    
	function reset()
    {
		unset($this->s__encuesta);
		unset($this->s__puntaje);
        unset($this->s__datos_ml_preguntas);
	}

}
?>