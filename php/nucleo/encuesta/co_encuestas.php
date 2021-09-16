<?php

class co_encuestas
{
    protected $array_resultados = "resultados (
            encuesta integer, --1
            encuesta_nombre character varying, --2
            encuesta_descripcion character varying, --3

            texto_preliminar text, --4
            implementada char, --5
            estado char, --6
            unidad_gestion character varying, --7

            bloque integer, --8
            bloque_nombre character varying, --9
            bloque_descripcion character varying, --10
            bloque_orden smallint, --11

            pregunta integer, --12
            pregunta_nombre character varying(4096), --13
            componente_numero integer, --14
            tabla_asociada character varying, --15
            tabla_asociada_codigo character varying, --16
            tabla_asociada_descripcion character varying, --17
            tabla_asociada_orden_campo character varying, --18
            tabla_asociada_orden_tipo char(4), --19
            descripcion_resumida character varying(30), --20
            es_libre text, --21
            es_multiple text, --22

            obligatoria char, --23
            pregunta_orden smallint, --24

            componente character varying, --25

            respuesta integer, --26
            respuesta_valor character varying, --27
            respuesta_orden smallint --28
        )";
    
    
    function get_bloques ($encuesta, $unidad_gestion) {
        $enc = toba::db()->quote($encuesta);
        $ug = toba::db()->quote($unidad_gestion);
        
        $sql = "SELECT DISTINCT
                    encuesta,
                    encuesta_nombre,
                    encuesta_descripcion,
                    
                    texto_preliminar,
                    implementada,
                    estado,
                    unidad_gestion,

                    bloque,
                    bloque_nombre,
                    bloque_descripcion,
                    bloque_orden
                FROM ws_encuesta_definicion( $ug, $enc, NULL, NULL ) 
                $this->array_resultados
                ORDER BY bloque_orden;
                ";
        
        toba::logger()->error($sql);
        return kolla_db::consultar($sql);
    }
    
    function get_bloques_preguntas ($encuesta, $bloque, $unidad_gestion) {
        $enc = toba::db()->quote($encuesta);
        $bl = toba::db()->quote($bloque);
        $ug = toba::db()->quote($unidad_gestion);
        
        $sql = "SELECT DISTINCT
                    encuesta, 
                    encuesta_nombre,
                    encuesta_descripcion,
                    
                    texto_preliminar,
                    implementada,
                    estado, 
                    unidad_gestion,
                    
                    bloque,
                    bloque_nombre,
                    bloque_descripcion,
                    bloque_orden,

                    pregunta,
                    pregunta_nombre,
                    componente_numero,
                    tabla_asociada,
                    tabla_asociada_codigo,
                    tabla_asociada_descripcion,
                    tabla_asociada_orden_campo,
                    tabla_asociada_orden_tipo,
                    unidad_gestion,
                    descripcion_resumida,
                    es_libre,
                    es_multiple,
                    
                    obligatoria, 
                    pregunta_orden,
                    
                    componente
                FROM ws_encuesta_definicion( $ug, $enc, $bl, NULL ) 
                $this->array_resultados
                ORDER BY pregunta_orden;";
        
        toba::logger()->error($sql);
        return kolla_db::consultar($sql);
    }    
    
    function get_bloques_preguntas_respuestas ($encuesta, $bloque, $pregunta, $unidad_gestion) {
        $enc = toba::db()->quote($encuesta);
        $bl = toba::db()->quote($bloque);
        $preg = toba::db()->quote($pregunta);
        $ug = toba::db()->quote($unidad_gestion);
        
        $sql = "SELECT  *
                FROM ws_encuesta_definicion( $ug, $enc, $bl, $preg ) 
                $this->array_resultados
                ORDER BY pregunta_orden, respuesta_orden;";
        
        toba::logger()->error($sql);
        return kolla_db::consultar($sql);
    }        
}