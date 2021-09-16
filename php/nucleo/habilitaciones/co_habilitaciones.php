<?php

class co_habilitaciones 
{
    protected $resultados_respuestas_completas_formulario_habilitado = "resultados (
                                                                            habilitacion integer,               --1
                                                                            formulario_habilitado integer,      --2
                                                                            formulario_nombre text,             --3
                                                                            respondido_formulario integer,      --4
                                                                            ingreso integer,                    --5
                                                                            fecha_inicio date,                  --6
                                                                            terminado_formulario character(1),  --7
                                                                            fecha_terminado date,               --8
                                                                            respondido_encuesta integer,        --9
                                                                            respondido_detalle integer,         --10
                                                                            moderada character(1),              --11
                                                                            respuesta_codigo integer,           --12
                                                                            respuesta_valor character varying,  --13
                                                                            encuesta_definicion integer,        --14
                                                                            encuesta integer,                   --15
                                                                            orden_encuesta integer,             --16
                                                                            orden_bloque smallint,              --17
                                                                            bloque integer,                     --18
                                                                            bloque_nombre character varying(255), --19
                                                                            orden_pregunta smallint,            --20
                                                                            pregunta integer,                   --21
                                                                            pregunta_nombre character varying(4096), --22
                                                                            componente character varying(35),   --23
                                                                            tabla_asociada character varying(100),   --24
                                                                            concepto integer,                   --25
                                                                            encuesta_nombre character varying,  --26
                                                                            elemento integer,                   --27
                                                                            elemento_nombre text,               --28
                                                                            respondido_encuestado integer,      --29
                                                                            encuestado integer,                 --30
                                                                            usuario character varying(60),      --31
                                                                            respondido_por character varying(60),   --32
                                                                            ignorado char,                      --33
                                                                            concepto_nombre text                --34                   
                                                                        , concepto_externo character varying(100) --35
                                                                        , elemento_externo character varying(100) --36
                                                                        , pregunta_tabla_codigo character varying(50) --37
                                                                        , pregunta_tabla_descripcion character varying(50) --38
                                                                        , numero integer                        --39
                                                                        , respondido_por_encuestado integer     --40
                                                                        , codigo_columna character varying      --41
                                                                    );";

    function get_listado_rest($where = '', $order='', $limit = '', $filtro = array())
    {
        $sql = "SELECT  sge_habilitacion.*
                FROM    sge_habilitacion
                WHERE   $where
                        $order
                        $limit";
        
        return kolla_db::consultar($sql);
    }
    
    function get_listado_formularios_rest($where = '', $order='', $limit = '', $filtro = array())
    {
    
        // Podemos enviar menos campos
        $sql = "SELECT  sge_formulario_habilitado.*,
                        sge_formulario_habilitado_detalle.*,
                        sge_elemento.*,
                        sge_concepto.concepto_externo
                FROM    sge_formulario_habilitado
                        JOIN sge_habilitacion ON (sge_formulario_habilitado.habilitacion = sge_habilitacion.habilitacion)
                        JOIN sge_formulario_habilitado_detalle ON (sge_formulario_habilitado.formulario_habilitado = sge_formulario_habilitado_detalle.formulario_habilitado)
                        LEFT JOIN sge_elemento ON (sge_formulario_habilitado_detalle.elemento = sge_elemento.elemento)
                        LEFT JOIN sge_concepto ON (sge_formulario_habilitado.concepto = sge_concepto.concepto)
                WHERE   $where
                        $order
                        $limit";

        return kolla_db::consultar($sql);
    }
    
    function get_formulario_externo($habilitacion, $formulario_externo, $sistema)
    {
        $habilitacion       = toba::db()->quote($habilitacion);
        $formulario_externo = toba::db()->quote($formulario_externo);
        $sistema            = toba::db()->quote($sistema);
        
        $sql = "SELECT  sge_formulario_habilitado.*
                FROM    sge_formulario_habilitado
                        JOIN sge_habilitacion ON (sge_habilitacion.habilitacion = sge_formulario_habilitado.habilitacion)
                WHERE       sge_formulario_habilitado.habilitacion = $habilitacion
                        AND sge_habilitacion.sistema = $sistema
                        AND formulario_habilitado_externo = $formulario_externo";
        
        return kolla_db::consultar_fila($sql);
    }
    
    function get_formulario_respuestas($habilitacion, $formulario, $codigo_externo, $sistema, $codigo_recuperacion=null)
    {
        $habilitacion   = toba::db()->quote($habilitacion);
        $formulario     = toba::db()->quote($formulario);
        $codigo_externo = toba::db()->quote($codigo_externo);
        $sistema        = toba::db()->quote($sistema);

        $campos = "formulario_habilitado, fecha_inicio, fecha_terminado, respuesta_codigo, respuesta_valor, encuesta_definicion, encuesta, 
                orden_encuesta, orden_bloque, bloque_nombre, orden_pregunta, pregunta, 
                pregunta_nombre, componente, tabla_asociada, encuesta_nombre, elemento_nombre, 
                concepto_nombre, concepto_externo, elemento_externo, numero, codigo_columna";

        $sql = "SELECT $campos FROM respuestas_formulario_externo($habilitacion, $formulario, $codigo_externo, $sistema) 
                $this->resultados_respuestas_completas_formulario_habilitado";

        return kolla_db::consultar($sql);
    }
    
    function get_encuesta_formulario_habilitado($habilitacion, $formulario_habilitado_externo, $encuesta, $elemento_externo, $unidad_gestion) {
        
        $hab = toba::db()->quote($habilitacion);
        $form_hab = toba::db()->quote($formulario_habilitado_externo);
        $enc = toba::db()->quote($encuesta);
        $elem = toba::db()->quote($elemento_externo);
        $ug = toba::db()->quote($unidad_gestion);
        
        if ( is_null($elemento_externo) || ($elemento_externo == 'null') || ($elemento_externo == '') ) {
            $condicion_elemento = "se.elemento_externo IS NULL";
        } else {
            $condicion_elemento = "se.elemento_externo = $elem ";
        }
        
        $sql = "SELECT 
                    sea.encuesta, 
                    sea.nombre, 
                    sea.descripcion, 
                    se.elemento_externo, 
                    sfhd.orden
                FROM  sge_habilitacion sh 
                    inner join sge_formulario_habilitado sfh on (sfh.habilitacion = sh.habilitacion)
                    left join sge_concepto sc on (sc.concepto = sfh.concepto and sc.unidad_gestion = sh.unidad_gestion)
                    inner join sge_formulario_habilitado_detalle sfhd on (sfhd.formulario_habilitado = sfh.formulario_habilitado)
                    left join sge_elemento se on (se.elemento = sfhd.elemento and se.unidad_gestion = sh.unidad_gestion)
                    inner join sge_encuesta_atributo sea on (sea.encuesta = sfhd.encuesta)
                WHERE $condicion_elemento 
                        and sfh.formulario_habilitado_externo = $form_hab 
                        and sh.habilitacion = $hab
                        and sea.encuesta = $enc
                        and sh.unidad_gestion = $ug ;";

        toba::logger()->error($sql);
        return kolla_db::consultar_fila($sql);
    }

    function get_formularios_habilitados($habilitacion, $ug) {
        $ug = toba::db()->quote($ug);
        $habilitacion = toba::db()->quote($habilitacion);

        $sql = "SELECT sge_formulario_habilitado.*,
	                    sge_concepto.descripcion as concepto_desc,
	                    sge_concepto.concepto_externo
                FROM sge_habilitacion 
	                  JOIN sge_formulario_habilitado 
		                ON (sge_habilitacion.habilitacion = sge_formulario_habilitado.habilitacion)
	                  LEFT JOIN sge_concepto
		                ON (sge_concepto.concepto = sge_formulario_habilitado.concepto)
                WHERE (sge_habilitacion.unidad_gestion = {$ug}) 
	                  AND (sge_habilitacion.habilitacion = {$habilitacion})
	            ORDER BY sge_formulario_habilitado.formulario_habilitado;";

        return kolla_db::consultar($sql);
    }

    function get_formularios_habilitados_detalle($habilitacion) {
        $habilitacion = toba::db()->quote($habilitacion);

        $sql = "SELECT  sge_formulario_habilitado.*,
                        sge_formulario_habilitado_detalle.*,
                        sge_elemento.*,
                        sge_concepto.concepto_externo,
                        sge_concepto.descripcion as concepto_desc,
                        sge_grupo_habilitado.grupo,
                        sge_tipo_elemento.descripcion as tipo_elemento_desc
                FROM sge_formulario_habilitado
                    JOIN sge_habilitacion ON (sge_formulario_habilitado.habilitacion = sge_habilitacion.habilitacion)
                    JOIN sge_formulario_habilitado_detalle ON (sge_formulario_habilitado.formulario_habilitado = sge_formulario_habilitado_detalle.formulario_habilitado)
                    LEFT JOIN sge_elemento ON (sge_formulario_habilitado_detalle.elemento = sge_elemento.elemento)
                    LEFT JOIN sge_concepto ON (sge_formulario_habilitado.concepto = sge_concepto.concepto)
                    JOIN sge_grupo_habilitado ON (sge_grupo_habilitado.formulario_habilitado = sge_formulario_habilitado.formulario_habilitado)
                    LEFT JOIN sge_tipo_elemento ON (sge_tipo_elemento.tipo_elemento = sge_formulario_habilitado_detalle.tipo_elemento)
                WHERE sge_formulario_habilitado.habilitacion = {$habilitacion}
                ORDER BY sge_formulario_habilitado_detalle.formulario_habilitado_detalle";

        return kolla_db::consultar($sql);
    }

    function get_encuesta($encuesta) {
        $sql = "SELECT
					nombre,
					descripcion,
					texto_preliminar,
					implementada,
					estado					
				FROM sge_encuesta_atributo
				WHERE encuesta = {$encuesta}
				";

        return kolla_db::consultar_fila($sql);
    }

    function get_encuesta_definicion_con_preguntas($encuesta) {
        $sql = "SELECT	ed.encuesta_definicion as encuesta_definicion,
	                    b.bloque,
	                    b.nombre as bloque_nombre,
	                    b.descripcion as bloque_descripcion,
	                    b.orden as bloque_orden,
	                    ed.pregunta as encuesta_definicion_pregunta,
	                    ed.orden as encuesta_definicion_orden,
	                    ed.obligatoria as encuesta_definicion_obligatoria,
	                    p.nombre as pregunta_nombre,
	                    cp.componente as pregunta_componente
                FROM sge_encuesta_definicion ed
	              INNER JOIN sge_pregunta p ON (ed.pregunta = p.pregunta)
	              INNER JOIN sge_bloque b ON (ed.bloque = b.bloque)
	              INNER JOIN sge_componente_pregunta cp ON (cp.numero = p.componente_numero)
                WHERE ed.encuesta = {$encuesta}
                ORDER BY b.orden, ed.orden;";

        return kolla::db()->consultar($sql);
    }
    
}