<?php

use ext_bootstrap\componentes\bootstrap_ci;

class ci_preguntas_dependientes_edicion_reglas extends bootstrap_ci
{
    protected $s__pregunta;
    protected $s__componente;
    protected $s__clave_dependencia;
    protected $s__efs_extras_cargados = false;
    
    function resetear()
    {
        unset($this->s__pregunta);
        unset($this->s__componente);
        unset($this->s__clave_dependencia);
        unset($this->s__efs_extras_cargados);
    }
    
    function conf()
    {
        $this->init_componente_pregunta();
        $componente = $this->get_componente();
        
        // Carga de efs dinámicamente según el tipo de pregunta
        if ($componente && !$this->s__efs_extras_cargados) {
            $clave = array('componente' => '45000113', 'proyecto' => 'kolla');
            $metadatos = toba_cargador::instancia()->get_metadatos_extendidos($clave, 'toba_ei_formulario');
            $metadatos['_info_formulario_ef'] = $this->get_efs_personalizados();
            toba_cargador::instancia()->set_metadatos_extendidos($metadatos, $clave);
            $this->s__efs_extras_cargados = true;
        }
        
        if (!$this->datos()->esta_cargada()) {
            $this->controlador()->pantalla()->eliminar_evento('eliminar');
        }
    }
    
    //-- auxiliares
    
    function get_encuesta()
    {
        return $this->controlador()->get_encuesta();
    }
    
    /**
     * @return toba_datos_relacion
     */
    function datos()
    {
        return $this->controlador()->datos();
    }
    
    /**
     * @return toba_datos_tabla
     */
    function pregunta()
    {
        return $this->controlador()->datos()->tabla('sge_pregunta_dependencia');
    }
    
    /**
     * @return toba_datos_tabla
     */
    function dependencias()
    {
        return $this->controlador()->datos()->tabla('sge_pregunta_dependencia_definicion');
    }

    function get_bloques()
    {
        return encuesta::get_bloques($this->get_encuesta());
    }
    
    function get_bloques_accion()
    {
        return kolla::co('co_preguntas_dependientes')->get_bloques_dependientes($this->get_encuesta(), $this->s__pregunta['orden_bloque']);
    }
    
    function get_preguntas($bloque)
    {
        $filtro = array('componentes_excluir' => array('label', 'etiqueta_titulo', 'etiqueta_texto_enriquecido', 'texto_mail', 'texto_numerotelefono'));
        return encuesta::get_preguntas_bloque($this->get_encuesta(), $bloque['bloque'], $filtro, null, true);
    }
    
    function get_preguntas_accion($bloque)
    {
        // Para cuando recupero preguntas dentro de un mismo bloque
        if ($bloque == $this->s__pregunta['bloque']) {
            $orden = $this->s__pregunta['orden_pregunta'];
        } else {
            $orden = null;
        }

        return encuesta::get_preguntas_bloque($this->get_encuesta(), $bloque, null, $orden, true);
    }
    
    function get_respuestas()
    {
        if (empty($this->s__componente['tabla_asociada'])) {
            return encuesta::get_respuestas_pregunta($this->s__pregunta['pregunta']);    
        } else {
            return $this->get_datos_tabla_asociada();
        }
    }
    
    function get_condiciones()
    {
        return kolla::co('co_preguntas_dependientes')->get_condiciones($this->s__componente['componente']);
    }
    
    function get_condiciones_combo()
    {
        $condiciones = $this->condiciones()->get_filas();
        foreach ($condiciones as $clave => $condicion) {
            $respuesta = kolla::co('co_respuestas')->get_respuesta($condicion['valor']);
            $condiciones[$clave]['condicion'] = $condicion['condicion'].' '.$respuesta['valor_tabulado'];
        }
        return $condiciones;
    }
    
    function get_localidad($id) 
	{
		$localidad = toba::consulta_php('consultas_mug')->get_localidades($id);
        // La localidad puede no existir
        if (empty($localidad)) {
            return null;
        } else {
            return $localidad[0]['nombre'];  
        }
	}
    
    function get_acciones()
    {
        return kolla::co('co_preguntas_dependientes')->get_acciones();
    }
    
    //-- pregunta
    
    function conf__pregunta(toba_ei_formulario $form)
    {
        $edicion = $this->datos()->esta_cargada();
        if (isset($this->s__pregunta)) {
            $form->set_datos($this->s__pregunta);
            //$def = $this->s__pregunta['encuesta_definicion'];
        } elseif ($edicion) {
            $pregunta   = $this->pregunta()->get();
            $definicion = encuesta::get_definicion($pregunta['encuesta_definicion']);
            $form->set_datos($definicion);            
        }
        
        if ($edicion) {
            $form->set_solo_lectura();
        }
    }
    
    function evt__pregunta__modificacion($datos)
    {
        $this->s__pregunta = $datos;
        $this->pregunta()->set($datos);
    }
    
    //-- dependencias
    
    function conf__pant_dependencias(toba_ei_pantalla $pantalla)
    {
        if (!isset($this->s__clave_dependencia)) {
            $pantalla->eliminar_dep('dependencia');
        }
    }
    
    function conf__dependencias(toba_ei_cuadro $cuadro)
    {
        $dependencias = $this->dependencias()->get_filas();
        foreach ($dependencias as $clave => $dependencia) {
            $dependencias[$clave]['valor_nombre'] = $this->get_respuesta_nombre($dependencia['valor']);
        }
        
        $pregunta = encuesta::get_definicion($this->s__pregunta['encuesta_definicion']);
        $descripcion = "<b>Bloque:</b> {$pregunta['nombre_bloque']}</br>
                        <b>Pregunta:</b> {$pregunta['nombre_pregunta']}";
        
        $cuadro->set_datos($dependencias);
        $cuadro->set_modo_descripcion(false);
        $cuadro->set_descripcion($descripcion);
        
        if (isset($this->s__clave_dependencia) ) {
            $cuadro->evento('agregar')->desactivar();
            $this->controlador()->pantalla()->evento('guardar')->desactivar();
            
            if ($this->controlador()->pantalla()->existe_evento('eliminar') && $this->controlador()->pantalla()->evento('eliminar')->esta_activado()) {
                $this->controlador()->pantalla()->evento('eliminar')->desactivar();
            }
        }
        
        if ($this->controlador()->es_encuesta_desgranamiento()) {
            $cuadro->evento('agregar')->desactivar();
        }
    }
    
    function evt__dependencias__seleccion($seleccion)
    {
        $this->s__clave_dependencia = $seleccion;
        $this->dependencias()->set_cursor($seleccion);
    }
    
    function evt__dependencias__agregar()
    {
        $this->s__clave_dependencia = false;
    }
    
    //-- dependencia
    
    function conf__dependencia(toba_ei_formulario $form)
    {
        if ($this->dependencias()->hay_cursor()) {
            $datos = $this->dependencias()->get();
            $valor = explode('||', $datos['valor']);
            $datos['valor_desde'] = $valor[0];
            if (count($valor) == 2) {
                $datos['valor_hasta'] = $valor[1];
            }
            $form->set_datos($datos);
        }
        
        if ($this->controlador()->es_encuesta_desgranamiento()) {
            $form->set_solo_lectura();
            $form->evento('baja')->desactivar();
            $form->evento('modificacion')->desactivar();
        }
    }
    
    function evt__dependencia__modificacion($datos)
    {
        $this->dependencias()->set($this->get_datos_dependencia($datos));
        $this->validar_dependencia();
        $this->evt__dependencia__cancelar();
    }
    
    function evt__dependencia__alta($datos)
    {
        $this->validar_dependencia($datos);
        $this->dependencias()->nueva_fila($this->get_datos_dependencia($datos));
        $this->evt__dependencia__cancelar();
    }
    
    function evt__dependencia__cancelar()
    {
        $this->dependencias()->resetear_cursor();
        $this->s__clave_dependencia = null;
    }
    
    function evt__dependencia__baja()
    {
        $this->dependencias()->set(null);
        $this->evt__dependencia__cancelar();
    }
    
    function init_componente_pregunta()
    {
        if (isset($this->s__pregunta) && !isset($this->s__componente)) {
            $this->s__componente = kolla::co('consultas_encuestas')->get_componente_pregunta($this->s__pregunta['pregunta']);   
        }
    }
    
    function get_componente()
    {
        if (isset($this->s__componente)) {
            return $this->s__componente['componente'];   
        } else {
            return null;
        }
    }
    
    function get_respuesta_nombre($respuesta)
    {
        switch ($this->s__componente['componente']) {
            case 'localidad':
            case 'localidad_y_cp':
                $nombre = $this->get_localidad($respuesta);
                break;
            case 'combo':
            case 'radio':
            case 'combo_autocompletado':
                // Para el caso de tablas asociadas
                if (empty($this->s__componente['tabla_asociada'])) {
                    $respuesta = kolla::co('co_respuestas')->get_respuesta($respuesta);
                    $nombre    = $respuesta['valor_tabulado'];
                } else {
                    $respuesta = $this->get_datos_tabla_asociada($respuesta);
                    if (empty($respuesta)) {
                        $nombre = null;
                    } else {
                        $nombre = $respuesta[0]['valor_tabulado'];
                    }
                }
                break;
            case 'list':
            case 'check':
                $nombre = '';
                $respuestas = explode(',', $respuesta);
                
                foreach ($respuestas as $key => $value) {
                    if (empty($this->s__componente['tabla_asociada'])) {
                        $valor = kolla::co('co_respuestas')->get_respuesta($value);
                        $nombre .= $valor['valor_tabulado'].', ';
                    } else {
                        $valor = $this->get_datos_tabla_asociada($value);
                        if (empty($valor)) {
                            $nombre .= null;
                        } else {
                            $nombre .= $valor[0]['valor_tabulado'].', ';
                        }
                    }
                }
                $nombre = trim($nombre, ', ');
                break;
            default :
                $nombre = $respuesta;
                break;
        }
        
        return $nombre;
    }
    
    function get_datos_tabla_asociada($respuesta=null)
    {
        $tabla       = $this->s__componente['tabla_asociada'];
        $codigo      = $this->s__componente['tabla_asociada_codigo'];
        $desc        = $this->s__componente['tabla_asociada_descripcion'];
        $orden_campo = $this->s__componente['tabla_asociada_orden_campo'];
        $orden_tipo  = $this->s__componente['tabla_asociada_orden_tipo'];
        return kolla::co('co_respuestas')->get_datos_tabla_asociada($tabla, $codigo, $desc, $orden_campo, $orden_tipo, $respuesta);
    }
    
    function get_efs_personalizados()
    {
        $efs = $this->get_efs_fijos();
        switch ($this->get_componente()) {
            case 'texto_numeroentero':
            case 'texto_numerodecimal':
            case 'texto_numeroedad':
            case 'texto_numeroanio':
                $efs[] = array(
                    'identificador'             => 'valor_desde',
                    'columnas'                  => 'valor_desde',
                    'obligatorio'               => 1,
                    'oculto_relaja_obligatorio' => 0,
                    'elemento_formulario'       => 'ef_editable_numero',
                    'etiqueta'                  => 'Número 1',
                    'descripcion'               => '',
                    'inicializacion'            => '',
                    'colapsado'                 => 0,
                );
                $efs[] = array(
                    'identificador'             => 'valor_hasta',
                    'columnas'                  => 'valor_hasta',
                    'obligatorio'               => 1,
                    'oculto_relaja_obligatorio' => 1,
                    'elemento_formulario'       => 'ef_editable_numero',
                    'etiqueta'                  => 'Número 2',
                    'descripcion'               => '',
                    'inicializacion'            => '',
                    'colapsado'                 => 0,
                );
                break;
            case 'combo':
            case 'radio':
            case 'combo_autocompletado':
                $efs[] = array(
                    'identificador'             => 'valor_desde',
                    'columnas'                  => 'valor_desde',
                    'obligatorio'               => 1,
                    'oculto_relaja_obligatorio' => 0,
                    'elemento_formulario'       => 'ef_combo',
                    'etiqueta'                  => 'Opción',
                    'descripcion'               => '',
                    'inicializacion'            => '',
                    'colapsado'                 => 0,
                    'carga_metodo'              => 'get_respuestas',
                    'carga_fuente'              => 'kolla',
                    'carga_col_clave'           => 'respuesta',
                    'carga_col_desc'            => 'valor_tabulado',
                    'carga_cascada_relaj'       => 0,
                    'cascada_mantiene_estado'   => 0,
                    'carga_permite_no_seteado'  => 1,
                    'carga_no_seteado'          => '-- Seleccione --',
                    'carga_no_seteado_ocultar'  => 0
                );
                break;
            case 'texto':
                $efs[] = array(
                    'identificador'             => 'valor_desde',
                    'columnas'                  => 'valor_desde',
                    'obligatorio'               => 1,
                    'oculto_relaja_obligatorio' => 0,
                    'elemento_formulario'       => 'ef_editable',
                    'etiqueta'                  => 'Texto',
                    'descripcion'               => '',
                    'inicializacion'            => '',
                    'colapsado'                 => 0,
                    'edit_maximo'               => validador::RENGLON_MAX_LENGTH,
                );
                break;
            case 'textarea':
                $efs[] = array(
                    'identificador'             => 'valor_desde',
                    'columnas'                  => 'valor_desde',
                    'obligatorio'               => 1,
                    'oculto_relaja_obligatorio' => 0,
                    'elemento_formulario'       => 'ef_editable_textarea',
                    'etiqueta'                  => 'Área de Texto',
                    'descripcion'               => '',
                    'inicializacion'            => '',
                    'colapsado'                 => 0,
                    'edit_maximo'               => validador::AREA_MAX_LENGTH,
                );
                break;
            case 'texto_fecha':
            case 'fecha_calculo_anios':
                $efs[] = array(
                    'identificador'             => 'valor_desde',
                    'columnas'                  => 'valor_desde',
                    'obligatorio'               => 1,
                    'oculto_relaja_obligatorio' => 0,
                    'elemento_formulario'       => 'ef_editable_fecha',
                    'etiqueta'                  => 'Fecha 1',
                    'descripcion'               => '',
                    'inicializacion'            => '',
                    'colapsado'                 => 0,
                );
                $efs[] = array(
                    'identificador'             => 'valor_hasta',
                    'columnas'                  => 'valor_hasta',
                    'obligatorio'               => 1,
                    'oculto_relaja_obligatorio' => 1,
                    'elemento_formulario'       => 'ef_editable_fecha',
                    'etiqueta'                  => 'Fecha 2',
                    'descripcion'               => '',
                    'inicializacion'            => '',
                    'colapsado'                 => 0,
                );
                break;
            case 'localidad':
            case 'localidad_y_cp':
                $efs[] = array(
                    'identificador'             => 'valor_desde',
                    'columnas'                  => 'valor_desde',
                    'obligatorio'               => 1,
                    'elemento_formulario'       => 'ef_popup',
                    'etiqueta'                  => 'Localidad',
                    'descripcion'               => '',
                    'inicializacion'            => '',
                    'colapsado'                 => 0,
                    'oculto_relaja_obligatorio' => 0,
                    'popup_item'                => '40000099',
                    'popup_proyecto'            => 'kolla',
                    'popup_editable'            => 0,
                    'popup_ventana'             => 'width: 800px,height: 600px,scrollbars: yes',
                    'popup_carga_desc_metodo'   => 'get_localidad',
                    'popup_puede_borrar_estado' => 1,
                    'popup_carga_desc_include'  => null,
                    'popup_carga_desc_clase'    => null
                );
                break;
            case 'list':
            case 'check':
                $efs[] = array(
                    'identificador'             => 'valor_desde',
                    'columnas'                  => 'valor_desde',
                    'obligatorio'               => 1,
                    'oculto_relaja_obligatorio' => 0,
                    'elemento_formulario'       => 'ef_multi_seleccion_lista',
                    'etiqueta'                  => 'Opciones',
                    'descripcion'               => '',
                    'inicializacion'            => '',
                    'colapsado'                 => 0,
                    'carga_metodo'              => 'get_respuestas',
                    'carga_fuente'              => 'kolla',
                    'carga_col_clave'           => 'respuesta',
                    'carga_col_desc'            => 'valor_tabulado',
                    'carga_cascada_relaj'       => 0,
                    'cascada_mantiene_estado'   => 0,
                    'carga_permite_no_seteado'  => 0,
                    'selec_utilidades'          => 0,
                    'selec_serializar'          => 1
                );
                break;
        }
        return $efs;
    }
    
    function get_efs_fijos()
    {
        return array(
            array(
                'identificador'             => 'condicion',
                'columnas'                  => 'condicion',
                'obligatorio'               => 1,
                'oculto_relaja_obligatorio' => 0,
                'elemento_formulario'       => 'ef_combo',
                'etiqueta'                  => 'Condición',
                'descripcion'               => '',
                'inicializacion'            => '',
                'colapsado'                 => 0,
                'carga_metodo'              => 'get_condiciones',
                'carga_fuente'              => 'kolla',
                'carga_col_clave'           => 'condicion',
                'carga_col_desc'            => 'etiqueta',
                'carga_cascada_relaj'       => 0,
                'cascada_mantiene_estado'   => 0,
                'carga_permite_no_seteado'  => 1,
                'carga_no_seteado'          => '-- Seleccione --',
                'carga_no_seteado_ocultar'  => 0
            ),
            array(
                'identificador'             => 'accion',
                'columnas'                  => 'accion',
                'obligatorio'               => 1,
                'oculto_relaja_obligatorio' => 0,
                'elemento_formulario'       => 'ef_combo',
                'etiqueta'                  => 'Acción',
                'descripcion'               => '',
                'inicializacion'            => '',
                'colapsado'                 => 0,
                'carga_metodo'              => 'get_acciones',
                'carga_consulta_php'        => '45000009',
                'carga_fuente'              => 'kolla',
                'carga_col_clave'           => 'accion',
                'carga_col_desc'            => 'etiqueta',
                'carga_cascada_relaj'       => 0,
                'cascada_mantiene_estado'   => 0,
                'carga_permite_no_seteado'  => 1,
                'carga_no_seteado'          => '-- Seleccione --',
                'carga_no_seteado_ocultar'  => 0,
                'carga_consulta_php_clase'  => 'co_preguntas_dependientes',
                'carga_consulta_php_archivo'=> 'nucleo/preguntas/co_preguntas_dependientes.php'
            ),
            array(
                'identificador'             => 'bloque',
                'columnas'                  => 'bloque',
                'obligatorio'               => 1,
                'oculto_relaja_obligatorio' => 0,
                'elemento_formulario'       => 'ef_combo',
                'etiqueta'                  => 'Bloque',
                'descripcion'               => '',
                'inicializacion'            => '',
                'colapsado'                 => 0,
                'carga_metodo'              => 'get_bloques_accion',
                'carga_fuente'              => 'kolla',
                'carga_col_clave'           => 'bloque',
                'carga_col_desc'            => 'nombre',
                'carga_cascada_relaj'       => 0,
                'cascada_mantiene_estado'   => 0,
                'carga_permite_no_seteado'  => 1,
                'carga_no_seteado'          => '-- Seleccione --',
                'carga_no_seteado_ocultar'  => 0
            ),
            array(
                'identificador'             => 'pregunta',
                'columnas'                  => 'pregunta,encuesta_definicion',
                'obligatorio'               => 0,
                'oculto_relaja_obligatorio' => 0,
                'elemento_formulario'       => 'ef_combo',
                'etiqueta'                  => 'Pregunta',
                'descripcion'               => '',
                'inicializacion'            => '',
                'colapsado'                 => 0,
                'carga_metodo'              => 'get_preguntas_accion',
                'carga_fuente'              => 'kolla',
                'carga_col_clave'           => 'pregunta,encuesta_definicion',
                'carga_col_desc'            => 'nombre_pregunta',
                'carga_maestros'            => 'bloque',
                'carga_cascada_relaj'       => 1,
                'cascada_mantiene_estado'   => 0,
                'carga_permite_no_seteado'  => 1,
                'carga_no_seteado'          => '-- Seleccione --',
                'carga_no_seteado_ocultar'  => 0
            ),
        );
    }
    
    function get_datos_dependencia($datos)
    {
        $datos['valor'] = $datos['valor_desde'];
        unset($datos['valor_desde']);
        if (isset($datos['valor_hasta'])) {
            $datos['valor'] .= '||'.$datos['valor_hasta'];
            unset($datos['valor_hasta']);
        }
        return $datos;
    }
    
    function validar_dependencia($datos=null)
    {
        if (!$datos) {
            $datos = $this->dependencias()->get();
        }
        if (!isset($datos['pregunta']) && $datos['bloque'] == $this->s__pregunta['bloque']) {
            throw new toba_error('Sólo pueden utilizarse bloques distintos del bloque contendor de la pregunta dependiente.');
        }
    }
    
}