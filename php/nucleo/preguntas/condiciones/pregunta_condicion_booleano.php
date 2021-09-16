<?php

class pregunta_condicion_booleano extends pregunta_condicion
{
    
    /*function get_js($campo, $valor)
    {
        return "$('input[value = $valor]').is(':checked')";
    }
    
    function get_js_extra($valores = null)
    {
        return '';
    }*/

    function get_js($campo, $valor)
    {
        return 'intersect(seleccionados, posibles).length != 0';
    }

    function get_js_extra($valores = null)
    {
        $valores = explode(',', $valores);
        $js = array();

        foreach ($valores as $id => $valor) {
            $valor = addslashes($valor);
            $js[] = "'$valor'";
        }

        $js = "[" . implode(',', $js). "]";

        return "var posibles = $js;\n";
    }
    
}
