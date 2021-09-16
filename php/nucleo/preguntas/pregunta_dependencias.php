<?php

class pregunta_dependencias
{
    private $_encuesta;
    private $_dependencias;
    
    function __construct($encuesta)
    {
        $this->_encuesta = $encuesta;
        $this->_init();
    }
    
    function get_encuesta()
    {
        return $this->_encuesta;
    }
    
    private function _init()
    {
        $this->_dependencias = kolla::co('co_preguntas_dependientes')->get_dependencias($this->get_encuesta());
    }
    
    function get_dependencias_js()
    {
        if ( !empty($this->_dependencias) ) {
            $js = "<script>\n";
            $js .= "$(document).ready(function(){\n";
            
            foreach ($this->_dependencias as $dependencia) {

                // Cabecera de la función
                $componente = $dependencia['componente'];
                $definicion = new pregunta_dependencia_def($dependencia['pregunta_dependencia'], $dependencia['componente']);

                if ($componente == 'check') {
                    /*$respuestas = explode(',', $definicion->get_respuesta());

                    foreach ($respuestas as $key => $value) {

                        // Cuerpo de la función
                        $id  = $dependencia['encuesta_definicion'].'.'.$value;
                        $js .= "get_preguntas('{$id}').on('change', function() {\n";
                        $js .= $definicion->get_definicion_js_check($value);

                        // Fin de la función
                        $js .= "}).change();\n";

                    } */
                    $dependencias_check = $definicion->get_respuestas_totales();

                    foreach ($dependencias_check as $key => $reglas) {
                        $respuestas = explode(',', $reglas['valor']);

                        foreach ($respuestas as $key => $value) {
                            // Cuerpo de la función
                            $id  = $dependencia['encuesta_definicion'].'.'.$value;
                            $js .= "get_preguntas('{$id}').on('change', function() {\n";
                            $js .= "var id = '{$dependencia['encuesta_definicion']}'; \n";
                            $js .= $definicion->get_definicion_js_check($reglas);
                            // Fin de la función
                            $js .= "}).change();\n";
                        }
                    }
                } else {
                    if ($componente == 'radio') {
                        $metodo = 'get_preguntas_radio';
                    } elseif ($componente == 'localidad') {
                        $metodo = 'get_preguntas_localidad';
                    } else {
                        $metodo = 'get_preguntas';
                    }

                    // Cuerpo de la función
                    $id  = $dependencia['encuesta_definicion'];
                    $js .= "$metodo({$id}).on('change', function() {\n";
                    $js .= "var str = (this.id).match(/c[0-9]+/);
                            (str != null) ? idelto = str[0].substring(1, str[0].length) : idelto = '';
                            ";
                    if ($componente == 'radio') {
                        $js .= " var idradio = $id;\n";
                    }
                    $js .= $definicion->get_definicion_js();

                    // Fin de la función
                    $js .= "}).change();\n";
                }
            }
            $js .= "});\n";
            $js .= "</script>\n";
            return $js;
        } else {
            return '';
        }
    }
        
    
}
