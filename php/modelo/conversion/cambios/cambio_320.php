<?php

require_once('cambio.php');

/**
 * Carga datos básicos
 */
class cambio_320 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 320: Carga de datos basicos para la migración';
	}

	function cambiar()
	{
		$dir = $this->path_proyecto . '/sql/datos/';
		
		$sqls = array_merge(
			$this->get_sqls_de_directorio($dir.'base'),
			$this->get_sqls_de_directorio($dir.'juegos_de_datos/mug'),
			$this->get_sqls_de_directorio($dir.'juegos_de_datos/relevamiento_ingenieria')
		);
		foreach ($sqls as $archivo) {
			$this->ejecutar_archivo($archivo);
		}
	}

}

?>