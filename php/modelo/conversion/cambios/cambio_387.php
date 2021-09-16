<?php

require_once('cambio.php');

class cambio_387 extends cambio
{
    function get_descripcion()
    {
        return 'Cambio 387 : Administración de tipos de elementos por dependencia (unidad de gestión)';
    }
    
    function cambiar()
    {
        $sql = "ALTER TABLE sge_tipo_elemento ADD COLUMN unidad_gestion Varchar;
                
                CREATE INDEX ifk_sge_tipo_elemento_sge_unidad_gestion ON  sge_tipo_elemento (unidad_gestion); 
                
                ALTER TABLE sge_tipo_elemento
                    ADD CONSTRAINT fk_sge_tipo_elemento_sge_unidad_gestion 
                    FOREIGN KEY (unidad_gestion) REFERENCES sge_unidad_gestion (unidad_gestion);
                ";
        
        $this->ejecutar($sql);

        $sql = "UPDATE sge_tipo_elemento SET unidad_gestion=0 WHERE unidad_gestion IS NULL";
        
        $this->ejecutar($sql);
    }

} 
