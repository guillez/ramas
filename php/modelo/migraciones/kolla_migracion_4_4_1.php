<?php

class kolla_migracion_4_4_1 extends kolla_migracion
{
    function negocio__33899()
    {
        //Previo a todo el proceso se difieren las constraints
        $sql = "SET CONSTRAINTS ALL DEFERRED;";
        $this->get_db()->ejecutar($sql);
        
        //Renombro las tablas actuales para posteriores correcciones que se deban hacer
        $sql = "DROP TABLE IF EXISTS arau_instituciones_backup CASCADE;
                DROP TABLE IF EXISTS arau_responsables_academicas_backup CASCADE;
                DROP TABLE IF EXISTS arau_titulos_backup CASCADE;
                ALTER TABLE arau_instituciones           RENAME TO arau_instituciones_backup;
                ALTER TABLE arau_responsables_academicas RENAME TO arau_responsables_academicas_backup;
                ALTER TABLE arau_titulos                 RENAME TO arau_titulos_backup;
                ";
        $this->get_db()->ejecutar($sql);
        
        //Creo las tablas de nuevo y les inserto los datos actuales
        $dir = $this->get_dir_datos_actualizaciones().'/4_4_1';
        
        $archivos = array(
            $dir.'/arau_titulos_datos_act.sql',
            $dir.'/arau_instituciones_datos_act.sql',
            $dir.'/arau_responsables_academicas_datos_act.sql',
        );
        
        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);
        }
        
        //Se revierten las constraints diferidas
        $sql = "SET CONSTRAINTS ALL IMMEDIATE;";
        $this->get_db()->ejecutar($sql);
    }
    
    

}