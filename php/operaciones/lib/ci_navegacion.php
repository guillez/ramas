<?php 

use ext_bootstrap\componentes\bootstrap_ci;

class ci_navegacion extends bootstrap_ci
{
	protected $s__filtro;
	protected $s__datos;
	protected $s__seleccion;
	protected $indx_msg_eof = '';

	function conf()
	{
		$this->indx_msg_eof = 'eof_cuadro';
	}

	//------------------------------------------------------------------------------------
	//---- PANTALLA SELECCION ------------------------------------------------------------
	//------------------------------------------------------------------------------------
	
	//---- filtro -----------------------------------------------------------------------

	function get_filtro_condicion()
	{
		return isset($this->s__filtro) ? $this->s__filtro : null;
	}

	function evt__filtro__filtrar($datos)
	{
		if (isset($datos) && !empty($datos)) {
			$this->s__datos = $datos;
			$this->s__filtro = $this->dep('filtro')->get_sql_where();
		} else {
			unset($this->s__datos);
			$this->s__filtro = '';
		}
	}

	function evt__filtro__cancelar()
	{
		$this->cancelar_filtro();
	}

	function conf__filtro(toba_ei_filtro $filtro)
	{
		if (isset($this->s__datos)) {
			$filtro->set_datos($this->s__datos);
		}		
	}
	
	function cancelar_filtro()
	{
		unset($this->s__datos);
		unset($this->s__filtro);
	}

	//---- cuadro -----------------------------------------------------------------------

	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
		if (isset($this->s__filtro)) {
			$this->indx_msg_eof .= '_filtrado';
		}
		
		$cuadro->set_datos($this->get_listado());
		$cuadro->set_eof_mensaje($this->get_mensaje($this->indx_msg_eof, $this->get_etiquetas_cuadro()));
	}
	
	function evt__cuadro__seleccion($seleccion)
	{
		$this->s__seleccion = $seleccion;
		$this->dep('datos')->cargar($seleccion);
		$this->set_pantalla('edicion');
	}
	
	//------------------------------------------------------------------------------------
	//---- PANTALLA EDICION --------------------------------------------------------------
	//------------------------------------------------------------------------------------

	function conf__edicion(toba_ei_pantalla $pantalla)
	{
        if ($this->pantalla('edicion')->existe_evento('eliminar') && (!$this->dep('datos')->esta_cargada())) {
            $pantalla->eliminar_evento('eliminar');
		}
	}
	
	//------------------------------------------------------------------------------------
	//---- Eventos -----------------------------------------------------------------------
	//------------------------------------------------------------------------------------

	function evt__agregar()
	{
		$this->set_pantalla('edicion');
	}

	function evt__eliminar()
	{
		if (isset($this->s__seleccion)) {
			try {
				$this->dep('datos')->eliminar_todo();
                $this->cancelar();
				toba::notificacion()->agregar($this->get_mensaje('eliminar_ok'), 'info');
			} catch(toba_error $e) {
                $this->dep('datos')->cargar($this->s__seleccion);
				throw new toba_error($e->getMessage());
			}
		}
	}

	function evt__guardar()
	{
		try {
			$this->dep('datos')->sincronizar();
			$this->cancelar();
			toba::notificacion()->agregar($this->get_mensaje('guardar_ok'), 'info');
		} catch (toba_error $e) {
			throw new toba_error($e->getMessage());
		}
	}

	function evt__cancelar()
	{
		$this->cancelar();
	}
	
	function cancelar()
	{
		$this->dep('datos')->resetear();
        unset($this->s__seleccion);
		$this->set_pantalla('seleccion');
	}

}
?>