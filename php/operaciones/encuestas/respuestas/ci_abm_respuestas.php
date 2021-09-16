<?php

class ci_abm_respuestas extends ci_navegacion_por_ug
{
	protected $datos_form;
	
    //------------------------------------------------------------------------------------
	//---- PANTALLA SELECCION ------------------------------------------------------------
	//------------------------------------------------------------------------------------
    
	function conf__seleccion($pantalla)
    {
        $this->dep('filtro')->columna('valor_tabulado')->set_condicion_fija('contiene', true);
    }
    
	function evt__guardar()
	{
		parent::evt__guardar();
		$this->dep('cuadro')->set_pagina_actual($this->dep('cuadro')->get_cantidad_paginas());
	}
	
    //-- CUADRO ---------------------------------------------------------
	
	function get_listado()
	{
        $this->set_ug();
        return toba::consulta_php('consultas_encuestas')->get_respuestas($this->get_filtro('tr'), 'respuesta');
	}
	
	function get_etiquetas_cuadro()
	{
		return array('respuestas');
	}
    
    //------------------------------------------------------------------------------------
	//---- PANTALLA EDICION --------------------------------------------------------------
	//------------------------------------------------------------------------------------
	
	//-- FORMULARIO -----------------------------------------------------

	function conf__formulario(toba_ei_formulario $form)
	{
		if ( isset($this->datos_form) ) {
			$datos = $this->datos_form;
            unset($this->datos_form);
		} elseif ( isset($this->s__seleccion) ) {
			$datos =  $this->dep('datos')->get();
            $form->ef('respuesta')->set_solo_lectura();
            
            $es_no_editable = toba::consulta_php('consultas_encuestas')->es_respuesta_no_editable($datos['respuesta']);
        
            if ( $es_no_editable ) {
                $this->pantalla('edicion')->set_descripcion('Esta respuesta no se puede editar');
                $this->evento('eliminar')->anular();
                $this->evento('guardar')->anular();
                $form->ef('valor_tabulado')->set_solo_lectura();
            }
		} else {
            $datos = array('unidad_gestion' => $this->s__ug);
		}
        
        $form->set_datos($datos);
	}
     
	function evt__formulario__modificacion($datos)
	{
		try {
			$this->dep('datos')->set($datos);	
		} catch (toba_error $e) {
			$this->datos_form = $datos;
			throw $e;
		}
	}
	
}
?>