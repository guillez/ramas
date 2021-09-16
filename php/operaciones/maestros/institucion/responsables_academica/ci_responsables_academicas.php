<?php 

use ext_bootstrap\componentes\bootstrap_ci;

class ci_responsables_academicas extends bootstrap_ci
{
	protected $s__filtro;
	
	function get_relacion ()
	{
		return $this->dependencia('datos');
	}
	
	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__agregar()
	{
		unset($this->s__filtro);
		unset($this->s__seleccion);
		$this->set_pantalla('edicion');
	}

	//-----------------------------------------------------------------------------------
	//---- Pantalla Selección -----------------------------------------------------------
	//-----------------------------------------------------------------------------------
	
	//---- cuadro -----------------------------------------------------------------------
	
	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
		if (isset($this->s__filtro)) {
			$where = $this->dep('filtro')->get_sql_where();
		}
		
		$datos = toba::consulta_php('consultas_mgi')->get_responsables_academicas(isset($where) ? $where : null);
		$cuadro->set_datos($datos);
	}

	function evt__cuadro__seleccion($seleccion)
	{
		$this->get_relacion()->cargar($seleccion);
		$this->set_pantalla('edicion');	
	}	

	//---- filtro -----------------------------------------------------------------------
	
	function conf__filtro(toba_ei_filtro $filtro)
	{
		if (isset($this->s__filtro)) {
			return $this->s__filtro;
		}
	}

	function evt__filtro__filtrar($datos)
	{
		if (isset($datos)) {
			$this->s__filtro = $datos;
		}
	}

	function evt__filtro__cancelar()
	{
		unset($this->s__filtro);
	}

}
?>