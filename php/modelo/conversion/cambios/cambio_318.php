<?php

require_once('cambio.php');

/**
 * Post Instalación
 */
class cambio_318 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 318: secuencias, fk, restricciones, vistas.';
	}

	function cambiar()
	{
		$ddl = $this->path_proyecto . '/sql/ddl/';
		$estructura = array_merge(
            $this->get_sqls_de_directorio($ddl.'50_SetVals'),
            $this->get_sqls_de_directorio($ddl.'60_FK'),
            $this->get_sqls_de_directorio($ddl.'70_Permisos'),
            $this->get_sqls_de_directorio($ddl.'80_Procesos'),
            $this->get_sqls_de_directorio($ddl.'90_Vistas'),
            $this->get_sqls_de_directorio($ddl.'100_Otros')
        );
		
		foreach ($estructura as $archivo) {
			$this->ejecutar_archivo($archivo);
		}
	}

}