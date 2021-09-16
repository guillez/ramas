<?php

class pregunta_condicion_fecha extends pregunta_condicion
{
    
    function get_js($campo, $valor)
    {
        return "fecha_valida && (fecha.getTime() {$this->operador} fecha_valor.getTime())";
    }
    
    function get_js_extra($valor)
    {
        $js = parent::get_js_extra();
        $fecha = DateTime::createFromFormat('Y-m-d', $valor);
        $js .= "var pattern = /(\d{2})\/(\d{2})\/(\d{4})/;\n";
        $js .= "var fecha = new Date(valor.replace(pattern,'$3-$2-$1'));\n";
        $js .= "var fecha_valor = new Date('{$fecha->format('Y')}-{$fecha->format('m')}-{$fecha->format('d')}');\n";
        $js .= "var fecha_valida = !isNaN(fecha.valueOf());\n";
        return $js;
    }
    
}
