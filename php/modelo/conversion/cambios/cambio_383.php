<?php

require_once('cambio.php');

class cambio_383 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 383: Nuevo reporte - Resultados de habilitaci�n. Por respuesta   .';
	}
    
	function cambiar()
	{
        $sql = "INSERT INTO kolla.sge_reporte_tipo(reporte_tipo, nombre, descripcion)
                VALUES (5, 
                    'Resultados de habilitaci�n por respuesta (conteo)', 
                    'Contabilizaci�n de respuestas para una habilitaci�n');
                ";
        
        $this->ejecutar($sql);
        
        $archivo = $this->path_proyecto . '/sql/ddl/80_Procesos/120_respuestas_completas_habilitacion_conteo.sql';
        $this->ejecutar_archivo($archivo);
	}

}