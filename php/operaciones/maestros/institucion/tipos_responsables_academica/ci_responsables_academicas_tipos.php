<?php

use ext_bootstrap\componentes\bootstrap_ci;

class ci_responsables_academicas_tipos extends bootstrap_ci
{
	protected $s__seleccion;
	protected $s__filtro;	
	protected $s__datos;
	
	function tabla()
	{
		return $this->dependencia('datos');
	}	
	
	function resetear()
	{
		unset($this->seleccion);
		unset($this->s__datos);
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
	
	function evt__eliminar()
	{
		$this->tabla()->eliminar_filas();
		try {
			$this->tabla()->sincronizar();
			$this->resetear();
		} catch(toba_error $e) {
			toba::notificacion()->agregar('Error borrando: Existen responsables académicas de este tipo.');
			toba::logger()->error($e->getMessage());
		}
	}
	
	function evt__guardar()
	{
		$this->tabla()->set($this->s__datos);
		$this->tabla()->sincronizar();
		$this->resetear();
	}
	
	function evt__cancelar()
	{
		$this->resetear();
	}

	//---- cuadro -----------------------------------------------------------------------

	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
		return toba::consulta_php('consultas_mgi')->get_responsables_academicas_tipos($this->dep('filtro')->get_sql_where());
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
		if (isset($this->s__filtro)) {
			 return $this->s__filtro;
		}
	}

	function evt__filtro__filtrar($filtro)
	{
		if (isset($filtro)) {
			$this->s__filtro = $filtro;
		}		
	}

	function evt__filtro__cancelar()
	{
		unset($this->s__filtro);		
	}

	//---- formulario -------------------------------------------------------------------

	function conf__formulario(toba_ei_formulario $form)
	{
		if ( isset($this->s__datos) ) {
			$form->set_datos($this->s__datos);
			unset($this->s__datos);
		} elseif ( isset($this->s__seleccion) ) {
			$form->set_datos($this->tabla()->get());
		}
	}
	
	function evt__formulario__modificacion($datos)
	{
		$this->s__datos = $datos;
	}
	
}

?>