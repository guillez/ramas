<?php

require_once('cambio.php');

/**
 * Carga datos básicos
 */
class cambio_321 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 321: Carga de datos iniciales del sistema (Relevamiento de Ingenierías)';
	}

	function cambiar()
	{
		$dir = $this->path_proyecto . '/sql/datos/';
		
		$sqls = array_merge(
			$this->get_sqls_de_directorio($dir.'base'),
			$this->get_sqls_de_directorio($dir.'juegos_de_datos/mug'),
			$this->get_sqls_de_directorio($dir.'juegos_de_datos/relevamiento_ingenieria'),
            $this->get_sqls_de_directorio($dir.'juegos_de_datos/encuestas_graduados')
		);
		foreach ($sqls as $archivo) {
			$this->ejecutar_archivo($archivo);
		}
	}

}

?>