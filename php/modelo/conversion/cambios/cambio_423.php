<?php

require_once('cambio.php');

class cambio_423 extends cambio
{
    function get_descripcion()
    {
        return 'Cambio 423: Modificar el nombre de la unidad de gesti�n 0';
    }
    
    function cambiar()
    {
        $sql = "UPDATE kolla.sge_unidad_gestion
                SET nombre='Unidad de Gesti�n Predeterminada'
                WHERE nombre='Unidad de Gesti�n Default' AND unidad_gestion = '0';
            ";
        
        $this->ejecutar($sql);
    }

} 