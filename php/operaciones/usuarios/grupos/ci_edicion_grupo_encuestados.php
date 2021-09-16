<?php

use ext_bootstrap\componentes\bootstrap_ci;

class ci_edicion_grupo_encuestados extends bootstrap_ci
{
    protected $datos_temp;
    
    function relacion()
    {
        return $this->controlador()->relacion();
    }
    
    function resetear()
    {
        $this->relacion()->resetear();
        $this->controlador()->set_pantalla('pant_seleccion');
    }
    
	//-----------------------------------------------------------------------------------
	//---- grupo ------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__grupo(toba_ei_formulario $form)
	{
		if (isset($this->datos_temp)) {
			$datos = $this->datos_temp;
		} else {
			$datos = $this->relacion()->tabla('grupo')->get();
		}
		
        $datos['unidad_gestion'] = $this->controlador()->get_ug();
        $form->set_datos($datos);
	}

	function evt__grupo__modificacion($datos)
	{
		$this->datos_temp = $datos;
		$this->validar_datos($datos);
        $this->relacion()->tabla('grupo')->set($datos);
        
	}
    
	function validar_datos($datos)
	{
		$datos['grupo'] = $this->controlador()->relacion()->tabla('grupo')->get_columna('grupo');
		
		if (!toba::consulta_php('consultas_usuarios')->validar_nombre_grupo($datos['nombre'], $datos['grupo'], $this->controlador()->get_ug())) {
			throw new toba_error($this->get_mensaje('dato_duplicado', array('el Grupo en la Unidad de Gestión seleccionada')));
		}
	}
	
    function evt__cancelar()
	{
        $this->resetear();
	}
    
    function evt__volver()
	{
        $this->dep('edicion_encuestados')->set_pantalla('pant_inicial');
	}

	function evt__eliminar()
	{
        $datos = $this->relacion()->tabla('grupo')->get();
		$this->relacion()->eliminar_todo();
		$this->resetear();
	}

	function evt__guardar()
	{
        try {
            $this->relacion()->sincronizar();
            if (!$this->relacion()->esta_cargada()) {
                $this->relacion()->set_cargado(true);
            } else {
                $this->resetear();
                toba::notificacion()->info('Los datos del grupo se guardaron correctamente.');
            }
        } catch (toba_error_db $e) {
            toba::notificacion()->error('Ocurrió un error al intentar guardar los datos del grupo. Si el error persiste consulte con su administrador.');
            throw $e;
        }
	}

	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf()
	{
        if ($this->relacion()->esta_cargada()) {
            $datos = $this->relacion()->tabla('grupo')->get();
			$utilizado = toba::consulta_php('consultas_usuarios')->es_grupo_utilizado_en_formulario($datos['grupo']);

			if (($utilizado['utilizado']) || ($datos['externo'] == 'S')) {
				
                //Eventos generales
                $this->pantalla()->eliminar_evento('guardar');
                $this->pantalla()->eliminar_evento('eliminar');
                $this->pantalla()->set_descripcion('El grupo no se puede editar porque está siendo utilizado en un formulario habilitado o bien porque el mismo se definió externamente.', 'warning');
                
                //Dependencias
                $this->dep('grupo')->set_solo_lectura();
                $this->dep('edicion_encuestados')->dep('encuestados')->eliminar_evento('editar');
			}
        } else {
            $this->pantalla()->eliminar_evento('eliminar');
            $this->pantalla()->tab('pant_encuestados')->desactivar();
        }
	}

}
?>