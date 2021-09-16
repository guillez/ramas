<?php

use ext_bootstrap\componentes\bootstrap_ci;

class ci_abm_conexiones_sistemas extends bootstrap_ci
{
	protected $s__form_se;
    
    function conf()
    {
        if (!$this->_se()->esta_cargada()) {
            $this->pantalla()->eliminar_evento('eliminar');    
        }
    }
    
    /**
     * @return toba_datos_tabla
     */
    private function _se()
    {
        return $this->dep('sge_sistema_externo');
    }
	
    private function _resetear() 
    {
        $this->_se()->resetear();
        unset($this->s__form_se);
        $this->set_pantalla('seleccion');
    }
	
    //---- cuadro_conexiones ------------------------------------------------------------

    function conf__cuadro_conexiones(toba_ei_cuadro $cuadro)
    {
        $datos = kolla::co('consultas_encuestas_externas')->get_sistemas_externos();
        $cuadro->set_datos($datos);
    }

    function evt__cuadro_conexiones__seleccion($seleccion)
    {
        $this->_se()->cargar($seleccion);
        $this->set_pantalla('edicion');
    }

    //---- form_conexiones --------------------------------------------------------------

    function conf__form_se(toba_ei_formulario $form)
    {
        if (isset($this->s__form_se)) {
            $form->set_datos($this->s__form_se);
            unset($this->s__form_se);
        } elseif ($this->_se()->esta_cargada()) {
            $form->set_datos($this->_se()->get());
            $form->set_solo_lectura(array('nombre'));
        }
    }
	
    function evt__form_se__modificacion($datos)
    {
        $this->s__form_se = $datos;
    }

    //---- Eventos ----------------------------------------------------------------------

    function evt__agregar()
    {
        $this->set_pantalla('edicion');
    }

    function evt__cancelar()
    {
        $this->_resetear();
    }
	
    function evt__eliminar()
    {
        try {
            toba::db()->abrir_transaccion();
            $this->_se()->persistidor()->desactivar_transaccion();
            $this->_desvincular_usuario();
            $this->_se()->eliminar_todo();
            toba::db()->cerrar_transaccion();
        } catch (toba_error_db $ex) {
            toba::db()->abortar_transaccion();
            toba::notificacion()->agregar('Ocurrió un error al intentar eliminar los datos del sistema externo.');
            throw $ex;
        }
        toba::notificacion()->info('El sistema externo se eliminó correctamente.');
        $this->_resetear();
    }
    
    function evt__guardar()
    {   
        $filtro = $this->s__form_se;
        unset($filtro['usuario']);
        $res = toba::consulta_php('consultas_encuestas_externas')->get_sistemas_externos($filtro);      

        if (!$this->_se()->esta_cargada() && !empty($res)) { 
            
            //si ya existe un sistema con ese nombre rechazar e informar
            toba::notificacion()->error("No es posible crear el sistema externo. Ya existe uno con estos datos.");
        } else {    
            try {
                toba::db()->abrir_transaccion();
                $this->_se()->persistidor()->desactivar_transaccion();
                $this->_vincular_usuario();
                $this->_se()->set($this->s__form_se);
                $this->_se()->sincronizar();
                toba::db()->cerrar_transaccion();
            } catch (toba_error_db $ex) {
                toba::db()->abortar_transaccion();
                toba::notificacion()->agregar('Ocurrió un error al intentar guardar los datos del sistema externo.');
                throw $ex;
            }
            toba::notificacion()->info('El sistema externo se actualizó correctamente.');
            $this->_resetear();
        }
    }
    
    private function _desvincular_usuario()
    {
        $se = $this->_se()->get();
        act_toba::eliminar_usuario($se['usuario'], array('externo'));
    }
    
    private function _vincular_usuario()
    {
        $conexion = str_replace(' ', '_', strtolower($this->s__form_se['nombre']));
        $usuario = 'ue_'.$conexion;
        
        if (kolla::co('co_toba')->existe_usuario($usuario) ) {
            throw new toba_error('El Usuario Asociado ya existe, por favor modifique el nombre del Sistema Externo.');
        }
        
        // Usuario en Toba
        act_toba::agregar_usuario($usuario, $this->s__form_se['nombre'], 'externo');
        
        // Encuestado en Kolla
        $encuestado = array (
                                'usuario'   => $usuario,
                                'guest'     => 'S',
                                'externo'   => 'S',
                                'clave'     => 'nada'
                            );
        
        $sql = sql_array_a_insert('sge_encuestado', $encuestado);
        $sql = substr($sql, 0, -1);
        $sql .= ' RETURNING encuestado';
        $e = kolla_db::consultar_fila($sql);

        // Grupo en Kolla
        $grupo = array (
                            'descripcion'		=> "Grupo de encuestados creado para las encuestas de la conexion $conexion",
                            'estado'			=> 'A',
                            'externo'			=> 'S',
                            'nombre'			=> $this->s__form_se['nombre'],
                            'unidad_gestion'	=> '0'
                        );
        
        $sql = sql_array_a_insert('sge_grupo_definicion', $grupo);
        $sql = substr($sql, 0, -1);
        $sql .= ' RETURNING grupo';
        $g = kolla_db::consultar_fila($sql);

        // Asociación del encuestado al grupo
        $grupo_detalle = array (
                                    'grupo'      => $g['grupo'],
                                    'encuestado' => $e['encuestado']
                                );
        
        $sql = sql_array_a_insert('sge_grupo_detalle', $grupo_detalle);
        kolla_db::ejecutar($sql);
        $this->s__form_se['usuario'] = $usuario;
    }
}

?>
