<?php

class kolla_migracion_4_2_1 extends kolla_migracion
{
    function negocio__17903()
    {
        $dir = $this->get_dir_ddl();
        
        $archivo = $dir.'/80_Procesos/190_copiar_encuesta_a_unidad_gestion.sql';

        $this->get_db()->ejecutar_archivo($archivo);
    }

}