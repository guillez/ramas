<?php

class pregunta_opciones_lista extends pregunta_base
{

    function ini()
    {
        $this->agregar_condicion('es_igual_a', new pregunta_condicion_lista('es igual a'));
    }
}