<?php

class kolla_migracion_3_6_2 extends kolla_migracion
{
    function negocio__526()
    {
        $dir = $this->get_dir_ddl();
        
        $archivos = array(
            $dir.'80_Procesos/30_sp_guarda_respuesta_libre.sql',
            $dir.'80_Procesos/70_sp_guarda_respuesta_tabulada.sql',
        );
        
        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);    
        }
    }
    
}