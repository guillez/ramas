<?php

require_once('cambio.php');

/**
 * Pre-Instalación. Crea estructuras.
 */
class cambio_317 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 317: Creación de estructura de la base de datos.';
	}

	function cambiar()
	{

		$ddl = $this->path_proyecto . '/sql/ddl/';
		
		$estructura_base = array_merge(
				$this->get_sqls_de_directorio($ddl.'10_Secuencias'),
				$this->get_sqls_de_directorio($ddl.'20_Tablas'),
				$this->get_sqls_de_directorio($ddl.'30_Checks'),
				$this->get_sqls_de_directorio($ddl.'40_Indices')
            );
		
		$sql = "
			DROP SCHEMA IF EXISTS kolla_new CASCADE;
			CREATE SCHEMA kolla_new;
			SET search_path = kolla_new;
		";
		
		$this->ejecutar($sql);
		
		foreach ($estructura_base as $archivo) {
			$this->ejecutar_archivo($archivo);
		}
	}
}

?>