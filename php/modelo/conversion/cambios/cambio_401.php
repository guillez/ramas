<?php

require_once('cambio.php');

class cambio_401 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 401 : Se crean datos de prueba para desarrollo';
	}

	function cambiar()
	{
		$dir = $this->path_proyecto . '/sql/datos/';

		$procesos = array_merge(
			$this->get_sqls_de_directorio($dir.'juegos_de_datos/desarrollo')
		);

		foreach ($procesos as $archivo) {
			$this->ejecutar_archivo($archivo);
		}
	}
} 