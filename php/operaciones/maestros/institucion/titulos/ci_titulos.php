<?php

use ext_bootstrap\componentes\bootstrap_ci;

class ci_titulos extends bootstrap_ci
{
	protected $s__filtro;
	protected $s__seleccion;
	protected $s__datos;
	
	function tabla() 
	{
		return $this->dep('datos');
	}
	
	function resetear() 
	{
		unset($this->s__datos);
		unset($this->s__seleccion);
		$this->tabla()->resetear();
		$this->set_pantalla('seleccion');
	}
	
	//---- Configuracion ----------------------------------------------------------------
	
	function conf__edicion(toba_ei_pantalla $pantalla)
	{
		if ( !$this->tabla()->esta_cargada() ) {
			$pantalla->eliminar_evento('eliminar');
		}
	}
	
	//---- Eventos ----------------------------------------------------------------------
	
	function evt__agregar()
	{
		$this->set_pantalla('edicion');
	}
	
	function evt__cancelar() 
	{
		$this->resetear();
	}

	function evt__guardar()
	{
		$this->tabla()->set($this->s__datos);
		$this->tabla()->sincronizar();
		$this->resetear();
	}
	
	function evt__eliminar()
	{
		$this->tabla()->eliminar_filas();
		try{
			$this->tabla()->sincronizar();
			$this->resetear();
		} catch(toba_error $e) {
			toba::notificacion()->agregar('Error borrando: el dato está siendo utilizado por otro registro.');
			toba::logger()->error($e->getMessage());
		}
	}

	//---- cuadro -----------------------------------------------------------------------

	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
		return toba::consulta_php('consultas_mgi')->get_titulos($this->dep('filtro')->get_sql_where());
	}

	function evt__cuadro__seleccion($seleccion)
	{
		$this->s__seleccion = $seleccion;
		$this->tabla()->cargar($seleccion);
		$this->set_pantalla('edicion');
	}

	//---- filtro -----------------------------------------------------------------------

	function conf__filtro(toba_ei_filtro $filtro)
	{
		if ( isset($this->s__filtro) ) {
			return $this->s__filtro;
		}
	}

	function evt__filtro__filtrar($datos)
	{
		if ( isset($datos) ) {
			$this->s__filtro = $datos;
		}
	}

	function evt__filtro__cancelar()
	{
		unset($this->s__filtro);
	}

	//---- fomulario --------------------------------------------------------------------

	function conf__fomulario(toba_ei_formulario $form)
	{
		if ( isset($this->s__datos) ) {
			$form->set_datos($this->s__datos);
			unset($this->s__datos);
		} elseif ( isset($this->s__seleccion) ) {
			$form->set_datos($this->tabla()->get());
		}
	}
	
	function evt__fomulario__modificacion($datos)
	{
		$this->s__datos = $datos;
	}
	
}

?>