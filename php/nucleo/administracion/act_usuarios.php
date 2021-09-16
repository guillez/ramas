<?php

class act_usuarios
{
    public static function eliminar_encuestado_de_grupo($encuestado, $grupo)
    {
        $encuestado = kolla_db::quote($encuestado);
        $grupo      = kolla_db::quote($grupo);
        
        $sql = "DELETE FROM sge_grupo_detalle
                WHERE       encuestado = $encuestado
				AND 		grupo      = $grupo";
        
        kolla_db::ejecutar($sql);
    }
    
    public static function insert_grupo_definicion($nombre, $estado, $descripcion, $unidad_gestion)
    {
        $nombre = kolla_db::quote($nombre);
        $estado = kolla_db::quote($estado);
        $descripcion = kolla_db::quote($descripcion);
        $unidad_gestion = kolla_db::quote($unidad_gestion);
        
        $grupo= "INSERT INTO sge_grupo_definicion (nombre, estado, descripcion, unidad_gestion)
                 VALUES      ($nombre, $estado, $descripcion, $unidad_gestion)
                 RETURNING   grupo";
        
        return kolla_db::consultar($grupo);
    }
    
    public static function insert_grupo_detalle($grupo, $encuestado)
    {
        $sql = "INSERT INTO sge_grupo_detalle (grupo, encuestado)
                VALUES ($grupo, $encuestado)";
        
        kolla_db::ejecutar($sql);
    }
    
    public static function update_grupo_definicion($grupo, $nombre)
    {
        $grupo = kolla_db::quote($grupo);
        $nombre = kolla_db::quote($nombre);
        
        $sql = "UPDATE  sge_grupo_definicion
                SET     nombre = $nombre
                WHERE   grupo = $grupo;
                ";
        
        kolla_db::ejecutar($sql);
    }
    
}