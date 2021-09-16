<?php

require_once('cambio.php');

class cambio_379 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 379: Identificación de conceptos externos por unidad de gestión.';
	}
    
	function cambiar()
	{
        $sql = "ALTER TABLE sge_concepto ADD COLUMN unidad_gestion Varchar;
                
                CREATE INDEX ifk_sge_concepto_sge_unidad_gestion ON  sge_concepto (unidad_gestion); 
                
                ALTER TABLE sge_concepto 
                    ADD CONSTRAINT fk_sge_concepto_sge_unidad_gestion 
                    FOREIGN KEY (unidad_gestion) REFERENCES sge_unidad_gestion (unidad_gestion);
                
                ";
        $this->ejecutar($sql);

        $archivo = $this->path_proyecto . '/sql/ddl/80_Procesos/80_sp_upsert_concepto.sql';
        $this->ejecutar_archivo($archivo);
	}

}
?>