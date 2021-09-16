<?php

require_once('cambio.php');

class cambio_378 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 378: Identificación de elementos externos por unidad de gestión.';
	}
    
	function cambiar()
	{
        $sql = "ALTER TABLE sge_elemento ADD COLUMN unidad_gestion Varchar;
                
                CREATE INDEX ifk_sge_elemento_sge_unidad_gestion ON  sge_elemento (unidad_gestion); 
                
                ALTER TABLE sge_elemento 
                    ADD CONSTRAINT fk_sge_elemento_sge_unidad_gestion 
                    FOREIGN KEY (unidad_gestion) REFERENCES sge_unidad_gestion (unidad_gestion);
                
                ";
        
        $this->ejecutar($sql);
        
        $archivo = $this->path_proyecto . '/sql/ddl/80_Procesos/90_sp_upsert_elemento.sql';
        $this->ejecutar_archivo($archivo);
	}

}
?>