<?php

class pregunta_opciones extends pregunta_base
{

    function ini()
    {
        $this->agregar_condicion('es_igual_a', new pregunta_condicion('es igual a', '=='));
        $this->agregar_condicion('es_distinto_de', new pregunta_condicion('es distinto de', '!='));
    }
}
