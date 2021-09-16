<?php
use ext_bootstrap\componentes\bootstrap_ci;

class ci_preguntas_dependientes_edicion extends bootstrap_ci
{
    //-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__pant_seleccion_deps(toba_ei_pantalla $pantalla)
	{
        if ($this->es_encuesta_desgranamiento()) {
            $pantalla->evento('agregar')->desactivar();
        }
	}
    
    function conf__pant_edicion_deps(toba_ei_pantalla $pantalla)
	{
        if ($this->es_encuesta_desgranamiento()) {
            $pantalla->evento('eliminar')->desactivar();
            $pantalla->evento('guardar')->desactivar();
        }
	}
    
    function es_encuesta_desgranamiento()
    {
        $encuesta = $this->get_encuesta();
        return $encuesta == 8 || $encuesta == 9 || $encuesta == 10;
    }
    
    //---- dependencias -----------------------------------------------------------------
    
    function conf__dependencias(toba_ei_cuadro $cuadro)
    {
        $encuesta = $this->get_encuesta();
        return kolla::co('co_preguntas_dependientes')->get_dependencias($encuesta);
    }
    
    function evt__dependencias__seleccion($seleccion)
    {
        $this->datos()->cargar($seleccion);
        $this->set_pantalla('pant_edicion_deps');
    }

    //-- auxiliares
    
    function get_encuesta()
    {
        return $this->controlador()->get_encuesta();
    }
    
    /**
     * @return toba_datos_relacion
     */
    function datos()
    {
        return $this->dep('datos');
    }
    
    //-- eventos
    
    function evt__volver_encuestas()
    {
        $this->controlador()->set_pantalla('seleccion');
    }
    
    function evt__agregar()
    {
        $this->set_pantalla('pant_edicion_deps');
    }
    
    function evt__eliminar()
    {
        try {
            $this->datos()->eliminar_todo();
            $this->datos()->sincronizar();
            $this->evt__cancelar();
        } catch (toba_error_db $ex) {
            toba::notificacion()->agregar('Ocurrió un error al intentar eliminar la pregunta dependiente.');
            throw $ex;
        }
    }
    
    function evt__cancelar()
    {
        $this->datos()->resetear();
        $this->dep('ci_edicion_deps')->resetear();
        $this->set_pantalla('pant_seleccion_deps');
    }
    
    function evt__guardar()
    {
        try {
            $this->datos()->sincronizar();
            $this->evt__cancelar();
        } catch (toba_error_db $ex) {
            toba::notificacion()->agregar('Ocurrió un error al intentar guardar la pregunta dependiente.');
            throw $ex;
        }
    }

}
?>