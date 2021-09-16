<?php

class pregunta_cadena extends pregunta_base
{

    function ini()
    {
        $this->agregar_condicion('es_igual_a', new pregunta_condicion_cadena('es igual a', '=='));
        $this->agregar_condicion('es_distinto_de', new pregunta_condicion_cadena('es distinto de', '!='));
        $this->agregar_condicion('contiene', new pregunta_condicion_cadena_contiene('contiene','match'));
    }
    
}
