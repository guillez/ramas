<?php

require_once('cambio.php');

class cambio_341 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 341: Migración de tablas locales.';
	}
    
	function cambiar()
	{
		$archivo = $this->path_proyecto . '/sql/cambios/3.4.2/datos_locales/migrar_tablas_locales.sql';
		$this->ejecutar_archivo($archivo);
	}

}
?>