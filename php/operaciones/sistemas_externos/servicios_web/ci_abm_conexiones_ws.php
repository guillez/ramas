<?php

class ci_abm_conexiones_ws extends ci_navegacion_por_ug
{
	protected $s__conexion;

	//------------------------------------------------------------------------------------
	//---- PANTALLA SELECCION ------------------------------------------------------------
	//------------------------------------------------------------------------------------
    
    //---- cuadro -----------------------------------------------------------------------

	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
        $this->set_ug();
        $filtro_ug = "sge_ws_conexion.unidad_gestion = ".kolla_db::quote($this->s__ug);
        $datos = toba::consulta_php('consultas_mgi')->get_conexiones_ws($filtro_ug);
        $cuadro->set_datos($datos);
	}

	function evt__cuadro__seleccion($seleccion)
	{
		$this->s__seleccion = $seleccion;
		$this->set_pantalla('edicion');
	}
    
    //------------------------------------------------------------------------------------
	//---- PANTALLA EDICION --------------------------------------------------------------
	//------------------------------------------------------------------------------------
    
    function conf__edicion(toba_ei_pantalla $pantalla)
	{
		if (!isset($this->s__seleccion)) {
			$pantalla->eliminar_evento('eliminar');
		}
	}
    
	//---- form conexion ----------------------------------------------------------------

	function conf__conexion(toba_ei_formulario $form)
	{
		if (isset($this->s__seleccion)) {
			$this->tabla()->cargar($this->s__seleccion);
			return $this->tabla()->get();
		} else {
            $form->set_datos(array('unidad_gestion' => $this->s__ug));
        }
	}

	function evt__conexion__modificacion($datos)
	{
        $this->s__conexion = $datos;
	}
    
    //---- Eventos ----------------------------------------------------------------------
	
	function evt__cancelar()
	{
		$this->resetear();
	}
	
	function evt__eliminar()
	{
		$this->tabla()->eliminar_filas();
		$this->tabla()->sincronizar();
		$this->resetear();
	}
	
	function evt__guardar()
	{
		$this->tabla()->set($this->s__conexion);
		$this->tabla()->sincronizar();
		$this->resetear();
	}
    
    //------------------------------------------------------------------------------------
	//---- AUXILIARES --------------------------------------------------------------------
	//------------------------------------------------------------------------------------
    
    function tabla()
	{
		return $this->dep('ws_conexiones');
	}

	function resetear()
	{
		unset($this->s__seleccion);
		unset($this->s__conexion);
		$this->tabla()->resetear();
		$this->set_pantalla('listado');
	}
    
    function get_tipos_servicios()
    {
        return array(
            array(
                'clave'     => 'soap',
                'nombre'    => 'SOAP'
            ),
            array(
                'clave'     => 'rest',
                'nombre'    => 'REST'
            ),
        );
    }
    
}
?>