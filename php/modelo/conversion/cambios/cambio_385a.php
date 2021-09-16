<?php

require_once('cambio.php');

class cambio_385a extends cambio
{
    function get_descripcion()
    {
        return 'Cambio 385a: Unidad de Gestión Predeterminada en la tabla sge_unidad_gestion';
    }
    
    function cambiar()
    {
        $sql = "INSERT INTO sge_unidad_gestion (unidad_gestion, nombre)
                VALUES (0, 'Unidad de Gestión Predeterminada')";
        
        $this->ejecutar($sql);
    }

} 