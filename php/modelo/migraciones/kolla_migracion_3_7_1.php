<?php

class kolla_migracion_3_7_1 extends kolla_migracion
{
	function negocio__587()
	{
		$dir = $this->get_dir_ddl();
		
		$archivos = array(
				$dir.'80_Procesos/150_ws_resultados_de_encuesta_detalle.sql',
		);
		
		foreach ($archivos as $archivo) {
			$this->get_db()->ejecutar_archivo($archivo);
		}
	}

	/**
	 * Se crea la función para no generar confusión, si bien se corre el mismo archivo
     * son problemas distintos y corregidos en tickets distintos pero relacionados
	 */
	function negocio__601()
	{
		$this->negocio__587(); 
	}

}

