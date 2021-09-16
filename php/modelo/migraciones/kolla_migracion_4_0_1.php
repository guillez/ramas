<?php

class kolla_migracion_4_0_1 extends kolla_migracion
{    
    function negocio__744()
    {
        $dir = $this->get_dir_datos_actualizaciones();
        
        $archivos = array(
            $dir.'/arau_titulos_datos_act.sql',
            $dir.'/arau_instituciones_datos_act.sql',
            $dir.'/arau_responsables_academicas_datos_act.sql',
        );
        
        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);
        }
    }
    
    function negocio__762()
    {
        $dir = $this->get_dir_ddl();
        
        $archivos = array(
            $dir.'80_Procesos/150_ws_resultados_de_encuesta_detalle.sql',
        );
        
        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);    
        }
    }   
    
}