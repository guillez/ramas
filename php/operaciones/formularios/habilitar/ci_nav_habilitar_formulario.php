<?php
class ci_nav_habilitar_formulario extends ci_navegacion_cn
{
    protected $s__ug;

    //------------------------------------------------------------------------------------
    //---- Seteos iniciales y finales de la operación ------------------------------------
    //------------------------------------------------------------------------------------

    function ini__operacion()
    {
        $this->s__ug = toba::memoria()->get_dato('unidad_gestion');
    }

    function fin()
    {
        $ug = $this->dep('form_unidad_gestion')->ef('unidad_gestion')->get_estado();
        toba::memoria()->set_dato('unidad_gestion', $ug);
    }

    //------------------------------------------------------------------------------------
    //---- PANTALLA SELECCION ------------------------------------------------------------
    //------------------------------------------------------------------------------------

    function conf__seleccion($pantalla)
    {
        $this->dep('filtro')->columna('descripcion')->set_condicion_fija('contiene', true);
    }

    function conf__filtro(toba_ei_filtro $filtro)
    {
        if (isset($this->s__datos)) {
            $filtro->set_datos($this->s__datos);
        } else {
            $filtro ->set_datos(array('archivada' => array('condicion' => 'es_igual_a', 'valor' => 'N')));
        }
    }

    //-- CUADRO ---------------------------------------------------------

    function get_listado()
    {
        if (!isset($this->s__ug)) {
            $ugs = toba::consulta_php('consultas_ug')->get_unidad_gestion_combo();
            $this->s__ug = $ugs[0]['unidad_gestion'];
        }
        
        $filtro_ug = "unidad_gestion = " . kolla_db::quote($this->s__ug);
        $filtro = $this->dep('filtro')->get_sql_where();
        $filtro = $filtro ? "$filtro AND $filtro_ug " : $filtro_ug;

        return toba::consulta_php('consultas_formularios')->get_habilitaciones($filtro);

    }

    function get_etiquetas_cuadro()
    {
        return array('habilitaciones');
    }

    //---- form_unidad_gestion -----------------------------------------------------------
    
    function conf__form_unidad_gestion(toba_ei_formulario $form)
    {
        $form->set_datos(isset($this->s__ug) ? ['unidad_gestion' => $this->s__ug] : []);

        if (toba::consulta_php('consultas_usuarios')->es_gestor_actual()) {
            $form->set_solo_lectura();
        }
    }

    function evt__form_unidad_gestion__modificacion($datos)
    {
        if (isset($datos['unidad_gestion'])) {
            $this->s__ug = $datos['unidad_gestion'];
        }
    }
    
    function evt__form_unidad_gestion__recargar($datos)
    {
        $this->evt__form_unidad_gestion__modificacion($datos);
    }

    //------------------------------------------------------------------------------------
    //---- Eventos -----------------------------------------------------------------------
    //------------------------------------------------------------------------------------

    function evt__guardar()
    {
        $formularios = $this->cn()->get_datos_form_habilitado();
        
        if (empty($formularios)) {
            throw new toba_error('Debe agregar al menos una definición a la habilitación. Utilice la pestaña <strong>Definición</strong> para tal fin.');
        }
        if ($this->cn()->es_nueva()) {
            //Cargar el log de plantilla de formulario utilizada para esta habilitación con los datos actuales
            $formulario = $this->dep('editor')->get_formulario_plantilla();
            $definicion_formulario = toba::consulta_php('consultas_formularios')->get_datos_formulario_definicion($formulario);
            $this->cn()->set_datos_log_formulario($definicion_formulario);
        }
        try {
            $this->actualizar_encuestas_implementada();
            $this->cn()->dep('datos')->sincronizar();
            $this->dep('editor')->resetear();
            $this->cancelar();
            toba::notificacion()->agregar($this->get_mensaje('guardar_ok'), 'info');
        } catch (toba_error_db $e) {
            toba::logger()->error($e->get_sql_ejecutado());
            throw new toba_error($e->getMessage());
        }
    }

    function actualizar_encuestas_implementada()
    {
        if (!$this->cn()->posee_encuestas_implementadas()) {
            $habilitacion  = $this->dep('editor')->get_habilitacion();

            if ($habilitacion['fecha_desde'] <= date('Y-m-d')) {
                $this->cn()->set_cursor_form_habilitado(0);
                $detalle = $this->cn()->get_datos_form_habilitado_detalle();
                $this->cn()->resetear_cursor_form_habilitado();
                $this->modelo_act['encuestas'] = kolla::abm('act_encuestas');

                foreach ($detalle as $key => $value) {
                    $this->modelo_act['encuestas']->actualizar_implementada_encuesta($value['encuesta'], 'S');
                }
            }
        }
    }

    function evt__cancelar()
    {
        $this->dep('editor')->resetear();
        parent::evt__cancelar();
    }

    function evt__cuadro__archivar($seleccion)
    {
        $this->cn()->dep('datos')->cargar($seleccion);
        $this->cn()->toggle_archivada();
        $this->cn()->sacar_destacada();
        $this->cn()->dep('datos')->sincronizar();
        $this->cn()->dep('datos')->resetear();
    }

    function evt__cuadro__destacar($seleccion)
    {
        $this->cn()->dep('datos')->cargar($seleccion);
        $this->cn()->toggle_destacada();
        $this->cn()->dep('datos')->sincronizar();
        $this->cn()->dep('datos')->resetear();
    }

    function conf_evt__cuadro__archivar(toba_evento_usuario $evento, $fila)
    {
        // no sirve, no tengo el dato en la fila
        $datos = $this->dep('cuadro')->get_datos();

        $this->cn()->dep('datos')->cargar(array('habilitacion' => $datos[$fila]['habilitacion']));
        $archivada = $this->cn()->get_archivada();
        $this->cn()->dep('datos')->resetear();

        if ($archivada == 'N') {
            $evento->set_estilo_css('glyphicon-send');
            $evento->set_msg_ayuda('Archivar');
        } else {
            $evento->set_estilo_css('icono-grisado glyphicon-send');
            $evento->set_msg_ayuda('Desarchivar');
        }
    }

    function conf_evt__cuadro__destacar(toba_evento_usuario $evento, $fila)
    {
        // no sirve, no tengo el dato en la fila
        $datos = $this->dep('cuadro')->get_datos();

        $this->cn()->dep('datos')->cargar(array('habilitacion' => $datos[$fila]['habilitacion']));
        $destacada = $this->cn()->get_destacada();
        $this->cn()->dep('datos')->resetear();

        if ($destacada == 'N') {
            $evento->set_estilo_css('icono-grisado-destacado glyphicon-star-empty');
            $evento->set_msg_ayuda('Destacar');
        } else {
            $evento->set_estilo_css('icono-destacado glyphicon-star');
            $evento->set_msg_ayuda('Quitar destacado');
        }
    }

    function evt__siguiente()
    {
        $this->dep('editor')->set_pantalla('pant_definicion');
    }

    //------------------------------------------------------------------------------------
    //---- Auxiliares --------------------------------------------------------------------
    //------------------------------------------------------------------------------------

    function get_ug()
    {
        return $this->s__ug;
    }

    //-----------------------------------------------------------------------------------
    //---- JAVASCRIPT -------------------------------------------------------------------
    //-----------------------------------------------------------------------------------
    
	function extender_objeto_js()
	{
		parent::extender_objeto_js();
		
        if ($this->get_id_pantalla() == 'seleccion') {
            return;
        }

        /*
         * Si la fecha desde es igual a la fecha actual y el campo implementada de todas
         * las encuestas es false, entonces emito alerta de (no) edición de encuestas. De
         * continuar con la creación/edición de la habilitación para la fecha actual, en
         * el momento de guardar se setean todos los campos implementada de las encuestas
         * en true, de esta manera quedan las encuestas en uso y la próxima vez no alerto
         */

        $implementadas = true;
        $habilitacion  = $this->dep('editor')->get_habilitacion();
        $mensaje_fecha_desde = 'A partir de la fecha actual no será posible continuar editando las encuestas involucradas en la habilitación. ¿Desea continuar?';

        if (!$this->dep('editor')->dep('habilitacion')->existe_ef('formulario')) {
            $implementadas = $this->cn()->posee_encuestas_implementadas();
        }

        if ($implementadas) {
            return;
        }

        if ($this->dep('editor')->get_id_pantalla() == 'pant_definicion') {

            if ($habilitacion['fecha_desde'] == date('Y-m-d')) {
                
                echo "

                //---- Eventos ---------------------------------------------

                {$this->objeto_js}.evt__guardar = function()
                {
                    if (confirm('$mensaje_fecha_desde')) {
                        return true;
                    } else {
                        return false;
                    }
                }
                ";
            }
        }

        if ($this->dep('editor')->get_id_pantalla() == 'pant_datos') {

            echo "

            //---- Eventos ---------------------------------------------

            {$this->objeto_js}.evt__guardar = function()
            {
                var today = new Date();
                var dd    = today.getDate();
                var mm    = today.getMonth() + 1;
                var yyyy  = today.getFullYear();
                if (dd < 10) {dd = '0' + dd}
                if (mm < 10) {mm = '0' + mm}

                var today = dd + '/' + mm + '/' + yyyy;
                var es_hoy = this.dep('editor').dep('habilitacion').ef('fecha_desde').get_estado() == today;

                if (es_hoy) {
                    if (confirm('$mensaje_fecha_desde')) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
            ";
        }
	}
}
?>