<?php

require_once('cambio.php');

class cambio_template extends cambio
{
	function get_descripcion()
	{
		return "Este es un archivo de cambio de ejemplo.";
	}

	function cambiar()
	{
		//parent::ejecutar($sql, get_class() . " - ejecutar()");
	}

}

?>