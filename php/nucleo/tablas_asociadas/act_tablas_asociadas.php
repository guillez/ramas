<?php

class act_tablas_asociadas 
{

    static function get_info_tabla_asociada($tabla)
    {
        $sql = "SELECT  c.relname AS tabla,
                        (SELECT obj_description(c.oid, 'pg_class')) AS descripcion
                FROM    pg_catalog.pg_class c 
                        LEFT JOIN pg_catalog.pg_user u ON u.usesysid = c.relowner 
                        LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace 
                WHERE   n.nspname='kolla'
                        AND n.nspname NOT IN ('pg_catalog', 'pg_toast', 'information_schema')
                        AND relkind = 'r'
                        AND c.relname = '$tabla'
                ORDER BY tabla ASC";
        
        return toba::db()->consultar_fila($sql);
    }

    static function get_tablas_asociadas($where)
    {   
        $sql = "SELECT  *
                FROM    sge_tabla_asociada
                WHERE   $where";
        
        $tablas = toba::db()->consultar($sql);
        
        foreach ($tablas as $id => $tabla)
        {
            $info = self::get_info_tabla_asociada($tabla['tabla_asociada_nombre']);
            if ( $info ) {
                $tablas[$id]['descripcion'] = $info['descripcion'];   
            } else {
                $tablas[$id]['descripcion'] = 'ERROR: no se encotró la tabla';
            }
        }
        
        return $tablas;
    }
    
    static function get_datos_tabla_asociada($tabla)
    {
        $sql = "SELECT      *
				FROM        $tabla
                ORDER BY    codigo ASC";
        
        return toba::db()->consultar($sql);
    }
    
    static function modificacion($tabla, $descripcion, $datos)
    {
        $descripcion = toba::db()->quote($descripcion);
        $sql = "COMMENT ON TABLE $tabla IS $descripcion;";
        toba::db()->ejecutar($sql);
        
        if ( !empty($datos) ) {
            $datos_old = self::get_datos_tabla_asociada($tabla);
            foreach ($datos as $clave => $dato) {
                $operacion = $dato['apex_ei_analisis_fila'];
                unset($dato['x_dbr_clave']);
                unset($dato['apex_ei_analisis_fila']);
                switch ( $operacion ) {
                    case 'A':
                        unset($dato['codigo']);
                        $sql = sql_array_a_insert($tabla, $dato);
                        break;
                    case 'M':
                        $sql = sql_array_a_update($tabla, $dato, array('codigo' => $dato['codigo']));
                        break;
                    case 'B':
                        $sql = "DELETE FROM $tabla WHERE codigo = " . toba::db()->quote($datos_old[$clave]['codigo']);
                        break;
                }
                toba::db()->ejecutar($sql);
            }
        }
    }
    
    static function alta($tabla, $datos, $unidad_gestion)
    {
        $nombre = 'ta_'.$tabla['nombre'];
        if ( !toba::db()->existe_tabla('kolla', $nombre) ) {
            $descripcion = toba::db()->quote($tabla['descripcion']);
            $sql = "CREATE TABLE $nombre
                    (
                       codigo serial NOT NULL,
                       descripcion character varying NOT NULL,
                       PRIMARY KEY (codigo)
                    );
                    COMMENT ON TABLE $nombre IS $descripcion;";

            toba::db()->ejecutar($sql);

            if ( !empty($datos) ) {
                foreach ($datos as $dato) {
                    unset($dato['x_dbr_clave']);
                    unset($dato['apex_ei_analisis_fila']);
                    unset($dato['codigo']);
                    $sql = sql_array_a_insert($nombre, $dato);
                    toba::db()->ejecutar($sql);
                }
            }

            $tabla          = toba::db()->quote($nombre);
            $unidad_gestion = toba::db()->quote($unidad_gestion);

            $sql = "INSERT INTO sge_tabla_asociada (tabla_asociada_nombre, unidad_gestion) VALUES ($tabla, $unidad_gestion)";

            toba::db()->ejecutar($sql);    
        } else {
            throw new toba_error("La tabla con nombre <strong>$nombre</strong> ya existe.");
        }
    }
    
    static function eliminar($tabla_asociada, $tabla_asociada_nombre)
    {
        $sql = "DROP TABLE $tabla_asociada_nombre";
        toba::db()->ejecutar($sql);
        $tabla_asociada = toba::db()->quote($tabla_asociada);
        $sql = "DELETE FROM sge_tabla_asociada WHERE tabla_asociada = $tabla_asociada";
        toba::db()->ejecutar($sql);
    }
    
    static function fue_utilizada_en_definicion($tabla)
    {
        $tabla = toba::db()->quote($tabla);
        $sql = "SELECT EXISTS(
                    SELECT  1
                    FROM    sge_formulario_habilitado_detalle
                            JOIN sge_encuesta_definicion ON (sge_formulario_habilitado_detalle.encuesta = sge_encuesta_definicion.encuesta)
                            JOIN sge_pregunta ON sge_encuesta_definicion.pregunta = sge_pregunta.pregunta
                    WHERE       tabla_asociada <> '' 
                            AND tabla_asociada = $tabla
                ) AS existe";
        
        $res = toba::db()->consultar_fila($sql);
        return $res['existe'];
    }
    
}
