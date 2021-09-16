<?php
use ext_bootstrap\componentes\bootstrap_ci;

class ci_edi_habilitar_formulario extends bootstrap_ci
{
    protected $datos_temp;
    protected $datos_temp_ml;
    protected $s__datos_form_habilitado;
    protected $s__formulario;
    protected $s__publica;
    protected $s__anonimo_predefinido;
    
    function ini__operacion()
	{
        $this->s__anonimo_predefinido = toba::consulta_php('consultas_formularios')->get_grupo_anonimo_predefinido($this->controlador()->get_ug());
	}

    public function resetear()
    {
        unset($this->s__datos_form_habilitado);
        unset($this->s__formulario);
    }

    //-----------------------------------------------------------------------------------
    //---- Configuración ----------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf()
    {
        $hab = $this->cn()->get_datos_habilitacion();

        if (!empty($hab) && ($this->esta_vigente($hab['fecha_desde']) || $hab['externa'] == 'S')) {
            $this->controlador->pantalla()->eliminar_evento('eliminar');
        }
    }

    //---- definicion -------------------------------------------------------------------

    function conf__habilitacion(toba_ei_formulario $form)
    {
        if (isset($this->datos_temp)) {
            $form->set_datos($this->datos_temp);
            $form->controlador()->dep('configuracion')->set_datos($this->datos_temp);
            unset($this->datos_temp);
        } else {
            $hab = $this->cn()->get_datos_habilitacion();

            if (!empty($hab) && $this->esta_vigente($hab['fecha_desde']) && !$this->cn()->es_habilitacion_nueva()) {
                $form->set_solo_lectura(array('fecha_desde', 'anonima'));
                //Bug al dejar solo lectura, esperamos que Ricardo Ruben lo corrija 'texto_preliminar'
                $form->set_solo_lectura(array('publica'));
            }

            if (empty($hab) || $this->cn()->es_habilitacion_nueva()) {
                if ($form->existe_ef('texto_preliminar')) {
                    $form->desactivar_efs(array('texto_preliminar'));
                }
                
                if ($this->cn()->get_datos_form_habilitado()) {
                    $form->desactivar_efs(array('formulario'));
                }
            } else {
                if ($this->cn()->get_datos_form_habilitado() || !$this->cn()->es_habilitacion_nueva()) {
                    $form->desactivar_efs(array('formulario'));
                }
            }
            $hab['unidad_gestion'] = $this->controlador()->get_ug();
            $form->set_datos($hab);
            $form->controlador()->dep('configuracion')->set_datos($hab);
        }
    }

    function evt__habilitacion__modificacion($datos)
    {
        if ($this->cn()->es_nueva()) {
            if (!isset($this->s__formulario)) {
                $this->s__formulario = $datos['formulario'];
            }
            // Datos del formulario que se recuperan por única vez
            $this->s__datos_form_habilitado = toba::consulta_php('consultas_formularios')->get_datos_formulario($this->s__formulario);
            $datos['texto_preliminar'] = $this->s__datos_form_habilitado['texto_preliminar'];
        } else {
            // Se recrea el formulario utilizado originalmente desde sge_formulario_habilitado
            $this->s__datos_form_habilitado = toba::consulta_php('consultas_formularios')->get_datos_formulario_plantilla($datos['habilitacion']);
        }
        
        $this->s__publica = $datos['publica'];
        
        try {
            $this->cn()->set_datos_habilitacion($datos);
            
            if ($datos['publica'] == 'S') {
            
                /*
                 * Si se chequea una habilitación como pública se deben eliminar todos
                 * los formularios habilitados asociados y agregar el anónimo predefinido
                 */
                
                if (!$this->existe_grupo_publico()) {
                    $this->agregar_grupo_publico($datos['formulario']);
                }
                
                $datos['anonima'] = 'N';
            } else {

                /*
                 * Si se marca una habilitación como NO pública se debe eliminar el
                 * formulario habilitado correspondiente al grupo anónimo predefinido
                 */
                
                $this->eliminar_grupo_publico();
            }
        } catch (toba_error $e) {
            $this->datos_temp = $datos;
            throw $e;
        }
    }
    
    function existe_grupo_publico()
    {
        $listado_fh = $this->cn()->get_datos_form_habilitado();
        
        foreach ($listado_fh as $formulario) {
            if (isset($formulario['grupo']) && isset($this->s__anonimo_predefinido['grupo']) && $formulario['grupo'] == $this->s__anonimo_predefinido['grupo']) {
                return true;
            }
        }
        
        return false;
    }
    
    function agregar_grupo_publico($formulario)
    {
        $this->cn()->eliminar_grupos_habilitados();
        $this->cn()->eliminar_formularios_habilitados_detalles();
        $this->cn()->eliminar_formularios_habilitados();

       if (!$this->validar_concepto_evaluacion()) {

            $concepto = array();
            $concepto['concepto_nombre'] = '';
            $concepto['grupo_nombre'] = $this->s__anonimo_predefinido['nombre'];
            $concepto['grupo'] = $this->s__anonimo_predefinido['grupo'];
            $concepto['concepto'] = null;
            $concepto['formulario'] = $formulario;
            $concepto['nombre'] = $this->s__datos_form_habilitado['nombre'];

            $this->agregar_formulario($concepto);
        }
    }
    
    function eliminar_grupo_publico()
    {
        $this->cn()->eliminar_grupo_publico($this->s__anonimo_predefinido);
    }

    function get_habilitacion()
    {
        return $this->cn()->get_datos_habilitacion();
    }

    //---- cuadro -----------------------------------------------------------------------

    function conf__cuadro(toba_ei_cuadro $cuadro)
    {
        //Obtener los datos de los formularios habilitados
        if (isset($this->datos_temp_ml)) {
            $listado_fh = $this->datos_temp_ml;
        } else {
            $listado_fh = $this->cn()->get_datos_form_habilitado();
        }
        
        $hab = $this->cn()->get_datos_habilitacion();
        
        //Configuración del evento eliminar
        if ($this->cn()->dep('datos')->esta_cargada()) {
            if ($this->esta_vigente($hab['fecha_desde'])) {
                $cuadro->eliminar_evento('eliminar');
            }
        }
        
        //Si la habilitación es pública se debe generar el enlace de acceso a cada formulario habilitado
        if ($this->s__publica == 'S') {
            foreach ($listado_fh as $clave => $fh) {
                $listado_fh[$clave]['url_acceso'] = (isset($fh['habilitacion']) && isset($fh['formulario_habilitado']))
                    ? $this->armar_acceso($fh['habilitacion'], $fh['formulario_habilitado'])
                    : '--Se generará al guardar--';
            }
        } else {
            $cuadro->eliminar_columnas(array('url_acceso'));
        }

        $cuadro->set_datos($listado_fh);

        if ($this->cn()->es_habilitacion_nueva()) {
            $cuadro->eliminar_evento('suspender');
            $cuadro->eliminar_evento('habilitar_todos');
            $cuadro->eliminar_evento('suspender_todos');
            $cuadro->eliminar_evento('html');
            $cuadro->eliminar_columnas(array('url_acceso'));
        }
    }

    private function armar_acceso($habilitacion, $formulario_habilitado)
    {
        return toba_http::get_nombre_servidor().toba_parametros::get_redefinicion_parametro('kolla', 'url', false).'/responder?h='.$habilitacion.'&f='.$formulario_habilitado;
    }

    function evt__cuadro__eliminar($seleccion)
    {
        $this->cn()->eliminar_formulario_habilitado($seleccion);
    }

    function evt__cuadro__suspender($seleccion)
    {
        $this->cn()->set_cursor_form_habilitado($seleccion['x_dbr_clave']);
        $this->cn()->toggle_estado_form_habilitado();
        $this->cn()->resetear_cursor_form_habilitado();
    }

    function conf_evt__cuadro__suspender(toba_evento_usuario $evento, $fila)
    {
        $datos = $this->dep('cuadro')->get_datos();

        if ($datos[$fila]['estado'] == 'A') {
            $evento->set_estilo_css('glyphicon-remove-circle');
            $evento->set_msg_ayuda('Suspender formulario');
        } else {
            $evento->set_estilo_css('glyphicon-ok-circle');
            $evento->set_msg_ayuda('Rehabilitar formulario');
        }
    }

    function evt__cuadro__suspender_todos()
    {
        $this->cn()->set_estado_baja_form_habilitados();
    }

    function evt__cuadro__habilitar_todos()
    {
        $this->cn()->set_estado_activo_form_habilitados();
    }

    //---- definicion -------------------------------------------------------------------------

    function conf__definicion(toba_ei_formulario $form)
    {
        $hab = $this->cn()->get_datos_habilitacion();

        if (empty($this->s__datos_form_habilitado) && $this->existe_evento('guardar')) {
            $form->set_solo_lectura();
            $form->evento('alta')->anular();
            $form->controlador()->controlador()->evento('guardar')->anular();
            $form->controlador()->pantalla()->set_descripcion('No se puede editar los formularios de esta habilitación.');
        }

        //Valida la obligatoriedad del concepto de evaluación
        if ($this->validar_concepto_evaluacion()) {
            $form->set_efs_obligatorios(array('concepto'));
        } else {
            //las encuestas no usan elementos, se deshabilita el combo si existe
            if ($form->existe_ef('concepto')) {
                $form->desactivar_efs(array('concepto'));
            }
        }
    }

    function evt__definicion__alta($datos)
    {
        //Valida que no se esté ingresando un par [grupo, concepto] existente
        $this->cn()->validar_formulario_duplicado($datos);
        $this->agregar_formulario($datos);
    }

    function validar_concepto_evaluacion()
    {
        if ($this->cn()->es_nueva()) {
            $datos_form_habilitado_detalle = toba::consulta_php('consultas_formularios')->get_datos_formulario_encuestas($this->s__datos_form_habilitado['formulario'], null);
        } else {
            $hab = $this->cn()->get_datos_habilitacion();
            $datos_form_habilitado_detalle = toba::consulta_php('consultas_formularios')->get_datos_formulario_plantilla_encuestas($hab['habilitacion'], null);
        }

        foreach ($datos_form_habilitado_detalle as $encuesta) {
            if (!is_null($encuesta['tipo_elemento'])) {
                return true;
            }
        }

        return false;
    }

    function agregar_formulario($concepto)
    {
        if ($this->cn()->es_nueva()) {
            $datos_form_habilitado_detalle = toba::consulta_php('consultas_formularios')->get_datos_formulario_encuestas($this->s__datos_form_habilitado['formulario'], $concepto['concepto']);
        } else {
            $hab = $this->cn()->get_datos_habilitacion();
            $datos_form_habilitado_detalle = toba::consulta_php('consultas_formularios')->get_datos_formulario_plantilla_encuestas($hab['habilitacion'], $concepto['concepto']);
        }

        // Si no tiene detalle emite un mensaje de error
        if (empty($datos_form_habilitado_detalle)) {
            $formulario = $this->s__datos_form_habilitado['nombre'];
            throw new toba_error("El formulario <strong>$formulario</strong> está asociado con un concepto que no tiene elementos.");
        }

        // Seteo en tabla cabecera y posiciono cursor
        $this->s__datos_form_habilitado['concepto'] = $concepto['concepto'];
        $this->s__datos_form_habilitado['estado']   = 'A';
        
        $cursor = $this->cn()->set_datos_form_habilitado($this->s__datos_form_habilitado);
        $this->cn()->set_cursor_form_habilitado($cursor);

        if (isset($concepto['grupo'])) {
            $this->cn()->set_datos_grupo_habilitado($concepto);
        }

        // Seteos en tabla detalle
        $orden = 0;
        foreach ($datos_form_habilitado_detalle as $encuesta) {
            $encuesta['orden'] = $orden;
            $this->cn()->set_datos_form_habilitado_detalle($encuesta);
            $orden++;
        }
    }

    function esta_vigente($fecha_desde)
    {
        return (date('Y-m-d') >= $fecha_desde);
    }

    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf_evt__cuadro__pdf(toba_evento_usuario $evento, $fila)
    {
        $datos = $this->dep('cuadro')->get_datos();

        if (isset($datos[$fila]['formulario_habilitado'])) {
            $evento->vinculo()->agregar_parametro('fh', $datos[$fila]['formulario_habilitado']);
        } else {
            $evento->anular();
        }
    }

    function conf_evt__cuadro__html(toba_evento_usuario $evento, $fila)
    {
        $datos = $this->dep('cuadro')->get_datos();

        if (isset($datos[$fila]['formulario_habilitado']) && ($datos[$fila]['estado'] == 'A')) {
            $hab = $this->cn()->get_datos_habilitacion();
            $evento->vinculo()->agregar_parametro('fh', $datos[$fila]['formulario_habilitado']);
            $evento->vinculo()->agregar_parametro('paginado', $hab['paginado']);
            $evento->vinculo()->agregar_parametro('estilo', $hab['estilo']);
            $evento->vinculo()->agregar_parametro('habilitacion', $hab['habilitacion']);
            $evento->vinculo()->agregar_parametro('texto_preliminar', urlencode($hab['texto_preliminar']));
            $evento->set_imagen('gentleface/black/eye_inv_icon&16.png', 'proyecto');
            $evento->activar();
        } else {
            $evento->set_imagen('gentleface/black/invisible_revert_icon&16.png', 'proyecto');
            $evento->desactivar();
        }
    }
    
    function conf_evt__cuadro__eliminar(toba_evento_usuario $evento, $fila)
	{
        $datos = $this->dep('cuadro')->get_datos();
        
        if ($datos[$fila]['grupo'] == $this->s__anonimo_predefinido['grupo']) {
            $evento->desactivar();
        }
	}

    function get_formulario_plantilla()
    {
        return $this->s__formulario;
    }

    function get_formularios()
    {
        $filtro = array(
            'unidad_gestion'    => array('valor' => $this->controlador()->get_ug()),
            'estado'            => array('valor' => 'A')
        );
        return toba::consulta_php('consultas_formularios')->get_formularios($filtro);
    }

    function get_combo_conceptos()
    {
        $unidad_gestion = $this->controlador()->get_ug();
        $where = ' sge_concepto.unidad_gestion = '.kolla_db::quote($unidad_gestion);
        $hab = $this->cn()->get_datos_habilitacion();

        return toba::consulta_php('consultas_formularios')->get_combo_conceptos_para_formulario($where, $hab['formulario']);
    }


    function get_combo_grupos_encuestados()
    {
        $unidad_gestion = $this->controlador()->get_ug();
        $where = 'unidad_gestion = '.kolla_db::quote($unidad_gestion);

        if ($this->s__publica == 'S') {
            $where .= " AND sge_grupo_definicion.estado = 'O'";
        } else {
            $where .= " AND sge_grupo_definicion.estado != 'O'";
        }

        return toba::consulta_php('consultas_formularios')->get_combo_grupos_encuestados($where);
    }
    //-----------------------------------------------------------------------------------
    //---- Configuraciones --------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__pant_datos(toba_ei_pantalla $pantalla)
    {
        $hab = $this->cn()->get_datos_habilitacion();
        if (isset($hab) && !is_null($hab['habilitacion'])) {
            $this->controlador->pantalla()->eliminar_evento('siguiente');
        } else {
            if ($this->controlador->pantalla()->existe_evento('guardar')) {
                $this->controlador->pantalla()->eliminar_evento('guardar');
            }
        }
    }

    function conf__pant_definicion(toba_ei_pantalla $pantalla)
    {
        $this->controlador->pantalla()->eliminar_evento('siguiente');
    }

    //-----------------------------------------------------------------------------------
    //---- configuracion ----------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function evt__configuracion__modificacion($datos)
    {
        try {
            $this->cn()->set_datos_configuracion_habilitacion($datos);
        } catch (toba_error $e) {
            $this->datos_temp = $datos;
            throw $e;
        }
    }

}
?>