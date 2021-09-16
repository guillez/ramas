<?php

class pregunta_numero extends pregunta_base
{

    function ini()
    {
        $this->agregar_condicion('es_igual_a', new pregunta_condicion('es igual a', '=='));
        $this->agregar_condicion('es_distinto_de', new pregunta_condicion('es distinto de', '!='));
        $this->agregar_condicion('es_mayor_que', new pregunta_condicion('es mayor que', '>'));
        $this->agregar_condicion('es_mayor_igual_que', new pregunta_condicion('es mayor o igual que', '>='));
        $this->agregar_condicion('es_menor_que', new pregunta_condicion('es menor que', '<'));
        $this->agregar_condicion('es_menor_igual_que', new pregunta_condicion('es menor o igual que', '<='));
        $this->agregar_condicion('entre', new pregunta_condicion_numero_entre('entre', '>=', '<='));
    }
}
