<?php

use ext_bootstrap\componentes\bootstrap_ci;

class ci_abm_tablas_externas extends bootstrap_ci
{
	protected $s__datos = null;
	
	//------------------------------------------------------------------------------------
	//---- PANTALLA SELECCION UGS --------------------------------------------------------
	//------------------------------------------------------------------------------------
	
	//---- formulario -------------------------------------------------------------------
	
	function conf__formulario(toba_ei_formulario $form)
	{
        if (is_null($this->s__datos['tabla_externa_nombre'])) {
            $datos['tabla_externa_nombre'] = '';
        } else {
            $ugs = toba::consulta_php('co_datos_externos')->get_unidades_gestion_presentes($this->s__datos['tabla_externa_nombre']);
            $datos['tabla_externa_nombre'] = $this->s__datos['tabla_externa_nombre'];
            $datos['unidad_gestion'] = kolla_arreglos::aplanar_matriz_sin_nulos($ugs, 'unidad_gestion');
        }
        
        $form->set_datos($datos);
	}

	function evt__formulario__modificacion($datos)
	{
        $this->s__datos = $datos;
	}
    
    function evt__formulario__recargar($datos)
    {
        $this->evt__formulario__modificacion($datos);
    }
    
    //------------------------------------------------------------------------------------
	//---- Eventos -----------------------------------------------------------------------
	//------------------------------------------------------------------------------------

	function evt__guardar()
	{
        $this->validar_datos();
        
		try {
            toba::db()->abrir_transaccion();
                abm::baja('sge_tabla_externa', ['tabla_externa_nombre' => $this->s__datos['tabla_externa_nombre']]);
                $tabla_externa['tabla_externa_nombre'] = $this->s__datos['tabla_externa_nombre'];

                foreach ($this->s__datos['unidad_gestion'] as $ug) {
                    $tabla_externa['unidad_gestion'] = $ug;
                    abm::alta('sge_tabla_externa', $tabla_externa);
                }
            toba::db()->cerrar_transaccion();
			toba::notificacion()->agregar($this->get_mensaje('guardar_ok'), 'info');
		} catch (toba_error $e) {
            toba::db()->abortar_transaccion();
            toba::notificacion()->agregar('Ocurrió un error al intentar asociar la tabla externa a las unidades de gestión.');
			throw new toba_error($e->getMessage());
		}
	}
    
    function validar_datos()
    {
        if (!isset($this->s__datos['tabla_externa_nombre'])) {
            throw new toba_error('El campo Tabla es obligatorio.');
        }
    }
	
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
            
		//---- Eventos ---------------------------------------------
		
		{$this->objeto_js}.evt__guardar = function()
		{
            var tabla_seleccionada = this.dep('formulario').ef('tabla_externa_nombre').tiene_estado();
            
            if (!tabla_seleccionada) {
                alert('Debe seleccionar la tabla junto con las unidades de gestión que desea asociar.');
                return false;
            }
		}
		";
	}

}
?>