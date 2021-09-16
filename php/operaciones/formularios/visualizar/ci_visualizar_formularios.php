<?php
class ci_visualizar_formularios extends toba_ci
{
	
	protected $s__filtro;
	protected $s__seleccion;

	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------
	
	function conf__seleccion(toba_ei_pantalla $pantalla)
	{
		toba::memoria()->eliminar_dato('visualizar_formulario_id');
		toba::memoria()->eliminar_dato('visualizar_formulario_paginado');
	}

	function conf__edicion(toba_ei_pantalla $pantalla)
	{
		toba::memoria()->set_dato('visualizar_formulario_id', $this->s__seleccion['formulario']);
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
		if ( isset($this->s__filtro) ) {
			$where = $this->dep('filtro')->get_sql_where();
			return toba::consulta_php('consultas_formularios')->get_formularios($where);
		}
	}

	function evt__cuadro__seleccion($seleccion)
	{
		$this->s__seleccion = $seleccion;
		$this->set_pantalla('edicion');
	}

	//-----------------------------------------------------------------------------------
	//---- filtro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__filtro__filtrar($filtro)
	{
		$this->s__filtro = $filtro;
	}
	
	function conf__filtro()
	{
		if ( isset($this->s__filtro) ) {
			return $this->s__filtro;
		}
	}
	
	function evt__filtro__cancelar()
	{
		unset($this->s__filtro);
	}

	//-----------------------------------------------------------------------------------
	//---- formulario -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__formulario(toba_ei_formulario $form)
	{
		$datos = toba::consulta_php('consultas_formularios')->get_datos_formulario($this->s__seleccion['formulario']);
		$form->set_titulo($datos['nombre']);
		$form->set_datos($datos);
	}

	function evt__formulario__visualizar($datos)
	{
		toba::memoria()->set_dato('visualizar_formulario_paginado', $datos['paginado']);
		toba::vinculador()->navegar_a(null, 46000023);//visualiz. form_definicion
	}

	function evt__formulario__cancelar()
	{
		$this->set_pantalla('seleccion');
	}

}

?>