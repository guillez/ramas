<?php

class pregunta_condicion_radio extends pregunta_condicion
{

    function get_js($campo, $valor)
    {
        $cond = "{$this->operador}$('#c'+idelto+'_pk_'+idradio+'$valor').prop('checked')";
        return $cond;
    }

}
