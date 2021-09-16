<?php

class kolla_migracion_4_3_0 extends kolla_migracion
{
    function negocio__18889()
    {
        $sql = "ALTER TABLE sge_encuestado 
                ADD COLUMN imagen_perfil_nombre character varying(300);
                ";
        $this->get_db()->ejecutar($sql);

        $sql = "ALTER TABLE sge_encuestado
                ADD COLUMN imagen_perfil_bytes BYTEA;
               ";
        $this->get_db()->ejecutar($sql);
    }

    function negocio__19006()
    {
        $sql = "UPDATE sge_pregunta
                SET componente_numero = 14
                WHERE sge_pregunta.pregunta = 686;";

        $this->get_db()->ejecutar($sql);
    }

}