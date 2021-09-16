<?php

require_once('cambio.php');

class cambio_335 extends cambio
{
	function get_descripcion()
	{
		return "Cambio 335: se apunta el sistema al nuevo esquema de base de datos";
	}
    
	function cambiar()
	{       
        // Renombramos el schema para que quede apuntando al nuevo migrado
        $sql = 'ALTER SCHEMA kolla RENAME TO kolla_old';
        $this->ejecutar($sql);
        $sql = 'ALTER SCHEMA kolla_new RENAME TO kolla';
        $this->ejecutar($sql);
        $sql = 'SET search_path TO kolla';
        $this->ejecutar($sql);
	}
}

?>