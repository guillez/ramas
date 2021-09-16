<?php

require_once('cambio.php');

/*
 * Para migrar a 3.4
 */
class cambio_custom_3_4 extends cambio
{
	function get_descripcion()
	{
		return "Cambio Migración Personalizada -";
	}
    
	function cambiar()
	{
		$mig = $this->path_proyecto . '/sql/cambios/3.4.0/migracion_personalizada.sql';

		if ( file_exists($mig) ) {
			$this->ejecutar_archivo($mig);
		} else {
			throw new RuntimeException("Para poder realizar la migración debe proporcionar un archivo con la migración personalizada para su institución. Si ya dispone del mismo, asegurese de que se encuentre en la ruta correcta.");
		}

		$ddl = $this->path_proyecto . '/sql/ddl/';

		$search_path = "SET SEARCH_PATH = kolla_new";
		$this->ejecutar($search_path);

		foreach ($this->get_sqls_de_directorio($ddl.'50_SetVals') as $archivo) {
			$this->ejecutar_archivo($archivo);
		}
        
        // Renombramos el schema para que quede apuntando al nuevo migrado
        $sql = 'ALTER SCHEMA kolla RENAME TO kolla_old';
        $this->ejecutar($sql);
        $sql = 'ALTER SCHEMA kolla_new RENAME TO kolla';
        $this->ejecutar($sql);
	}
}

?>