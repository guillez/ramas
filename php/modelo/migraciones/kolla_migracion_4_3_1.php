<?php

class kolla_migracion_4_3_1 extends kolla_migracion
{
    function negocio__19355()
    {
        $dir = $this->get_dir_ddl();

        $archivo = $dir.'/80_Procesos/115_respuestas_pregunta.sql';

        $this->get_db()->ejecutar_archivo($archivo);
    }
    
    function negocio__19022()
    {
        $sql = "ALTER TABLE sge_pregunta
                ADD COLUMN  visualizacion_horizontal character(1) NOT NULL DEFAULT 'N';
               ";
        
        $this->get_db()->ejecutar($sql);
    }

}