<?php

class pregunta_condicion
{
    protected $etiqueta;
    protected $operador;
            
    function __construct($etiqueta, $operador=null)
	{
		$this->etiqueta = $etiqueta;
		$this->operador = $operador;
	}
    
    function get_etiqueta()
    {
        return $this->etiqueta;
    }
    
    function get_operador()
    {
        return $this->operador;
    }
    
    function get_js($campo, $valor)
    {
        return "$campo != '' && $campo {$this->operador} $valor";
    }

    function get_js_extra($extra=null)
    {
        $js_extra = ($extra != null) ? $extra." \n " : '';
        $js_extra .= " var valor = this.value;\n ";
        return $js_extra;
    }
    
}
