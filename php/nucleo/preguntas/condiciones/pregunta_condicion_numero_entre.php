<?php

class pregunta_condicion_numero_entre extends pregunta_condicion
{
    protected $operador_desde;
    protected $operador_hasta;
    
    function __construct($etiqueta, $operador_desde, $operador_hasta)
    {
        $this->etiqueta = $etiqueta;
        $this->operador_desde = $operador_desde;
        $this->operador_hasta = $operador_hasta;
    }
            
    function get_js($campo, $valor)
    {
        return "$campo {$this->operador_desde} desde && $campo {$this->operador_hasta} hasta";
    }
    
    function get_js_extra($valor)
    {
        $js = parent::get_js_extra();
        $numeros = explode('||', $valor);
        $desde   = $numeros[0];
        $hasta   = $numeros[1];
        
        $js .= "var desde = $desde;\n";
        $js .= "var hasta = $hasta;\n";
        return $js;
    }
    
}
