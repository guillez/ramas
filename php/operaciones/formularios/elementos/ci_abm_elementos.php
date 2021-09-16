<?php

class ci_abm_elementos extends ci_navegacion_por_ug
{
	//------------------------------------------------------------------------------------
	//---- PANTALLA SELECCION ------------------------------------------------------------
	//------------------------------------------------------------------------------------
    
    //-- CUADRO ---------------------------------------------------------
	
	function get_listado()
	{
        $this->set_ug();
        return toba::consulta_php('consultas_encuestas_externas')->get_elementos($this->get_filtro('sge_elemento'));
	}
    
    function get_etiquetas_cuadro()
	{
		return array('elementos');
	}
	
	function evt__cuadro__seleccion($seleccion)
	{
		$this->s__seleccion = $seleccion;
		$this->dep('elemento')->cargar($seleccion);
		$this->set_pantalla('edicion');
	}

	//-----------------------------------------------------------------------------------
	//---- PANTALLA EDICION -------------------------------------------------------------
	//-----------------------------------------------------------------------------------
	
	function conf__edicion(toba_ei_pantalla $pantalla)
	{
		if (!$this->dep('elemento')->esta_cargada()) {
			$pantalla->eliminar_evento('eliminar');
		}
	}
	
	//---- formulario -------------------------------------------------------------------

	function conf__formulario(toba_ei_formulario $form)
	{
		if ( isset($this->s__datos__form) ) {
			$form->set_datos($this->s__datos__form);
			unset($this->s__datos__form);
		} elseif (isset($this->s__seleccion)) {
			$datos = $this->dep('elemento')->get();
			if (!isset($datos['sistema']) || !isset($datos['elemento_externo'])) {
				$form->desactivar_efs(array('sistema_nombre', 'elemento_externo'));
			} else {
				$filtro = array();
				$filtro['sistema'] = $datos['sistema'];
				$datos_sist = current(toba::consulta_php('consultas_encuestas_externas')->get_sistemas_externos($filtro));
				$datos['sistema_nombre'] = $datos_sist['nombre'];
				$form->set_solo_lectura();
				$this->pantalla()->eliminar_evento('eliminar');
				$this->pantalla()->eliminar_evento('guardar');
			}
            $datos['unidad_gestion'] = $this->s__ug;
            $form->set_datos($datos);
		} else {
			$form->desactivar_efs(array('sistema_nombre', 'elemento_externo'));
            $datos = array('unidad_gestion' => $this->s__ug);
            $form->set_datos($datos);
		}
	}

	function evt__formulario__modificacion($datos)
	{
		$this->s__datos__form = $datos;
	}
	
	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__cancelar()
	{
		unset($this->s__seleccion);
		unset($this->s__datos__form);
		$this->dep('elemento')->resetear();
		$this->set_pantalla('seleccion');
	}
	
	function evt__eliminar()
	{
		try {
			$this->dep('elemento')->eliminar_todo();
			$this->dep('elemento')->sincronizar();
			$this->evt__cancelar();
		} catch(toba_error $e) {
			toba::logger()->error($e->getMessage());
			$this->dep('elemento')->cargar($this->s__seleccion);
			throw $e;
		}
	}
	
	function evt__guardar()
	{
		try {
			$this->dep('elemento')->set($this->s__datos__form);
			$this->dep('elemento')->sincronizar();
			$this->evt__cancelar();
		} catch(toba_error $e) {
			toba::logger()->error($e->getMessage());
			throw $e;
		}
	}
	
}
?>