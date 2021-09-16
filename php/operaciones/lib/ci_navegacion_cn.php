<?php 

class ci_navegacion_cn extends ci_navegacion
{
	//------------------------------------------------------------------------------------
	//---- PANTALLA SELECCION ------------------------------------------------------------
	//------------------------------------------------------------------------------------
    
    function get_ug()
    {
        return $this->s__datos['unidad_gestion']['valor'];
    }
	
	//---- cuadro -----------------------------------------------------------------------

	function evt__cuadro__seleccion($seleccion)
	{
		$this->s__seleccion = $seleccion;
		$this->cn()->dep('datos')->cargar($seleccion);
		$this->set_pantalla('edicion');
	}
	
	//------------------------------------------------------------------------------------
	//---- PANTALLA EDICION --------------------------------------------------------------
	//------------------------------------------------------------------------------------

	function conf__edicion(toba_ei_pantalla $pantalla)
	{
		if ($this->pantalla('edicion')->existe_evento('eliminar')) {
			if (!$this->cn()->dep('datos')->esta_cargada()) {
				$pantalla->eliminar_evento('eliminar');
			}
		}
	}
	
	//------------------------------------------------------------------------------------
	//---- Eventos -----------------------------------------------------------------------
	//------------------------------------------------------------------------------------

	function evt__eliminar()
	{
		if (isset($this->s__seleccion)) {
			try {
				$this->cn()->dep('datos')->eliminar_todo();
				$this->cancelar();
				toba::notificacion()->agregar($this->get_mensaje('eliminar_ok'), 'info');
			} catch(toba_error $e) {
				throw new toba_error($e->getMessage());
			}
		}
	}

	function evt__guardar()
	{
		try {
			$this->cn()->dep('datos')->sincronizar();
			$this->cancelar();
			toba::notificacion()->agregar($this->get_mensaje('guardar_ok'), 'info');
		} catch (toba_error_db $e) {
            toba::logger()->error($e->get_sql_ejecutado());
			throw new toba_error($e->getMessage());
		}
	}

	function cancelar()
	{
		$this->cn()->dep('datos')->resetear();
		$this->set_pantalla('seleccion');
	}

}
?>