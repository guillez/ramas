<?php

class pregunta_condicion_fecha_entre extends pregunta_condicion
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
        return "!isNaN(fecha.valueOf()) && (fecha {$this->operador_desde} desde && fecha {$this->operador_hasta} hasta)";
    }
    
    function get_js_extra($valor)
    {
        $js = parent::get_js_extra();

        $fechas = explode('||', $valor);
        $desde = explode('-', $fechas[0]);
        $hasta = explode('-', $fechas[1]);
        
        $js  .= "var pattern = /(\d{2})\/(\d{2})\/(\d{4})/;\n";
        $js .= "var fecha = new Date(valor.replace(pattern,'$3-$2-$1'));\n";
        $js .= "var desde = new Date('{$desde[0]}-{$desde[1]}-{$desde[2]}');\n";
        $js .= "var hasta = new Date('{$hasta[0]}-{$hasta[1]}-{$hasta[2]}');\n";
        return $js;
    }
    
}
