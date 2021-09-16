<?php
use ext_bootstrap\componentes\bootstrap_ci;

class ci_popup_tipo_elemento extends bootstrap_ci
{
	protected $datos_temp;
	
	//------------------------------------------------------------------------------------
	//---- PANTALLA INICIAL --------------------------------------------------------------
	//------------------------------------------------------------------------------------
	
	//---- cuadro ------------------------------------------------------------------------

	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
		$cuadro->desactivar_modo_clave_segura();
        $unidad_gestion = toba::memoria()->get_dato('unidad_gestion');
        
        if (isset($unidad_gestion)) {
            $unidad_gestion = kolla_db::quote($unidad_gestion);
            $filtro_ug = "sge_tipo_elemento.unidad_gestion = $unidad_gestion";
        }
        
        $datos = toba::consulta_php('consultas_formularios')->get_tipos_elemento(isset($filtro_ug) ? $filtro_ug : null);
		$mje   = $this->get_mensaje('eof_cuadro', array('Tipos de Elemento'));
		$cuadro->set_eof_mensaje($mje);
		$cuadro->set_datos($datos);
	}
	
	//---- formulario --------------------------------------------------------------------
	
	function conf__formulario(toba_ei_formulario $form)
	{
		if (isset($this->datos_temp)) {
			$form->set_datos($this->datos_temp);
		}
	}
	
	function evt__formulario__agregar($datos)
	{
		try {
            $unidad_gestion = toba::memoria()->get_dato('unidad_gestion');
            
            if (isset($unidad_gestion)) {
                $datos['unidad_gestion'] = $unidad_gestion;
            }
            
			$this->validar_datos($datos);
			$this->dep('tipo_elemento')->nueva_fila($datos);
			$this->dep('tipo_elemento')->sincronizar();	
		} catch (toba_error $e) {
			$this->datos_temp = $datos;
			throw $e;
		}
	}

	//------------------------------------------------------------------------------------
	//---- Validaciones ------------------------------------------------------------------
	//------------------------------------------------------------------------------------
	
	function validar_datos($datos)
	{
		$valida_descripcion = toba::consulta_php('consultas_formularios')->validar_descripcion_tipo_elemento($datos['descripcion']);
		
		if (!$valida_descripcion) {
			throw new toba_error($this->get_mensaje('dato_duplicado', array('el Tipo de Elemento')));
		}
	}


}
?>