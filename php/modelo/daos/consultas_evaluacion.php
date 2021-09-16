<?php

class consultas_evaluacion
{
	
	function get_puntajes_encuesta($encuesta=null)
	{
		$where = isset($encuesta)?" sge_puntaje.encuesta = $encuesta" : "1=1";
		
		$query  = "	SELECT
						sge_puntaje.puntaje, 
						sge_puntaje.nombre, 
						sge_puntaje.implementado,
                                                sge_puntaje.fecha_hora_creacion,
                                                sge_puntaje.encuesta
					FROM
						sge_puntaje
					WHERE
						$where";
		return kolla_db::consultar($query);
	}
	
	function get_puntaje_encuesta($idpuntaje)
	{
		$query = "	SELECT 
					  sge_puntaje.puntaje as puntaje, 
					  sge_puntaje.nombre as nombre,
					  sge_puntaje.encuesta as encuesta,
					  sge_encuesta_atributo.nombre as encuesta_nombre
					FROM 
					  kolla.sge_puntaje, 
					  kolla.sge_encuesta_atributo
					WHERE 
					  sge_puntaje.encuesta = sge_encuesta_atributo.encuesta AND puntaje = $idpuntaje";
		return kolla_db::consultar_fila($query);
	}
	
	function get_preguntas_puntaje_encuesta($idencuesta, $idpuntaje = null)
	{
		$where_clause = ["encuesta = $idencuesta","sge_componente_pregunta.tipo = 'C'"];
		$select_clause = [ 	"sge_encuesta_definicion.encuesta","sge_encuesta_definicion.encuesta_definicion",
							"sge_encuesta_definicion.bloque", "sge_encuesta_definicion.pregunta", "sge_bloque.nombre as nombre_bloque",
							"sge_pregunta.nombre as nombre_pregunta" ];
		$inner_clause = '';
		if ( $idpuntaje ){
			$where_clause[] = "sge_puntaje_pregunta.puntaje = $idpuntaje";
			$inner_clause = "LEFT JOIN sge_puntaje_pregunta ON sge_encuesta_definicion.encuesta_definicion = sge_puntaje_pregunta.encuesta_definicion";
		}
		
		$select_clause[] = isset($idpuntaje)?"sge_puntaje_pregunta.puntaje":"NULL as puntaje";
		$select_clause[] = ( isset($idpuntaje)?"sge_puntaje_pregunta.puntos":"0" )." AS puntos";
		
		$where = implode(" AND ", $where_clause);
		$select = implode(',', $select_clause);
		
		$query = "	SELECT
						$select
					FROM 
						sge_encuesta_definicion 
						INNER JOIN sge_pregunta ON  sge_encuesta_definicion.pregunta = sge_pregunta.pregunta
						INNER JOIN sge_bloque ON sge_encuesta_definicion.bloque = sge_bloque.bloque
						INNER JOIN sge_componente_pregunta ON sge_pregunta.componente_numero = sge_componente_pregunta.numero
						$inner_clause
					WHERE $where 
					ORDER BY sge_encuesta_definicion.encuesta, sge_bloque.orden, sge_encuesta_definicion.orden";
		return kolla_db::consultar($query);
		
	}
    
    function get_datos_encuesta_definicion($encuesta_definicion)
    {
        $encuesta_definicion = kolla_db::quote($encuesta_definicion);
        
		$sql = "SELECT		sge_bloque.nombre   AS nombre_bloque,
							sge_pregunta.nombre AS nombre_pregunta
	            FROM		sge_encuesta_definicion
                                INNER JOIN sge_pregunta ON  sge_encuesta_definicion.pregunta = sge_pregunta.pregunta
                                INNER JOIN sge_bloque ON sge_encuesta_definicion.bloque = sge_bloque.bloque
                WHERE       sge_encuesta_definicion.encuesta_definicion = $encuesta_definicion
	        	";
        
        return kolla_db::consultar_fila($sql);
    }
    
    function get_nombre_respuesta($respuesta)
	{
        $respuesta = kolla_db::quote($respuesta);
        
		$sql = "SELECT	sge_respuesta.valor_tabulado
				FROM 	sge_respuesta
				WHERE	sge_respuesta.respuesta = $respuesta
				";
		
        return kolla_db::consultar_fila($sql);;
	}
	
        function get_evaluaciones_habilitacion($habilitacion)
        {
            $where = isset($habilitacion) ? ' AND se.habilitacion = '.kolla_db::quote($habilitacion) : '';
            
            $sql = "SELECT 
                            se.evaluacion as evaluacion,  
                            se.cerrada as cerrada, 
                            CASE WHEN se.cerrada = 'S' THEN 'Cerrada'
                              ELSE 'Abierta' END as cerrada_descripcion,
                            se.habilitacion as habilitacion, 
                            se.nombre as evaluacion_nombre,
                            sh.descripcion as habilitacion_nombre,
                            sh.unidad_gestion as unidad_gestion
                    FROM sge_evaluacion se 
                            INNER JOIN sge_habilitacion sh ON (se.habilitacion = sh.habilitacion)
                    WHERE TRUE $where;";
            return kolla_db::consultar($sql);
        }
        
        function get_evaluacion($evaluacion) 
        {
            $where = isset($evaluacion) ? ' AND se.evaluacion = '.kolla_db::quote($evaluacion) : '';
            
            $sql = "SELECT 
                        se.evaluacion
                        , se.cerrada
                        , se.habilitacion
                        , se.nombre
                        , spa.puntaje
                        , spa.puntaje_aplicacion                        
                        , spa.formulario_habilitado
                        , spa.formulario_habilitado_detalle
                  FROM sge_evaluacion se 
                        INNER JOIN sge_puntaje_aplicacion spa ON (spa.evaluacion = se.evaluacion)
                        INNER JOIN sge_puntaje sp ON (sp.puntaje = spa.puntaje)
                WHERE TRUE $where;";
            return kolla_db::consultar_fila($sql);
        }        
 
        function get_evaluacion_detalle($evaluacion)
        {
            $where = isset($evaluacion) ? ' AND se.evaluacion = '.kolla_db::quote($evaluacion) : '';
            
            /*
             * 
             * 
             */
            $sql = "SELECT 
                        se.evaluacion, se.cerrada, se.habilitacion, se.nombre
                        , spa.puntaje, spa.puntaje_aplicacion, spa.formulario_habilitado, spa.formulario_habilitado_detalle
                        , 
                        sp.nombre as nombre_puntaje
                        , sea.nombre as nombre_encuesta 
                        , sc.concepto
                        , ste.tipo_elemento
                        , CASE 
                            WHEN (sc.concepto IS NOT NULL) THEN sc.descripcion 
                            ELSE ' --Sin concepto-- ' END AS concepto_descripcion
                        , CASE 
                            WHEN (ste.tipo_elemento IS NOT NULL) THEN ste.descripcion 
                            ELSE ' --Sin tipo de elemento-- ' END AS tipo_elemento_descripcion
                        , CASE 
                            WHEN (selto.elemento IS NOT NULL) THEN selto.descripcion 
                            ELSE ' --Sin elemento-- ' END AS elemento_descripcion
                          FROM sge_evaluacion se 
                                INNER JOIN sge_habilitacion sh ON (sh.habilitacion = se.habilitacion)
                                INNER JOIN sge_formulario_habilitado sfh ON (sfh.habilitacion = sh.habilitacion)
                                INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sfhd.formulario_habilitado = sfh.formulario_habilitado)
                                INNER JOIN sge_encuesta_atributo sea ON (sea.encuesta = sfhd.encuesta)

                                LEFT JOIN sge_puntaje_aplicacion spa ON (spa.evaluacion = se.evaluacion 
                                                        AND spa.formulario_habilitado = sfh.formulario_habilitado
                                                        AND spa.formulario_habilitado_detalle = sfhd.formulario_habilitado_detalle
                                                        )
                                LEFT JOIN sge_puntaje sp ON (sp.puntaje = spa.puntaje)	
                                LEFT JOIN sge_concepto sc ON (sc.concepto = sfh.concepto)
                                LEFT JOIN sge_tipo_elemento ste ON (ste.tipo_elemento = sfhd.tipo_elemento)
                                LEFT JOIN sge_elemento selto ON (selto.elemento = sfhd.elemento)

                WHERE TRUE $where;";
            return kolla_db::consultar($sql);
        }
        
        function get_puntajes_evaluacion ($evaluacion) 
        {
            $where = isset($evaluacion) ? " AND se.evaluacion = $evaluacion " : "";
            
            $sql = "SELECT DISTINCT 
                        sp.puntaje, 
                        sp.nombre, 
                        sp.implementado, 
                        sp.fecha_hora_creacion, 
                        sp.encuesta
                    FROM sge_evaluacion se INNER JOIN sge_puntaje_aplicacion spa ON (spa.evaluacion = se.evaluacion)
                        INNER JOIN sge_puntaje sp ON (sp.puntaje = spa.puntaje)	
                        WHERE TRUE $where ;";
            return kolla_db::consultar($sql);
        }
}