<?php

class kolla_migracion_3_7_2 extends kolla_migracion
{
    function negocio__604()
    {
        $dir = $this->get_dir_ddl();
        
        $archivos = array(
            $dir.'80_Procesos/140_respuestas_completas_formulario_habilitado_conteo.sql',
        );
        
        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);    
        }
    }
}