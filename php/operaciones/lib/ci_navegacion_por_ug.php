<?php 

class ci_navegacion_por_ug extends ci_navegacion
{
    protected $s__ug = null;
    
    //------------------------------------------------------------------------------------
	//---- Seteos iniciales y finales de la operación ------------------------------------
	//------------------------------------------------------------------------------------
    
    function ini__operacion()
	{
        $this->s__ug = toba::memoria()->get_dato('unidad_gestion');
	}
    
    function fin()
	{
        $ug = $this->dep('form_unidad_gestion')->ef('unidad_gestion')->get_estado();
        toba::memoria()->set_dato('unidad_gestion', $ug);
	}
    
	//------------------------------------------------------------------------------------
	//---- PANTALLA SELECCION ------------------------------------------------------------
	//------------------------------------------------------------------------------------
	
	//---- form_unidad_gestion -----------------------------------------------------------

	function conf__form_unidad_gestion(toba_ei_formulario $form)
	{
        $form->set_datos(is_null($this->s__ug) ? [] : ['unidad_gestion' => $this->s__ug]);
        
        if (toba::consulta_php('consultas_usuarios')->es_gestor_actual()) {
            $form->set_solo_lectura();
        }
	}
    
    function evt__form_unidad_gestion__modificacion($datos)
	{
        if (!is_null($datos['unidad_gestion'])) {
            $this->s__ug = $datos['unidad_gestion'];
        }
	}

    function evt__form_unidad_gestion__recargar($datos)
    {
        $this->evt__form_unidad_gestion__modificacion($datos);
    }
    
    //-- Auxiliares
    
    function set_ug()
    {
        if (is_null($this->s__ug)) {
            $ugs = toba::consulta_php('consultas_ug')->get_unidad_gestion_combo();
            
            //Seteo la única UG, esto es asi porque un Gestor debería tener una sola
            $this->s__ug = $ugs[0]['unidad_gestion'];
        }
    }
    
    function get_ug()
    {
        return $this->s__ug;
    }
    
    function get_filtro($tabla)
    {
        $filtro_ug = "$tabla.unidad_gestion = ".kolla_db::quote($this->s__ug);
        $filtro    = $this->get_filtro_condicion();
        $filtro    = $filtro ? "$filtro AND $filtro_ug " : $filtro_ug;
        
        return $filtro;
    }
    
}
?>