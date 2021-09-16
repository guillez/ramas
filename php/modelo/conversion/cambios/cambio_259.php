<?php

require_once('cambio.php');

/**
 * Cambio para definir los usuarios/encuestados y grupos.
 */
class cambio_259 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 259: Migración de Usuarios/Encuestados y Grupos desde 3.3.0';
	}

	function cambiar()
	{
		$archivo = $this->path_proyecto . '/sql/cambios/3.4.0/creacion_usuarios/insercion_en_tablas_3_3.sql';
		$this->ejecutar_archivo($archivo);
	}

}
?>