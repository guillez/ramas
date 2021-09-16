<?php

require_once('cambio.php');

class cambio_383 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 383: Nuevo reporte - Resultados de habilitación. Por respuesta   .';
	}
    
	function cambiar()
	{
        $sql = "INSERT INTO kolla.sge_reporte_tipo(reporte_tipo, nombre, descripcion)
                VALUES (5, 
                    'Resultados de habilitación por respuesta (conteo)', 
                    'Contabilización de respuestas para una habilitación');
                ";
        
        $this->ejecutar($sql);
        
        $archivo = $this->path_proyecto . '/sql/ddl/80_Procesos/120_respuestas_completas_habilitacion_conteo.sql';
        $this->ejecutar_archivo($archivo);
	}

}