<?php

class ci_nav_crear extends ci_navegacion_por_ug
{
	protected $datos_temp;
	protected $datos_ml_temp;
    protected $nombre_duplicado = false;
    
    //------------------------------------------------------------------------------------
	//---- PANTALLA SELECCION ------------------------------------------------------------
	//------------------------------------------------------------------------------------
    
    function conf__seleccion($pantalla)
    {
        $this->dep('filtro')->columna('nombre')->set_condicion_fija('contiene', true);
        $this->dep('filtro')->columna('descripcion')->set_condicion_fija('contiene', true);
        $this->dep('filtro')->columna('estado')->set_condicion_fija('es_igual_a', true);
        
        if (toba::memoria()->existe_dato('unidad_gestion_popup')) {
            toba::memoria()->eliminar_dato('unidad_gestion_popup');
        }
    }
    
    //-- CUADRO ---------------------------------------------------------
	
	function get_listado()
	{
        $this->set_ug();
        $this->s__datos['unidad_gestion']['valor'] = $this->s__ug;
        
        return toba::consulta_php('consultas_formularios')->get_formularios($this->s__datos);
	}
	
	function get_etiquetas_cuadro()
	{
		return array('formularios');
	}
	
	//------------------------------------------------------------------------------------
	//---- PANTALLA EDICION --------------------------------------------------------------
	//------------------------------------------------------------------------------------
	
	//---- formulario -------------------------------------------------------------------
	
	function conf__formulario(toba_ei_formulario $form)
	{
		if (isset($this->datos_temp)) {
			$datos = $this->datos_temp;
		} else {
            $datos = $this->dep('datos')->tabla('atributos')->get();
		}
        $datos['unidad_gestion'] = $this->s__ug;
        $form->set_datos($datos);
	}

	function evt__formulario__modificacion($datos)
	{
		try {
			$this->validar_datos($datos);
			$this->dep('datos')->tabla('atributos')->set($datos);
		} catch (toba_error $e) {
			$this->datos_temp = $datos;
			throw $e;
		}
	}

	function validar_datos($datos)
	{
		$datos['formulario'] = $this->dep('datos')->tabla('atributos')->get_columna('formulario');
		
		if (!toba::consulta_php('consultas_formularios')->validar_nombre_formulario($datos['nombre'], $datos['formulario'], $this->s__ug)) {
			/*
			 * Difiero el lanzamiento para la excepción del nombre de formulario repetido porque
			 * de lo contrario se podrían llegar a perder los cambios en el formulario multilinea
			 */
			$this->nombre_duplicado = true;
		}
	}
	
	//---- formulario_ml ----------------------------------------------------------------
	
	function conf__formulario_ml(toba_ei_formulario_ml $form_ml)
	{
        //Seteo de la Unidad de Gestión a usar en el popup
        if (isset($this->s__datos)) {
            toba::memoria()->set_dato('unidad_gestion_popup', $this->s__ug);
        }
        
		if (isset($this->datos_ml_temp)) {
			$form_ml->set_datos($this->datos_ml_temp);
		} else {
			$form_ml->set_datos($this->dep('datos')->tabla('definicion')->get_filas());
		}
	}

	function evt__formulario_ml__modificacion($datos)
	{
		try {
			$this->validar_datos_definicion($datos);
			$this->dep('datos')->tabla('definicion')->procesar_filas($datos);
		} catch (toba_error $e) {
			$this->set_datos_ml_temp($datos);
			throw $e;
		}
	}
	
	function set_datos_ml_temp($datos_ml)
	{
		$this->datos_ml_temp = array();
		foreach ($datos_ml as $dato) {
			if ($dato['apex_ei_analisis_fila'] != 'B') {
				$this->datos_ml_temp[] = $dato;
			}
		}
	}
	
	function validar_datos_definicion($filas)
	{
		if ($this->nombre_duplicado) {
			throw new toba_error($this->get_mensaje('dato_duplicado', array('el Formulario')));
		}
		
		$definicion = array();
		$formulario_definido = false;
		
		foreach ($filas as $fila) {
			if ($fila['apex_ei_analisis_fila'] != 'B') {
				$encuesta  = $fila['encuesta'];
				$tipo_elem = $fila['tipo_elemento'];
				if (in_array($encuesta.'_'.$tipo_elem, $definicion)) {
					throw new toba_error($this->get_mensaje('filas_duplicadas'));
				}
				
				$definicion[] = $encuesta.'_'.$tipo_elem;
				$formulario_definido = true;
			}
		}
		
		if (!$formulario_definido) {
			throw new toba_error($this->get_mensaje('falta_encuesta'));
		}
	}
    
    function get_encuestas()
    {
        $where  = 'sge_encuesta_atributo.unidad_gestion = '.kolla_db::quote($this->s__ug);
        $where .= " AND sge_encuesta_atributo.estado = 'A'";
        return toba::consulta_php('consultas_encuestas')->get_combo_encuestas($where);
    }
		
}
?>