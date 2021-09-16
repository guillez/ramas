<?php

require_once('cambio.php');

class cambio_436 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 436: Modificacion en tabla para Reportes Exportados';
	}

	function cambiar()
	{
        $sql = "
            DELETE FROM sge_reporte_exportado;
            
            ALTER TABLE sge_reporte_exportado
                ADD COLUMN usuario character varying NOT NULL;
        ";
        
        $this->ejecutar($sql);
	}
}