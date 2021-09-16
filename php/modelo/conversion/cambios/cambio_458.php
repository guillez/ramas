<?php

require_once('cambio.php');

class cambio_458 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 458: Modificación en tabla para las Habilitaciones';
	}

	function cambiar()
	{
        $sql = "
            ALTER TABLE	sge_habilitacion
            ADD COLUMN	imprimir_respuestas_completas character(1) DEFAULT 1;
        ";
        
        $this->ejecutar($sql);
	}
}