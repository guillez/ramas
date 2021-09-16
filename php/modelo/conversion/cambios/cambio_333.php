<?php

require_once('cambio.php');

/**
 * Cambio para definir los usuarios/encuestados y grupos.
 */

class cambio_333 extends cambio
{
	function get_descripcion()
	{
		return "Cambio 333: Migración de datos (encuestados, encuestas, tablas auxiliares, etc.) desde 3.0.0 y definición de encuestas de graduados.";
	}

	function cambiar()
	{        
        $dir = $this->path_proyecto . '/sql/cambios/3.4.2/';
        
        $sqls = array (
			$dir.'creacion_usuarios/insercion_en_tablas_3_0.sql',
            $dir.'modelo_forms_y_encuestas/migracion_forms_3_0.sql',
            $dir.'modelo_forms_y_encuestas/actualizacion_ids_3_0.sql',
            $this->path_proyecto.'/sql/datos/juegos_de_datos/encuestas_graduados/10_encuestas_graduados.sql'
		);
        
		foreach ($sqls as $archivo) {
			$this->ejecutar_archivo($archivo);
		}
	}

}
?>