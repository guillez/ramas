<?php

class pregunta_fecha extends pregunta_base
{

    function ini()
    {
        $this->agregar_condicion('es_igual_a', new pregunta_condicion_fecha('es igual a', '=='));
        $this->agregar_condicion('es_distinto_de', new pregunta_condicion_fecha('es distinto de', '!='));
        $this->agregar_condicion('desde', new pregunta_condicion_fecha('desde', '>='));
        $this->agregar_condicion('hasta', new pregunta_condicion_fecha('hasta', '<='));
        $this->agregar_condicion('entre', new pregunta_condicion_fecha_entre('entre', '>=', '<='));
    }
    
}
