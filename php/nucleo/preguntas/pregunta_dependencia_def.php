<?php

class pregunta_dependencia_def 
{
    private $_pregunta_dependencia;
    private $_definicion;
    private $_componente;

    function __construct($pregunta_dependencia, $componente)
    {
        $this->_pregunta_dependencia = $pregunta_dependencia;
        $this->_componente = $componente;
        $this->_init();
    }
    
    private function _get_pregunta_dependencia()
    {
        return $this->_pregunta_dependencia;
    }
    
    private function _init()
    {
        $this->_definicion = kolla::co('co_preguntas_dependientes')->get_dependencias_definicion($this->_get_pregunta_dependencia());
    }
    
    function get_respuesta()
    {
        return $this->_definicion[0]['valor'];
    }

    function get_respuestas_totales()
    {
        return $this->_definicion;
    }
    
    function get_definicion_js()
    {
        $this->_agrupar_definiciones();
        $pregunta = $this->get_pregunta();
        $js = '';

        foreach ($this->_definicion as $clave => $definicion) {
            $clave = explode('+', $clave);
            list($condicion, $respuesta) = $clave;
            $extra = null;
            
            if (in_array($this->_componente, array('list', 'texto_fecha', 'fecha_calculo_anios'))) {
                $extra = $respuesta;
            }
            
            $js .= "var clave = '#'.concat(this.id);\n";
            $js .= "var error = $(clave).parents('.form-group').hasClass('has-error');\n";
            $js .= "if (error) {return;}\n";
            $js .= $pregunta->get_condicion($condicion)->get_js_extra($extra);
            $js .= "if (".$pregunta->get_condicion($condicion)->get_js('valor', $respuesta).") {\n";
            $js .= $this->_get_accion_js($definicion);
            $js .= "}\n";
        }
        
        return $js;
    }
    
    /*function get_definicion_js_check($value)
    {
        $this->_agrupar_definiciones();
        $pregunta = $this->get_pregunta();
        $js = '';
        
        foreach ($this->_definicion as $clave => $definicion) {
            $clave = explode('+', $clave);
            list($condicion, $respuesta) = $clave;
            
            $js .= "if (".$pregunta->get_condicion($condicion)->get_js('valor', $value).") {\n";
            $js .= $this->_get_accion_js($definicion);
            $js .= "}\n";
        }
        
        return $js;
    }*/

    function get_definicion_js_check($value)
    {
        $pregunta = $this->get_pregunta();
        $js = '';
        $condicion = $value['condicion'];
        $respuesta = $value['valor'];

        $js .= "var str = (this.id).match(/c[0-9]+/);
                (str != null) ? idelto = str[0].substring(1, str[0].length) : idelto = '';\n";
        $js .= "var seleccionados = buscar_elementos_checkbox_seleccionados(id, idelto); \n";
        $js .= $pregunta->get_condicion($condicion)->get_js_extra($respuesta);
        $js .= "if (" . $pregunta->get_condicion($condicion)->get_js("", "") . ") {\n";
        $js .= $this->_get_accion_js_check($value);
        $js .= "}\n";

        return $js;
    }
    
    private function _get_accion_js($dependencias)
    {
        $accion_then = array();
        $accion_else = array();
        foreach ($dependencias as $definicion) {
            $definicion = isset($definicion[0]) ? $definicion[0] : $definicion;
            
            switch ($definicion['accion']) {
                case 'habilitar':
                    $accion_then = array_merge($accion_then, $this->_get_acciones_then_habilitar($definicion));
                    $accion_else = array_merge($accion_else, $this->_get_acciones_else_habilitar($definicion));
                    break;
                case 'deshabilitar':
                    $accion_then = array_merge($accion_then, $this->_get_acciones_else_habilitar($definicion));
                    $accion_else = array_merge($accion_else, $this->_get_acciones_then_habilitar($definicion));
                    break;
                case 'mostrar':
                    $accion_then = array_merge($accion_then, $this->_get_acciones_then_mostrar($definicion));
                    $accion_else = array_merge($accion_else, $this->_get_acciones_else_mostrar($definicion));
                    break;
                case 'ocultar':
                    $accion_then = array_merge($accion_then, $this->_get_acciones_else_mostrar($definicion));
                    $accion_else = array_merge($accion_else, $this->_get_acciones_then_mostrar($definicion));
                    break;
            }
        }
        if ( !empty($accion_then) && !empty($accion_else) ) {
            $js = implode("\n", $accion_then)."\n";
            $js .= "} else {\n";
            $js .= implode("\n", $accion_else)."\n";
            return $js;
        } else {
            return '';
        }
    }

    private function _get_accion_js_check($dependencias)
    {
        $accion_then = array();
        $accion_else = array();
        $definicion = $dependencias;

        switch ($definicion['accion']) {
            case 'habilitar':
                $accion_then = array_merge($accion_then, $this->_get_acciones_then_habilitar($definicion));
                $accion_else = array_merge($accion_else, $this->_get_acciones_else_habilitar($definicion));
                break;
            case 'deshabilitar':
                $accion_then = array_merge($accion_then, $this->_get_acciones_else_habilitar($definicion));
                $accion_else = array_merge($accion_else, $this->_get_acciones_then_habilitar($definicion));
                break;
            case 'mostrar':
                $accion_then = array_merge($accion_then, $this->_get_acciones_then_mostrar($definicion));
                $accion_else = array_merge($accion_else, $this->_get_acciones_else_mostrar($definicion));
                break;
            case 'ocultar':
                $accion_then = array_merge($accion_then, $this->_get_acciones_else_mostrar($definicion));
                $accion_else = array_merge($accion_else, $this->_get_acciones_then_mostrar($definicion));
                break;
        }

        if ( !empty($accion_then) && !empty($accion_else) ) {
            $js = implode("\n", $accion_then)."\n";
            $js .= "} else {\n";
            $js .= implode("\n", $accion_else)."\n";
            return $js;
        } else {
            return '';
        }
    }
    
    private function _get_acciones_then_habilitar($definicion)
    {
        $acciones = array();
        $definicion = isset($definicion[0]) ? $definicion[0] : $definicion;
        
        if ( $definicion['pregunta_accion'] ) {
            if (( $definicion['componente'] == 'localidad' ) || ( $definicion['componente'] == 'localidad_y_cp' )) {
                $acciones[] = "habilitar_elemento_localidad(this, {$definicion['encuesta_definicion_accion']});";
            } elseif ( $definicion['componente'] == 'radio' ) {
                $acciones[] = "habilitar_elemento_radio(this, {$definicion['encuesta_definicion_accion']}, idelto);";
            } elseif ( $definicion['componente'] == 'check' ) {
                $acciones[] = "habilitar_elemento_checkbox(this, {$definicion['encuesta_definicion_accion']}, idelto);";
            } else {   
                $acciones[] = "habilitar_elemento(this, {$definicion['encuesta_definicion_accion']});";
            }
            if ( $definicion['obligatoria'] ) {
                $acciones[] = "hacer_obligatoria(this, {$definicion['encuesta_definicion_accion']});";
            }
        } else {
            //$acciones[] = "habilitar_bloque(this, {$definicion['bloque_accion']});";
        }
        return $acciones;
    }
    
    private function _get_acciones_else_habilitar($definicion)
    {
        $acciones = array();
        $definicion = isset($definicion[0]) ? $definicion[0] : $definicion;
        
        if ( $definicion['pregunta_accion'] ) {
            if (( $definicion['componente'] == 'localidad' ) || ( $definicion['componente'] == 'localidad_y_cp' )){
                $acciones[] = "deshabilitar_elemento_localidad(this, {$definicion['encuesta_definicion_accion']});";
            } elseif ( $definicion['componente'] == 'radio' ) {
                $acciones[] = "deshabilitar_elemento_radio(this, {$definicion['encuesta_definicion_accion']}, idelto);";
            } elseif ( $definicion['componente'] == 'check' ) {
                $acciones[] = "deshabilitar_elemento_checkbox(this, {$definicion['encuesta_definicion_accion']}, idelto);";
            } else {
                $acciones[] = "deshabilitar_elemento(this, {$definicion['encuesta_definicion_accion']});";
            }
            if ( $definicion['obligatoria'] ) {
                $acciones[] = "sacar_obligatoria(this, {$definicion['encuesta_definicion_accion']});";
            }
        } else {
            //$acciones[] = "deshabilitar_bloque(this, {$definicion['bloque_accion']});";
        }
        return $acciones;
    }
    
    private function _get_acciones_else_mostrar($definicion)
    {
        $acciones = array();
        $definicion = isset($definicion[0]) ? $definicion[0] : $definicion;
        
        if ( $definicion['pregunta_accion'] ) {
            if ($definicion['componente'] != 'label' && $definicion['componente'] != 'etiqueta_titulo' && $definicion['componente'] != 'etiqueta_texto_enriquecido') {
                $anexo = ($definicion['componente'] == 'radio')?'_radio':'';
                $acciones[] = "colapsar_elemento$anexo(this, {$definicion['encuesta_definicion_accion']});";
            } else {
                $acciones[] = "colapsar_etiqueta(this, {$definicion['encuesta_definicion_accion']});";
            }
            if ( $definicion['obligatoria'] ) {
                $acciones[] = "sacar_obligatoria(this, {$definicion['encuesta_definicion_accion']});";
            }
        } else {
            //$acciones[] = "colapsar_bloque(this, {$definicion['bloque_accion']});";
            $acciones[] = "slideup_bloque(this, {$definicion['bloque_accion']});";
        }
        return $acciones;
    }
    
    private function _get_acciones_then_mostrar($definicion)
    {
        $acciones = array();
        $definicion = isset($definicion[0]) ? $definicion[0] : $definicion;
        
        if ( $definicion['pregunta_accion'] ) {
            if ($definicion['componente'] != 'label'  && $definicion['componente'] != 'etiqueta_titulo' && $definicion['componente'] != 'etiqueta_texto_enriquecido') {
                $anexo = ($definicion['componente'] == 'radio')?'_radio':'';
                $acciones[] = "descolapsar_elemento$anexo(this, {$definicion['encuesta_definicion_accion']});";
             } else {
                $acciones[] = "descolapsar_etiqueta(this, {$definicion['encuesta_definicion_accion']});";
            }   
            if ( $definicion['obligatoria'] ) {
                $acciones[] = "hacer_obligatoria(this, {$definicion['encuesta_definicion_accion']});";
            }
        } else {
            //$acciones[] = "descolapsar_bloque(this, {$definicion['bloque_accion']});";
            $acciones[] = "slidedown_bloque(this, {$definicion['bloque_accion']});";
        }
        return $acciones;
    }
    
    private function _agrupar_definiciones()
    {
        $defs = array();

        foreach ($this->_definicion as $definicion) {
            $definicion = isset($definicion[0]) ? $definicion[0] : $definicion;
            $clave = $definicion['condicion'].'+'.$definicion['valor'];
            
            if ( isset($defs[$clave]) ) {
                $defs[$clave][] = $definicion;
            } else {
                $defs[$clave] = array($definicion);
            }
        }
        $this->_definicion = $defs;
    }
    
    function get_componente()
    {
        return $this->_componente;
    }
    
    function get_pregunta()
    {
        return kolla::co('co_preguntas_dependientes')->get_pregunta($this->get_componente());
    }
    
}
