<?php

class ci_abm_multi_respuestas_preguntas extends ci_navegacion_por_ug
{
    //------------------------------------------------------------------------------------
	//---- PANTALLA SELECCION ------------------------------------------------------------
	//------------------------------------------------------------------------------------

	//-- CUADRO --
	
    function get_listado()
	{
        $this->set_ug();
        return toba::consulta_php('consultas_encuestas')->get_preguntas_con_respuesta($this->get_filtro('p'));
	}
    
    function get_etiquetas_cuadro()
	{
		return array('preguntas');
	}
    
	function evt__cuadro__seleccion($seleccion)
	{
		$this->dependencia('relacion')->cargar($seleccion);
		$this->set_pantalla('edicion');
	}
    
    //------------------------------------------------------------------------------------
	//---- PANTALLA EDICION --------------------------------------------------------------
	//------------------------------------------------------------------------------------

	//-- FORMULARIO DETALLE

	function conf__detalle(toba_ei_formulario_ml $form_ml)
	{
		$pregunta = $this->dep('relacion')->tabla('preguntas')->get();
        
		if (!empty($pregunta)) {
            if (toba::consulta_php('consultas_encuestas')->es_pregunta_no_editable($pregunta['pregunta'])) {
                $this->pantalla('edicion')->set_descripcion('No se pueden editar las respuestas asociadas a esta pregunta.');
                $form_ml->set_solo_lectura();
                $form_ml->desactivar_agregado_filas();
                $form_ml->desactivar_ordenamiento_filas();
                $this->evento('procesar')->anular();
            }
        }
        
        $filas = $this->dep('relacion')->tabla('preguntas_respuestas')->get_filas();
        $form_ml->set_datos($filas);
	}

	function evt__detalle__modificacion($datos)
	{
		$this->dependencia('relacion')->tabla('preguntas_respuestas')->procesar_filas($datos);
	}

	//-- FORMULARIO MAESTRO

	function conf__maestro($componente)
	{
        $datos = $this->dep('relacion')->tabla('preguntas')->get();
		$componente->set_datos($datos);
	}

	function evt__maestro__modificacion($datos)
	{
		$this->dependencia('relacion')->tabla('preguntas')->set($datos);
	}
    
    function get_respuestas_para_combo($codigo=null)
    {
        $datos = $this->dep('relacion')->tabla('preguntas')->get();
        return toba::consulta_php('consultas_encuestas')->get_respuestas_para_combo($codigo, $datos['unidad_gestion']);
    }
    
    //-------------------------------------------------------------------
	//--- Eventos
	//-------------------------------------------------------------------

	function evt__procesar()
	{
		try {
			$this->dependencia('relacion')->sincronizar();
			$this->dependencia('relacion')->resetear();
			$this->set_pantalla('seleccion');
		} catch(toba_error $e) {
			toba::notificacion()->agregar('Verifique no haber incluido más de una vez la misma respuesta, o estar modificando una pregunta que ya esta definida para una o más encuestas.');
			toba::logger()->error($e->GetMessage());
		}
	}

	function evt__cancelar()
	{
		$this->dependencia('relacion')->resetear();
		$this->set_pantalla('seleccion');
	}
	
}
?>