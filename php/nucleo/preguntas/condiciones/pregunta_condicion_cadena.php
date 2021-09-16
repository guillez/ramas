<?php

class pregunta_condicion_cadena extends pregunta_condicion
{
    
    function get_js($campo, $valor)
    {
        return "$campo {$this->operador} '$valor'";
    }
    
}
