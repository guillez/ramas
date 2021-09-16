<?php

class ci_abm_unidades_gestion extends ci_navegacion
{
	protected $datos_temp;
	
	//------------------------------------------------------------------------------------
	//---- PANTALLA SELECCION ------------------------------------------------------------
	//------------------------------------------------------------------------------------
	
	function get_listado()
	{
		return toba::consulta_php('consultas_ug')->get_listado($this->get_filtro_condicion());
	}
	
	function get_etiquetas_cuadro()
	{
		return array('unidades de gestión');
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
			$form->set_datos($this->dep('datos')->get());
		}
        
		if ($this->dep('datos')->esta_cargada()) {
			$form->set_solo_lectura(array('unidad_gestion'));
		}
	}

	function evt__formulario__modificacion($datos)
	{
        $this->datos_temp = $datos;
	}
	
	//------------------------------------------------------------------------------------
	//---- Eventos -----------------------------------------------------------------------
	//------------------------------------------------------------------------------------

	function evt__guardar()
	{
		try {
            toba::db()->abrir_transaccion();
			$this->validar_datos($this->datos_temp);
            
            /*
             * Por cada nueva unidad de gestión se crea el grupo de encuestados para habilitaciones públicas
             * y se le agrega el usuario invitado de Kolla, si el mismo no existe se emite un error
             */
            
            if ($this->tiene_datos()) {
                $this->actualizar_grupo();
            } else {
                $this->agregar_grupo();
            }
            
            $this->dep('datos')->set($this->datos_temp);
            $this->dep('datos')->sincronizar();
            $this->cancelar();
			toba::notificacion()->agregar($this->get_mensaje('guardar_ok'), 'info');
            toba::db()->cerrar_transaccion();
		} catch (toba_error $e) {
            toba::db()->abortar_transaccion();
			throw new toba_error($e->getMessage());
		}
	}
    
    function validar_datos($datos)
	{
		$datos['unidad_gestion'] = $this->dep('datos')->get_columna('unidad_gestion');
		
		if (!toba::consulta_php('consultas_ug')->validar_nombre_unidad_gestion($datos['nombre'], $datos['unidad_gestion']) ) {
            toba::db()->abortar_transaccion();
			throw new toba_error($this->get_mensaje('dato_duplicado', array('la Unidad de Gestión')));
		}
	}
    
    function tiene_datos()
    {
        $datos = $this->dep('datos')->get();
        return !empty($datos);
    }

    function agregar_grupo()
    {
        $usuario_invitado = toba::consulta_php('consultas_usuarios')->get_usuario_invitado();
        
        if (empty($usuario_invitado)) {
            toba::db()->abortar_transaccion();
            throw new toba_error('No existe el Usuario Anónimo predeterminado SIU-Kolla.');
        }
        
        $grupo = act_usuarios::insert_grupo_definicion("Grupo invitado - ".$this->datos_temp['nombre'], 'O', 'Grupo predefinido para usuario invitado', $this->datos_temp['unidad_gestion']);
        act_usuarios::insert_grupo_detalle($grupo[0]['grupo'], $usuario_invitado['encuestado']);
    }
    
    function actualizar_grupo()
    {
        $grupo_anonimo = toba::consulta_php('consultas_usuarios')->get_grupo_anonimo_predefinido($this->datos_temp['unidad_gestion']);
        act_usuarios::update_grupo_definicion($grupo_anonimo['grupo'], "Grupo invitado - ".$this->datos_temp['nombre']);
    }
    
}
?>