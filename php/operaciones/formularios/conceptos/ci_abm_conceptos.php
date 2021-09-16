<?php

class ci_abm_conceptos extends ci_navegacion_por_ug
{
    protected $datos_temp;
    protected $datos_elementos_temp;
    
    //-----------------------------------------------------------------------------------
	//---- PANTALLA SELECCION -----------------------------------------------------------
	//-----------------------------------------------------------------------------------
	
	//---- cuadro -----------------------------------------------------------------------
	
	function get_listado()
	{
        $this->set_ug();
        return toba::consulta_php('consultas_encuestas_externas')->get_conceptos($this->get_filtro('sge_concepto'));
	}
	
	function get_etiquetas_cuadro()
	{
		return array('conceptos');
	}
	
	//-----------------------------------------------------------------------------------
	//---- PANTALLA EDICION -------------------------------------------------------------
	//-----------------------------------------------------------------------------------
	
	//---- concepto ---------------------------------------------------------------------
	
	function conf__concepto(toba_ei_formulario $form)
	{
		if (isset($this->s__seleccion)) {
			$datos = $this->dep('datos')->tabla('dt_concepto')->get();
			if (!isset($datos['sistema']) || !isset($datos['concepto_externo'])) {
				$form->desactivar_efs(array('sistema_nombre', 'concepto_externo'));
			} else {
				$filtro = array();
				$filtro['sistema'] = $datos['sistema'];
				$datos_sist = current(toba::consulta_php('consultas_encuestas_externas')->get_sistemas_externos($filtro));
				$datos['sistema_nombre'] = $datos_sist['nombre'];
				$form->set_solo_lectura();
				$this->pantalla()->eliminar_evento('eliminar');
				$this->pantalla()->eliminar_evento('guardar');
			}
            $datos['unidad_gestion'] = $this->s__ug;
			$form->set_datos($datos);
		} else {
            $form->desactivar_efs(array('sistema_nombre', 'concepto_externo'));
            
            if (isset($this->datos_temp)) {
                $form->set_datos($this->datos_temp);
            } else {
                $datos['unidad_gestion'] = $this->s__ug;
                $form->set_datos($datos);
            }
		}
	}

	function evt__concepto__modificacion($datos)
	{
		$this->datos_temp = $datos;
		$this->dep('datos')->tabla('dt_concepto')->set($datos);
	}

	//---- elementos --------------------------------------------------------------------
	
	function conf__elementos(toba_ei_formulario_ml $form_ml)
	{
		$concepto = $this->dep('datos')->tabla('dt_concepto')->get();
        
		if (isset($concepto['sistema']) || isset($concepto['concepto_externo'])) {
			$form_ml->set_solo_lectura();
			$form_ml->desactivar_agregado_filas();
            $form_ml->desactivar_ordenamiento_filas();
		}
		
        if (isset($this->datos_elementos_temp)) {
            $form_ml->set_datos($this->datos_elementos_temp);
        } else {
            $form_ml->set_datos($this->dep('datos')->tabla('dt_elemento')->get_filas());
        }
	}

	function evt__elementos__modificacion($datos)
	{
        //Validaciones a elementos
        $this->validar_existencia($datos);
        $this->validar_duplicados($datos);
        
        //Inserción de los datos
        $this->dep('datos')->tabla('dt_elemento')->eliminar_filas();
        foreach ($datos as $fila) {
			if ($fila['apex_ei_analisis_fila']!= 'B') {
				$this->dep('datos')->tabla('dt_elemento')->nueva_fila($fila);
			}
		}
	}

	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

    function evt__eliminar()
	{
        $this->validar_concepto();
        parent::evt__eliminar();
	}

    //-----------------------------------------------------------------------------------
	//---- Auxiliares -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
    
    function validar_duplicados($datos)
	{
		$elementos = array();
		foreach ($datos as $clave => $valor) {
			if ($valor['apex_ei_analisis_fila'] != 'B') {
                $clave = $valor['elemento'].$valor['tipo_elemento'];
				if (in_array($clave, $elementos)) {
                    $this->datos_elementos_temp = $datos;
					throw new toba_error_validacion('Se intenta ingresar un registro Elemento-Tipo que ya existe. Verifique las asociaciones.');
				} else {
					$elementos[] = $clave;
				}
			}
		}
	}
    
    function validar_concepto()
    {
        if (!isset($this->s__seleccion)) {
            return;
        }
        
        $concepto = $this->dep('datos')->tabla('dt_concepto')->get_columna('concepto');
        $cantidad = toba::consulta_php('consultas_formularios')->get_cantidad_formularios_habilitados_por_concepto($concepto);
        
        if ($cantidad > 0) {
            throw new toba_error('No se puede eliminar el concepto ya que está siendo utilizado por una habilitación.');
        }
    }
    
    function validar_existencia($datos)
    {
        foreach ($datos as $clave => $valor) {
			if ($valor['apex_ei_analisis_fila'] != 'B') {
                return;
			}
		}
        
        $this->datos_elementos_temp = array();
        throw new toba_error('Debe ingresar al menos un Elemento asociado.');
    }
            
	function get_combo_elementos()
    {
        $where = 'unidad_gestion = '.kolla_db::quote($this->s__ug);
        return toba::consulta_php('consultas_formularios')->get_combo_elementos($where);
    }
    
    function get_combo_tipos_elemento()
    {
        $where = 'unidad_gestion = '.kolla_db::quote($this->s__ug);
        return toba::consulta_php('consultas_formularios')->get_combo_tipos_elemento($where);
    }
	
}
?>