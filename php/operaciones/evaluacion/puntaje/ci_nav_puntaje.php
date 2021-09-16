<?php
class ci_nav_puntaje extends ci_navegacion
{
	function get_seleccion()
    {
		return $this->s__seleccion;
	}
	
	function get_encuesta_filtrada()
    {
		return isset($this->s__datos['encuesta']['valor']) ? $this->s__datos['encuesta']['valor'] : null;
	}
	
	function get_listado()
	{
		$filtro = $this->s__datos['encuesta']['valor'];
		return toba::consulta_php('consultas_evaluacion')->get_puntajes_encuesta($filtro);
	}
	
	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
		if (isset($this->s__filtro)) {
			$cuadro->set_datos($this->get_listado());
		}
	}

	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__seleccion(toba_ei_pantalla $pantalla)
	{
		if (!isset($this->s__datos)) {
			$pantalla->eliminar_evento('agregar');
		}
	}
    
    //------------------------------------------------------------------------------------
	//---- Eventos -----------------------------------------------------------------------
	//------------------------------------------------------------------------------------
    
    function evt__guardar()
	{
		try {
			$this->dep('datos')->sincronizar();
			toba::notificacion()->agregar($this->get_mensaje('guardar_ok'), 'info');
		} catch (toba_error $e) {
			throw new toba_error($e->getMessage());
		}
	}
	
	function evt__cancelar()
	{
		$this->dep('editor')->reset();
		parent::cancelar();
	}
    
}
?>