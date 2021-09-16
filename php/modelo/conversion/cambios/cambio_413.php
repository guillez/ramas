<?php

require_once('cambio.php');

class cambio_413 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 413 : Se quita obligatoriedad del campo sge_encuestado.clave';
	}

	function cambiar()
	{
        $sql = 'ALTER TABLE sge_encuestado ALTER COLUMN clave DROP NOT NULL';
		$this->ejecutar($sql);
        $sql = 'UPDATE sge_encuestado SET clave = NULL';
        $this->ejecutar($sql);
	}
} 