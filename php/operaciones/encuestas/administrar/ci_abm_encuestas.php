<?php

class ci_abm_encuestas extends ci_navegacion_por_ug
{
    //------------------------------------------------------------------------------------
	//---- PANTALLA SELECCION ------------------------------------------------------------
	//------------------------------------------------------------------------------------
    
    function conf__seleccion($pantalla)
    {
        $this->dep('filtro')->columna('implementada')->set_condicion_fija('es_igual_a', true);
        $this->dep('filtro')->columna('nombre')->set_condicion_fija('contiene', true);
    }
    
    //-- CUADRO ---------------------------------------------------------
	
	function get_listado()
	{
        $this->set_ug();
        return toba::consulta_php('consultas_encuestas')->get_encuestas($this->get_filtro('ug'));
	}
	
	function get_etiquetas_cuadro()
	{
		return array('encuestas');
	}
	
	function evt__cuadro__seleccion($seleccion)
	{
		$this->s__seleccion = $seleccion;
		$this->set_pantalla('edicion');
	}

	//-----------------------------------------------------------------------------------
	//---- Varios -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function resetear()
	{
		if (isset($this->s__seleccion)) {
			unset($this->s__seleccion);
		}
		$this->set_pantalla('seleccion');
	}
	
	function get_encuesta()
	{
		if (isset($this->s__seleccion)) {
			return $this->s__seleccion['encuesta'];
		}
	}
	
	function set_encuesta($encuesta)
	{
		$this->s__seleccion = array('encuesta' => $encuesta);
	}
    
    function get_ug()
    {
        return $this->s__ug;
    }
	
}