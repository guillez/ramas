<?php

class kolla_migracion_3_7_4 extends kolla_migracion
{
    function negocio__680()
    { 
        //el código a ejecutar coincide con el que necesita el ticket #714
    }
    
    function negocio__714()
    { //el código a ejecutar coincide con el que necesita el ticket #680
        $dir = $this->get_dir_ddl();
        
        $archivos = array(
            $dir.'80_Procesos/150_ws_resultados_de_encuesta_detalle.sql',
        );
        
        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);    
        }
    }
}
