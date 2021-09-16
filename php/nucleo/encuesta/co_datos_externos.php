<?php

class co_datos_externos
{
    function get_listado($where = null)
    {
        $where = isset($where) && $where != '' ? " WHERE $where" : '';
        
        $sql = "SELECT  sge_tabla_externa.tabla_externa,
                        sge_tabla_externa.unidad_gestion,
                        sge_tabla_externa.tabla_externa_nombre,
                        sge_unidad_gestion.nombre AS unidad_gestion_nombre
                FROM    sge_tabla_externa
                            LEFT JOIN sge_unidad_gestion ON sge_tabla_externa.unidad_gestion = sge_unidad_gestion.unidad_gestion
                $where
                ";
        
        return kolla_db::consultar($sql);
    }
    
    function get_unidades_gestion_presentes($tabla_externa)
    {
        $tabla_externa = kolla_db::quote($tabla_externa);
        
        $sql = "SELECT  sge_tabla_externa.tabla_externa,
                        sge_tabla_externa.unidad_gestion,
                        sge_tabla_externa.tabla_externa_nombre
                FROM    sge_tabla_externa
                WHERE   sge_tabla_externa.tabla_externa_nombre = $tabla_externa
                ";
        
        return kolla_db::consultar($sql);
    }
    
}
