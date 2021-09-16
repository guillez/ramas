<?php

use ext_bootstrap\componentes\bootstrap_ci;

class ci_edicion_responsables_academicas extends bootstrap_ci
{
	protected $es_alta;
	protected $datos_form_temp;
	protected $inst_arau = null;
		
	function get_tabla($id) 
	{
		return $this->get_relacion()->tabla($id);
	}
	
	function get_relacion() 
	{
		return $this->controlador()->get_relacion();
	}
	
	function resetear() 
	{
		$this->get_relacion()->resetear();
		$this->controlador()->set_pantalla('seleccion');
	}
    
    function ra_esta_cargada()
    {
        $datos = $this->get_tabla('ras')->get();
        return !empty($datos);
    }
		
	//-----------------------------------------------------------------------------------
	//---- Pantalla Responsable Académica -----------------------------------------------
	//-----------------------------------------------------------------------------------

	//---- formulario -------------------------------------------------------------------
	
	function conf__formulario(toba_ei_formulario $form)
	{	
		$datos = array();
		$r = toba::consulta_php('consultas_mgi')->get_institucion();
		
		if (isset($this->datos_form_temp)) {
			$datos = $this->datos_form_temp;
		} elseif ($this->ra_esta_cargada()) {
			$datos = $this->get_tabla('ras')->get();
		} elseif (!empty($r)){
			$datos['institucion'] = $r[0]['institucion'];
		}
		
		$this->inst_arau = !empty($r) ? $r[0]['institucion_araucano'] : null;
		$form->set_solo_lectura(array('institucion'));
		$form->set_datos($datos);
	}

	function evt__formulario__modificacion($datos)
	{
		try {
			$this->validar_datos_formulario($datos);
			$this->get_tabla('ras')->set($datos);
		} catch (toba_error $e) {
			$this->datos_form_temp = $datos;
			throw $e;
		}
	}

	function validar_datos_formulario($datos)
	{
		$responsable_academica = $this->get_tabla('ras')->get_columna('responsable_academica');
        
		if (!toba::consulta_php('consultas_mgi')->validar_nombre_responsable_academica($datos['nombre'], $responsable_academica)) {
			throw new toba_error($this->get_mensaje('dato_duplicado', array('la Responsable Académica')));
		}
	}
	
	//-----------------------------------------------------------------------------------
	//---- Pantalla Propuestas ----------------------------------------------------------
	//-----------------------------------------------------------------------------------
	
	//---- ml_propuestas ----------------------------------------------------------------
	
	function conf__ml_propuestas(toba_ei_formulario_ml $form_ml)
	{
		if ($this->get_relacion()->esta_cargada()) {
			$propuestas = $this->get_tabla('propuestas_ra')->get_filas();
			$form_ml->set_datos($propuestas);	
		}		
	}

	function evt__ml_propuestas__modificacion($datos)
	{
		$this->get_tabla('propuestas_ra')->eliminar_filas();
        
		foreach ($datos as $fila) {
			if ($fila['apex_ei_analisis_fila']!= 'B') {
				$this->get_tabla('propuestas_ra')->nueva_fila($fila);
			}
		}
	}

	//-----------------------------------------------------------------------------------
	//---- Pantalla Títulos -------------------------------------------------------------
	//-----------------------------------------------------------------------------------
	
	//---- ml_titulos -------------------------------------------------------------------
	
	function conf__ml_titulos(toba_ei_formulario_ml $form_ml)
	{
		if ($this->get_relacion()->esta_cargada()) { //esta cargada, es edicion de datos
			$titulos = $this->get_tabla('titulos_ra')->get_filas();		
			if (!empty($titulos)) {	//ya tiene titulos cargados, se muestran esos datos
				$form_ml->set_datos($titulos);				
			}
		} else { //no esta cargada, es un alta, sugerir titulos segun las propuestas que haya asociado 
			$propuestas = $this->get_tabla('propuestas_ra')->get_filas();
			if (!empty($propuestas)) {//buscar los titulos asociados con las propuestas cargadas y pre-cargar esos
				$where = '(';
				$or = '';
				foreach ($propuestas as $p) {
					$where = $where.$or." propuesta = '".$p['propuesta']."'";
					$or = 'OR'; 
				}
				$where = $where.')';
				$titulos = toba::consulta_php('consultas_mgi')->get_titulos_propuestas($where);
				$form_ml->set_datos($titulos);
			} //si no hay propuestas asociadas, no se sugieren titulos
		}
	}

	function evt__ml_titulos__modificacion($datos)
	{
		$this->get_tabla('titulos_ra')->eliminar_filas();
        
		foreach ($datos as $fila) {
			if ($fila['apex_ei_analisis_fila']!= 'B') {
				$this->get_tabla('titulos_ra')->nueva_fila($fila);
			}
		}		
	}

	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__alta()
	{
		$this->evt__guardar();	
	}	
	
	function evt__guardar()
	{
		$this->get_relacion()->sincronizar();
		$this->resetear();	
	}

	function evt__eliminar()
	{
		$this->get_relacion()->eliminar_todo();
		$this->resetear();
	}

	function evt__cancelar()
	{
		$this->get_relacion()->resetear();
		$this->controlador()->set_pantalla('seleccion');		
	}

	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__responsables_academicas(toba_ei_pantalla $pantalla)
	{
		if ($this->get_relacion()->esta_cargada()) { 
			$this->es_alta = false; //es edicion de datos
			$pantalla->eliminar_evento('alta');
		} else {
			$this->es_alta = true; //es un alta
			$pantalla->eliminar_evento('eliminar');
			$pantalla->eliminar_evento('guardar');
		} 					
	}

	function conf__propuestas(toba_ei_pantalla $pantalla)
	{
		if ($this->get_relacion()->esta_cargada()) {
			$this->es_alta = false; //es edicion de datos
			$pantalla->eliminar_evento('alta');			
		} else {
			$this->es_alta = true; //es un alta
			$pantalla->eliminar_evento('eliminar');
			$pantalla->eliminar_evento('guardar');
		} 					
	}
	
	function conf__titulos(toba_ei_pantalla $pantalla)
	{
		if ($this->get_relacion()->esta_cargada()) {
			$this->es_alta = false; //es edicion de datos
			$pantalla->eliminar_evento('alta');			
		} else {
			$this->es_alta = true; //es un alta
			$pantalla->eliminar_evento('eliminar');
			$pantalla->eliminar_evento('guardar');
		} 
	}
	
	function get_localidad($id) 
	{	
		$localidad = toba::consulta_php('consultas_mug')->get_localidades($id);
		return $localidad[0]['nombre'];
	}
	
	function get_ra_araucano()
	{
		return toba::consulta_php('consultas_mgi')->get_ras_aucano_institucion($this->inst_arau);
	}
}
?>