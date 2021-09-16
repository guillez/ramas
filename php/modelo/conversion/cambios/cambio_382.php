<?php

require_once('cambio.php');

class cambio_382 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 382: Nuevo reporte - Resultados de habilitaci�n. Por pregunta.';
	}
    
	function cambiar()
	{
        $sql = "INSERT INTO kolla.sge_reporte_tipo(reporte_tipo, nombre, descripcion)
                VALUES (4, 
                    'Resultados de habilitaci�n por pregunta', 
                    'Resultados de toda una habilitaci�n, visualizados por pregunta');
                ";
        
        $this->ejecutar($sql);
	}

}