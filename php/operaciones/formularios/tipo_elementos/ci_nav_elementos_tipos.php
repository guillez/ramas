<?php

class ci_nav_elementos_tipos extends ci_navegacion_por_ug
{
	protected $datos_temp;
    
    //------------------------------------------------------------------------------------
	//---- PANTALLA SELECCION ------------------------------------------------------------
	//------------------------------------------------------------------------------------
    
    //-- CUADRO ---------------------------------------------------------
	
	function get_listado()
	{
        $this->set_ug();
        return toba::consulta_php('consultas_formularios')->get_tipos_elemento($this->get_filtro('sge_tipo_elemento'));
	}
	
	function get_etiquetas_cuadro()
	{
		return array('tipos de elemento');
	}
	
	//------------------------------------------------------------------------------------
	//---- PANTALLA EDICION --------------------------------------------------------------
	//------------------------------------------------------------------------------------
	
	//---- formulario -------------------------------------------------------------------
	
	function conf__formulario(toba_ei_formulario $form)
	{
		if (isset($this->datos_temp)) {
			$form->set_datos($this->datos_temp);		
		} else {
            $datos = $this->dep('datos')->tabla('tipos_elementos')->get();
            $datos['unidad_gestion'] = $this->s__ug;
			$form->set_datos($datos);
		}
	}

	function evt__formulario__modificacion($datos)
	{
		try {
			$this->validar_datos($datos);
			$this->dep('datos')->tabla('tipos_elementos')->set($datos);	
		} catch (toba_error $e) {
			$this->datos_temp = $datos;
			throw $e;
		}
	}
	
	function validar_datos($datos)
	{
		$datos['tipo_elemento'] = $this->dep('datos')->tabla('tipos_elementos')->get_columna('tipo_elemento');
		if (!toba::consulta_php('consultas_formularios')->validar_descripcion_tipo_elemento($datos['descripcion'], $datos['tipo_elemento'])) {
			throw new toba_error($this->get_mensaje('dato_duplicado', array('el Tipo de Elemento')));
		}
	}
	
	//------------------------------------------------------------------------------------
	//---- Eventos -----------------------------------------------------------------------
	//------------------------------------------------------------------------------------

	function evt__eliminar()
	{
		if (isset($this->s__seleccion)) {
			if (toba::consulta_php('consultas_formularios')->es_tipo_elemento_en_uso($this->s__seleccion['tipo_elemento'])) {
				throw new toba_error($this->get_mensaje('eliminar_error', array('la definición de formularios')));
			}
		}
		parent::evt__eliminar();
	}
	
}
?>