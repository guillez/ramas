<?php

class consultas_relevamiento_ingenierias
{
	function get_resultados($filtro = null)
	{
		$campos = "
            arau_instituciones.nombre					AS arau_institucion_nombre,
            arau_instituciones.institucion_araucano		AS arau_institucion,
            arau_responsables_academicas.nombre			AS arau_ua_nombre,
            arau_responsables_academicas.ra_araucano	AS arau_ua,
            arau_titulos.nombre							AS arau_titulo_nombre,
            arau_titulos.titulo_araucano				AS arau_titulo,
            sge_encuestado.apellidos,
            sge_encuestado.nombres,
            CASE WHEN (int_ingenieria_relevamiento.genero = '1')
                THEN (SELECT ing_genero.codigo FROM ing_genero WHERE ing_genero.nombre = 'Femenino')
                ELSE (SELECT ing_genero.codigo FROM ing_genero WHERE ing_genero.nombre = 'Masculino')
            END AS genero,
            (substr(int_ingenieria_relevamiento.fecha_nacimiento::text, 1, 4) ||
             substr(int_ingenieria_relevamiento.fecha_nacimiento::text, 6, 2) ||
             substr(int_ingenieria_relevamiento.fecha_nacimiento::text, 9, 2)) AS fecha_nacimiento,
            ing_tipo_documento.nombre AS tipo_documento,
            int_ingenieria_relevamiento.numero_documento,
            int_ingenieria_relevamiento.anio_ingreso,
            int_ingenieria_relevamiento.cant_total_mat_aprob,
            int_ingenieria_relevamiento.cant_mat_regul,
            int_ingenieria_relevamiento.cant_mat_plan_estu,
            int_ingenieria_relevamiento.cant_mat_aprob,
            (substr(int_ingenieria_relevamiento.fecha_ult_act_acad::text, 1, 4) ||
             substr(int_ingenieria_relevamiento.fecha_ult_act_acad::text, 6, 2) ||
             substr(int_ingenieria_relevamiento.fecha_ult_act_acad::text, 9, 2)) AS fecha_ult_act_acad,
            sge_encuestado.encuestado,
            sge_encuestado.usuario
        ";
		
		$tablas = "
                sge_encuestado
                INNER JOIN int_ingenieria_relevamiento ON (sge_encuestado.usuario = int_ingenieria_relevamiento.usuario)
                INNER JOIN arau_responsables_academicas ON (int_ingenieria_relevamiento.arau_ua = arau_responsables_academicas.ra_araucano::varchar)
                INNER JOIN arau_instituciones ON (arau_responsables_academicas.institucion_araucano = arau_instituciones.institucion_araucano)
                LEFT  JOIN arau_titulos ON (int_ingenieria_relevamiento.arau_titulo = arau_titulos.titulo_araucano)
                INNER JOIN ing_tipo_documento ON (ing_tipo_documento.codigo = int_ingenieria_relevamiento.tipo_documento)
        ";
		
		$where = array('TRUE');
		
		if ( isset($filtro) ) {
			if ( isset($filtro['usuario']) ) {
				$where[] = 'sge_encuestado.usuario = ' . kolla_db::quote($filtro['usuario']);
			}
			if ( isset($filtro['titulo_araucano']) ) {
				$where[] = 'arau_titulos.titulo_araucano = ' . kolla_db::quote($filtro['titulo_araucano']);
			}
		}
        
        $condicion = implode(' AND ', $where);
		
		$sql = "
            SELECT
                $campos
            FROM
                $tablas
            WHERE
                $condicion
            ";
        
		return kolla_db::consultar($sql);
	}
	
	function get_formulario_terminado($formulario_habilitado, $encuestado)
	{
		$sql = "SELECT 	(substr(sge_respondido_formulario.fecha_terminado::text, 1, 4) ||
						 substr(sge_respondido_formulario.fecha_terminado::text, 6, 2) ||
						 substr(sge_respondido_formulario.fecha_terminado::text, 9, 2)) AS fecha_terminado,
						 sge_formulario_habilitado.formulario_habilitado
				FROM	sge_formulario_habilitado,
						sge_respondido_formulario,
						sge_respondido_encuestado,
						sge_formulario_habilitado_detalle
				WHERE	sge_formulario_habilitado.formulario_habilitado	= $formulario_habilitado
                AND     sge_formulario_habilitado.formulario_habilitado = sge_respondido_formulario.formulario_habilitado
				AND		sge_respondido_formulario.respondido_formulario = sge_respondido_encuestado.respondido_formulario
				AND		sge_formulario_habilitado_detalle.formulario_habilitado = sge_formulario_habilitado.formulario_habilitado
				AND		sge_respondido_formulario.terminado 	= 'S'
				AND		sge_respondido_encuestado.encuestado 	= $encuestado
				";
		
		return consultar_fuente($sql);
	}
	
	function get_respuestas_completas_formulario_habilitado_encuestado($formulario_habilitado, $encuestado)
	{
		$sql = "SELECT	*
				FROM	respuestas_completas_formulario_habilitado($formulario_habilitado)
						resultados (
				                    habilitacion integer,
				                    formulario_habilitado integer,
				                    formulario_nombre text,
				                    respondido_formulario integer, 
				                    ingreso integer, 
				                    fecha_inicio date, 
				                    terminado_formulario character(1), 
				                    fecha_terminado date, 
				                    respondido_encuesta integer, 
				                    respondido_detalle integer, 
				                    moderada character(1), 
				                    respuesta_codigo integer, 
				                    respuesta_valor character varying, 
				                    encuesta_definicion integer,
				                    encuesta integer, 
				                    orden_encuesta integer, 
				                    orden_bloque smallint, 
				                    bloque integer, 
				                    bloque_nombre character varying(255), 
				                    orden_pregunta smallint, 
				                    pregunta integer, 
				                    pregunta_nombre character varying(4096), 
				                    componente character varying(35),
				                    tabla_asociada character varying(50), 
				                    concepto integer, 
				                    encuesta_nombre character varying, 
				                    elemento integer, 
				                    elemento_nombre text, 
				                    respondido_encuestado integer,
				                    encuestado integer,
				                    usuario character varying(60),
                                    respondido_por character varying(60),
				                    ignorado char
				                    )
				WHERE	encuestado = $encuestado
				";
		return consultar_fuente($sql);
	}
	
	/**
	 * Retorna el listado de usuarios a importar.
	 */
	function get_usuarios_para_importar($where = '')
	{
		if ($where) {
			$where = " AND $where";
		}
		
		$sql = "SELECT 	int_ingenieria_relevamiento.tipo_documento,
						int_ingenieria_relevamiento.numero_documento,
						int_ingenieria_relevamiento.pais_documento,
						int_ingenieria_relevamiento.usuario,
						int_ingenieria_relevamiento.clave,
						int_ingenieria_relevamiento.arau_ua_nombre,
						int_ingenieria_relevamiento.arau_ua,
						int_ingenieria_relevamiento.arau_titulo_nombre,
						int_ingenieria_relevamiento.arau_titulo,
						int_ingenieria_relevamiento.apellidos,
						int_ingenieria_relevamiento.nombres,
						int_ingenieria_relevamiento.fecha_nacimiento,
						int_ingenieria_relevamiento.email,
						int_ingenieria_relevamiento.genero,
						int_ingenieria_relevamiento.anio_ingreso,
						int_ingenieria_relevamiento.cant_total_mat_aprob,
						int_ingenieria_relevamiento.cant_mat_regul,
						int_ingenieria_relevamiento.cant_mat_plan_estu,
						int_ingenieria_relevamiento.cant_mat_aprob,
						int_ingenieria_relevamiento.fecha_ult_act_acad,
						int_ingenieria_relevamiento.importado,
						int_ingenieria_relevamiento.resultado_proceso,
						int_ingenieria_relevamiento.resultado_descripcion
				FROM 	int_ingenieria_relevamiento
				WHERE  	int_ingenieria_relevamiento.importado = 'N'
				AND 	(int_ingenieria_relevamiento.resultado_proceso = '' OR int_ingenieria_relevamiento.resultado_proceso IS NULL)
						$where
				";
		
		return kolla_db::consultar($sql);
		
	}
	
}
?>