<?php

require_once('cambio.php');

class cambio_datos_prueba extends cambio
{
	function get_descripcion()
	{
		return "Este es un archivo de cambio de ejemplo.";
	}

	function cambiar()
	{
        $sqls = $this->get_sqls_de_directorio($this->path_proyecto . '/sql/datos/juegos_de_datos/desarrollo');

        foreach ($sqls as $archivo) {
            $this->ejecutar_archivo($archivo);
        }

	}

}

?>