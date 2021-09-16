<?php

class act_encuestas
{
	function insertar_encuesta_definicion($encuesta, $bloque, $pregunta, $orden, $obligatoria)
	{
		$sql = "INSERT INTO	sge_encuesta_definicion (encuesta, bloque, pregunta, orden, obligatoria)
    			VALUES 		($encuesta, {$bloque}, {$pregunta}, {$orden}, {$obligatoria})
				";
		
		kolla_db::ejecutar($sql);
	}
	
	function insertar_bloque($nombre, $descripcion, $orden)
	{
		$sql = "INSERT INTO	sge_bloque (nombre, descripcion, orden)
				VALUES       ({$nombre}, {$descripcion}, {$orden})
				RETURNING 	*
				";
		
        kolla_db::ejecutar($sql);
	}
	
    function mover_encuesta($encuesta, $unidad_gestion)
	{
        $encuesta = kolla_db::quote($encuesta);
        $unidad_gestion = kolla_db::quote($unidad_gestion);
        
		$sql = "SELECT  *
				FROM    mover_encuesta_a_unidad_gestion({$encuesta}, {$unidad_gestion})
				";
		
        return kolla_db::ejecutar($sql);
	}
    
    function copiar_encuesta($encuesta, $unidad_gestion)
	{
        $encuesta = kolla_db::quote($encuesta);
        $unidad_gestion = kolla_db::quote($unidad_gestion);
        
		$sql = "SELECT  *
				FROM    copiar_encuesta_a_unidad_gestion({$encuesta}, {$unidad_gestion})
				";
		
        return kolla_db::ejecutar($sql);
	}
    
    function importar_encuesta($unidad_gestion)
	{
        $unidad_gestion = kolla_db::quote($unidad_gestion);
        
		$sql = "SELECT  *
				FROM    importar_encuesta_a_unidad_gestion({$unidad_gestion})
				";
		
        return kolla_db::ejecutar($sql);
	}
    
    function actualizar_implementada_encuesta($encuesta, $implementada)
    {
		$encuesta = kolla_db::quote($encuesta);
        $implementada = kolla_db::quote($implementada);
        
        $sql = "UPDATE  sge_encuesta_atributo
                SET     implementada = $implementada
			    WHERE   encuesta = $encuesta";
        
		kolla_db::ejecutar($sql);
	}
    
    function insertar_pregunta($nombre, $componente_numero, $unidad_gestion, $descripcion_resumida, $oculta = 'N', $tabla_asociada = null, $tabla_asociada_codigo = null, $tabla_asociada_descripcion = null, $tabla_asociada_orden_campo = null, $tabla_asociada_orden_tipo = null)
	{
        $nombre               = kolla_db::quote($nombre);
        $componente_numero    = kolla_db::quote($componente_numero);
        $unidad_gestion       = kolla_db::quote($unidad_gestion);
        $descripcion_resumida = kolla_db::quote($descripcion_resumida);
        $oculta               = kolla_db::quote($oculta);
        
        if (is_null($tabla_asociada)) {
            $sql = "INSERT INTO	sge_pregunta (nombre, componente_numero, unidad_gestion, descripcion_resumida, oculta)
                    VALUES 		({$nombre}, {$componente_numero}, {$unidad_gestion}, {$descripcion_resumida}, {$oculta})
                    ";
        } else {
            $tabla_asociada             = kolla_db::quote($tabla_asociada);
            $tabla_asociada_codigo      = kolla_db::quote($tabla_asociada_codigo);
            $tabla_asociada_descripcion = kolla_db::quote($tabla_asociada_descripcion);
            $tabla_asociada_orden_campo = kolla_db::quote($tabla_asociada_orden_campo);
            $tabla_asociada_orden_tipo  = kolla_db::quote($tabla_asociada_orden_tipo);
            
            $sql = "INSERT INTO	sge_pregunta (nombre, componente_numero, unidad_gestion, descripcion_resumida, oculta, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo)
                    VALUES 		({$nombre}, {$componente_numero}, {$unidad_gestion}, {$descripcion_resumida}, {$oculta}, {$tabla_asociada}, {$tabla_asociada_codigo}, {$tabla_asociada_descripcion}, {$tabla_asociada_orden_campo}, {$tabla_asociada_orden_tipo})
                    ";
        }
		
        kolla_db::ejecutar($sql);
        return toba::db()->recuperar_secuencia('sge_pregunta_seq');
	}
    
    function actualizar_pregunta($pregunta, $nombre)
	{
        $nombre = kolla_db::quote($nombre);
        $pregunta = kolla_db::quote($pregunta);
        
		$sql = "UPDATE  sge_pregunta
                SET     nombre = $nombre
			    WHERE   pregunta = $pregunta";
        
		kolla_db::ejecutar($sql);
	}
    
    function eliminar_pregunta($pregunta)
	{
        $pregunta = kolla_db::quote($pregunta);
        
		$sql = "DELETE FROM sge_pregunta
                WHERE       pregunta = $pregunta";
        
		kolla_db::ejecutar($sql);
	}
    
    function eliminar_preguntas_ocultas($encuesta, $bloque)
    {
        $encuesta = kolla_db::quote($encuesta);
        $bloque   = kolla_db::quote($bloque);
        
		$sql = "DELETE FROM sge_encuesta_definicion
                WHERE       encuesta = $encuesta
                AND         bloque = $bloque
                AND         pregunta IN (
                                            SELECT  sge_pregunta.pregunta
                                            FROM    sge_pregunta
                                            WHERE   sge_pregunta.oculta = 'S'
                                        )
                ";
        
		kolla_db::ejecutar($sql);
    }
    
}