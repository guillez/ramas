<?php

require_once('cambio.php');

class cambio_319 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 319: juego de datos para base nueva';
	}

	function cambiar()
	{
		$sqls = array_merge(
			$this->get_sqls_de_directorio($this->path_proyecto . '/sql/datos/base'),
			$this->get_sqls_de_directorio($this->path_proyecto . '/sql/datos/juegos_de_datos/mug'),
            $this->get_sqls_de_directorio($this->path_proyecto . '/sql/datos/juegos_de_datos/encuestas_graduados'),
            $this->get_sqls_de_directorio($this->path_proyecto . '/sql/datos/juegos_de_datos/relevamiento_ingenieria'),
            $this->get_sqls_de_directorio($this->path_proyecto . '/sql/ddl/50_SetVals'),
            $this->get_sqls_de_directorio($this->path_proyecto . '/sql/ddl/80_Procesos'),
            $this->get_sqls_de_directorio($this->path_proyecto . '/sql/ddl/90_Vistas'),
            $this->get_sqls_de_directorio($this->path_proyecto . '/sql/ddl/100_Otros')
		);
		
		foreach ($sqls as $archivo) {
			$this->ejecutar_archivo($archivo);
		}
	}

}