<?php 

use ext_bootstrap\componentes\bootstrap_ci;

class ci_propuestas extends bootstrap_ci
{	
	protected $s__filtro;
	
	function tabla($id) 
	{
		return $this->relacion()->tabla($id);
	}
	
	function relacion() 
	{
		return $this->dependencia('datos');
	}
	
	function resetear() 
	{
		unset($this->s__seleccion);
		$this->relacion()->resetear();
		$this->set_pantalla('seleccion');
	}
	
	//---- Configuracion ----------------------------------------------------------------
	
	function conf__edicion(toba_ei_pantalla $pantalla)
	{
		if (!$this->relacion()->esta_cargada()) {
			$pantalla->eliminar_evento('eliminar');
		}
	}
	
	//---- Eventos ----------------------------------------------------------------------
	
	function evt__agregar()
	{
		unset($this->s__filtro);
		$this->relacion()->resetear();
		$this->set_pantalla('edicion');
	}

	function evt__cancelar()
	{
		$this->set_pantalla('seleccion');
	}

	function evt__guardar()
	{
		$this->relacion()->sincronizar();
		$this->resetear();
	}	

	function evt__eliminar()
	{
		$this->relacion()->eliminar_todo();
		$this->resetear();
	}		
	
	//---- cuadro -----------------------------------------------------------------------

	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
		return toba::consulta_php('consultas_mgi')->get_propuestas($this->dep('filtro')->get_sql_where());
	}

	function evt__cuadro__seleccion($seleccion)
	{
		$this->relacion()->cargar($seleccion); 
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

	//---- formulario -------------------------------------------------------------------

	function conf__formulario(toba_ei_formulario $form)
	{ 
		return $this->tabla('propuestas')->get();
	}

	function evt__formulario__modificacion($datos)
	{
		$this->tabla('propuestas')->set($datos);
	}
	
	//---- titulos_ml -------------------------------------------------------------------

	function conf__titulos_ml(toba_ei_formulario_ml $form_ml)
	{
		return $this->tabla('titulos_propuestas')->get_filas();
	}

	function evt__titulos_ml__modificacion($datos)
	{		
		$this->tabla('titulos_propuestas')->eliminar_filas();
        
		foreach ($datos as $fila) {
			if ($fila['apex_ei_analisis_fila']!= 'B') {
				$this->tabla('titulos_propuestas')->nueva_fila($fila);
			}
		}
	}	
}

?>