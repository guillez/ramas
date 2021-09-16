<?php

class ci_preguntas_dependientes extends ci_navegacion_por_ug
{
    //------------------------------------------------------------------------------------
	//---- PANTALLA SELECCION ------------------------------------------------------------
	//------------------------------------------------------------------------------------
    
    function conf__seleccion($pantalla)
    {
        $this->dep('filtro')->columna('nombre')->set_condicion_fija('es_igual_a', true);
    }
    
    //-- cuadro
    
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
    
    //-- Auxiliares
    
    function get_encuesta()
    {
        return $this->s__seleccion['encuesta'];
    }

}