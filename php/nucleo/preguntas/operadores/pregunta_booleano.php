<?php

class pregunta_booleano extends pregunta_base
{

    function ini()
    {
        $this->agregar_condicion('es_igual_a', new pregunta_condicion_booleano('es igual a', '=='));
    }
}
