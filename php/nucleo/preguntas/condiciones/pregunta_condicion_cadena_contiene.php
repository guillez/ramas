<?php

class pregunta_condicion_cadena_contiene extends pregunta_condicion
{
    
    function get_js($campo, $valor)
    {
        return "$campo.{$this->operador}(/^.*$valor.*$/i)";
    }
    
}

