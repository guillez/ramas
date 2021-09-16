<?php

class encuesta {
	
    static function get_definicion($definicion)
    {
        $definicion = toba::db()->quote($definicion);
        $sql = "SELECT  sge_encuesta_definicion.encuesta_definicion,
                        sge_encuesta_definicion.encuesta,
                        sge_encuesta_definicion.bloque,
                        sge_encuesta_definicion.pregunta,
                        sge_encuesta_definicion.obligatoria,
                        sge_encuesta_definicion.orden AS orden_pregunta,
                        sge_bloque.orden AS orden_bloque,
                        sge_bloque.nombre AS nombre_bloque,
                        sge_pregunta.nombre AS nombre_pregunta
                FROM    sge_encuesta_definicion
                            JOIN sge_bloque ON (sge_encuesta_definicion.bloque = sge_bloque.bloque)
                            JOIN sge_pregunta ON (sge_encuesta_definicion.pregunta = sge_pregunta.pregunta)
                WHERE   encuesta_definicion = $definicion";
        
        return kolla_db::consultar_fila($sql);
    }

    static function get_bloques($encuesta, $max_long_nombre = null)
	{
		$encuesta = kolla_db::quote($encuesta);
        
        if (isset($max_long_nombre)) {
            $max_long_nombre = kolla_db::quote($max_long_nombre);
            $select = " CASE WHEN length(sge_bloque.nombre) > $max_long_nombre
                                THEN substr(sge_bloque.nombre, 1, $max_long_nombre) || '...'
                                ELSE sge_bloque.nombre
                        END AS nombre";
        } else {
            $select = 'sge_bloque.nombre';
        }
        
		$sql = "SELECT      $select,
                            sge_bloque.bloque,
                            sge_bloque.descripcion,
                            sge_bloque.orden,
                            sge_bloque.orden AS orden_bloque
                FROM        sge_bloque
                WHERE       sge_bloque.bloque IN (SELECT d.bloque FROM sge_encuesta_definicion AS d WHERE d.encuesta = $encuesta)
                ORDER BY    sge_bloque.orden ASC";
		
		return kolla_db::consultar($sql);
	}
	
	static function get_preguntas_bloque($encuesta, $bloque, $filtro = null, $orden = null, $solo_visibles = false)
	{
        $partes = array();
		$partes[] = 'encuesta = ' . toba::db()->quote($encuesta);
		$partes[] = 'bloque = ' . toba::db()->quote($bloque);
        
        if ( $orden ) {
            $partes[] = 'orden > ' . toba::db()->quote($orden);
        }
        
        if ( isset($filtro['componentes_excluir']) ) {
            $partes[] = 'componente NOT IN (' . implode(',', toba::db()->quote($filtro['componentes_excluir'])) . ')';
        }
        
        $where  = implode(' AND ', $partes);
        $where .= $solo_visibles ? "AND sge_pregunta.oculta = 'N'" : '';
        
		$sql = "SELECT      encuesta_definicion,
                            bloque,
                            sge_encuesta_definicion.pregunta,
                            orden,
                            orden AS orden_pregunta,
                            obligatoria,
                            CASE WHEN length(nombre) >= 70 
                                    THEN substr(nombre, 0, 70) || ' ...  - '
                                    ELSE nombre
                            END AS nombre_pregunta,
                            sge_componente_pregunta.componente
                FROM        sge_encuesta_definicion
                                JOIN sge_pregunta ON (sge_encuesta_definicion.pregunta = sge_pregunta.pregunta)
                                JOIN sge_componente_pregunta ON (sge_pregunta.componente_numero = sge_componente_pregunta.numero)
                WHERE       $where
                ORDER BY    orden ASC";
        
		return kolla_db::consultar($sql);
	}
    
    static function get_respuestas_pregunta($pregunta)
    {
       $p = toba::db()->quote($pregunta);
       
       $sql = "SELECT   sge_pregunta.tabla_asociada
               FROM     sge_pregunta
               WHERE    sge_pregunta.pregunta = $p";
       
       $pregunta_tabla = kolla_db::consultar_fila($sql);
       
       if ($pregunta_tabla['tabla_asociada'] == '' || is_null($pregunta_tabla['tabla_asociada'])) {
           return self::get_respuesta_pregunta_tabulada($pregunta);
       } else {
           return self::get_respuesta_pregunta_tabla_asociada($pregunta);
       }
    }
    
    private static function get_respuesta_pregunta_tabulada($pregunta) 
    {
        $pregunta = toba::db()->quote($pregunta);
        
        $sql = "SELECT   sge_respuesta.respuesta,
                         valor_tabulado
                FROM     sge_respuesta
                             JOIN sge_pregunta_respuesta ON sge_pregunta_respuesta.respuesta = sge_respuesta.respuesta
                WHERE    sge_pregunta_respuesta.pregunta = $pregunta";

        return kolla_db::consultar($sql);        
    }
    
    private static function get_respuesta_pregunta_tabla_asociada($pregunta) 
    {
        $pregunta = toba::db()->quote($pregunta);
        
        $sql = "SELECT 
                    sge_pregunta.tabla_asociada, 
                    sge_pregunta.tabla_asociada_codigo, 
                    sge_pregunta.tabla_asociada_descripcion, 
                    sge_pregunta.tabla_asociada_orden_campo, 
                    sge_pregunta.tabla_asociada_orden_tipo
                FROM sge_pregunta
                WHERE sge_pregunta.pregunta = $pregunta";
        $tabla_asociada = kolla_db::consultar_fila($sql);
        
        $tabla_nombre = $tabla_asociada['tabla_asociada'];
        $tabla_codigo = $tabla_asociada['tabla_asociada_codigo'];
        $tabla_descripcion = $tabla_asociada['tabla_asociada_descripcion'];
        $tabla_orden_campo = ($tabla_asociada['tabla_asociada_orden_campo'] == 'codigo') ? $tabla_asociada['tabla_asociada_codigo'] : $tabla_asociada['tabla_asociada_descripcion'];
        $tabla_orden_tipo = $tabla_asociada['tabla_asociada_orden_tipo'];
       
        $sql = "SELECT   $tabla_codigo AS respuesta, $tabla_descripcion AS  valor_tabulado
                FROM     $tabla_nombre
                ORDER BY $tabla_orden_campo $tabla_orden_tipo";
        
        return kolla_db::consultar($sql);
    }
    
    static function copiar_bloque($bloque, $encuesta)
    {
        try {
            toba::db()->abrir_transaccion();
            
            $orden = bloque::get_max_orden($encuesta);
            
            $sql = "INSERT INTO sge_bloque (nombre, descripcion, orden)
                    (
                        SELECT  CASE WHEN length(nombre) > 247 
                                        THEN 'Copia - ' || substr(nombre, 1, 247)
                                        ELSE 'Copia - ' || nombre
                                END AS nombre,
                                descripcion,
                                $orden
                        FROM    sge_bloque
                        WHERE   bloque = $bloque
                    )
                ";
            
            kolla_db::ejecutar($sql);
            
            $bloque_id = toba::db()->recuperar_secuencia('sge_bloque_seq');
            
            $sql = "INSERT INTO sge_encuesta_definicion (encuesta, bloque, pregunta, orden, obligatoria)
                    (
                        SELECT  $encuesta,
                                $bloque_id,
                                pregunta,
                                orden,
                                obligatoria
                        FROM    sge_encuesta_definicion
                        WHERE   bloque = $bloque
                        AND     encuesta = $encuesta
                    )
                ";
            
            kolla_db::ejecutar($sql);
            
            toba::db()->cerrar_transaccion();
        } catch (toba_error_db $e) {
            toba::db()->abortar_transaccion();
            toba::notificacion()->error('Ocurrió un error al intentar copiar la definición del bloque. Consulte con su administrador.');
        }
    }
	
}

?>