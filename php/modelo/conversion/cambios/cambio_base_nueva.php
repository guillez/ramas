<?php

require_once('cambio.php');

class cambio_base_nueva extends cambio
{
	function get_descripcion()
	{
		return 'Base Nueva: creacion de la base de negocio';
	}

	function cambiar()
	{
        $sql = 'DROP SCHEMA IF EXISTS kolla CASCADE';
        $this->ejecutar($sql);

        $sql = 'CREATE SCHEMA kolla';
        $this->ejecutar($sql);
        $sql = 'SET search_path TO kolla';
        $this->ejecutar($sql);
        $archivo = $this->get_path_proyecto() . '/sql/estructura.sql';
        $this->ejecutar_archivo($archivo);
	}

}