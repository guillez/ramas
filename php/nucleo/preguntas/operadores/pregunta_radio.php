<?php

class pregunta_radio extends pregunta_base
{

    function ini()
    {
        //Por cómo se define la condición para los radios los operadores igual y distinto se traducen a "vacío" y "negación" respectivamente
        $this->agregar_condicion('es_igual_a', new pregunta_condicion_radio('es igual a', ''));
        $this->agregar_condicion('es_distinto_de', new pregunta_condicion_radio('es distinto de', '!'));
    }
}