<?php

require_once('cambio.php');

class cambio_381 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 381: Nuevo reporte - Resultados de habilitación. Por encuestado.';
	}
    
	function cambiar()
	{
        $sql = "INSERT INTO kolla.sge_reporte_tipo(reporte_tipo, nombre, descripcion)
                VALUES (6, 
                    'Resultados de habilitación por encuestado', 
                    'Resultados de toda una habilitación, visualizados por encuestado (respuestas obtenidas)');
                ";
        
        $this->ejecutar($sql);
        
        $dir = $this->path_proyecto . '/sql/ddl/80_Procesos/';
        
        $sqls = array (
            $dir.'100_preguntas_habilitacion.sql',
            $dir.'110_respuestas_completas_habilitacion.sql'            
		);

		foreach ($sqls as $archivo) {
			$this->ejecutar_archivo($archivo);
		}
	}

}