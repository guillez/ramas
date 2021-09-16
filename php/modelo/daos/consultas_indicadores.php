<?php

class consultas_indicadores
{
	/***************************************************** 
	 ** Funciones para manejo de indicadores de encuesta
	 ****************************************************/
	
	/* 
	 * Obtiene las preguntas que pueden ser usadas como indicadores de las encuestas
	 * Desde versión 3.2 todas las preguntas pueden ser usadas como indicador excepto las etiquetas
	 */
	function get_preguntas_para_indicador($encuesta=null, $bloque=null)
	{  
		$where = array('TRUE');
		if (isset($encuesta)) {
			$where[] = 'ed.encuesta = ' . kolla_db::quote($encuesta);
		}
		if (isset($bloque)) {
			$where[] = 'ed.bloque = ' . kolla_db::quote($bloque);
		}
		
		$where = implode(' AND ', $where);
		
		$sql = "
			SELECT
				ed.encuesta,
				ed.pregunta,
				ed.bloque,
				ed.orden,
				b.nombre AS bloque_nombre,
				CASE WHEN LENGTH(p.nombre) >= 80 THEN SUBSTR(p.nombre, 0, 80) || ' ...' 
					ELSE p.nombre END AS pregunta_nombre,
				p.tabla_asociada as tabla_asociada,
				b.nombre  || '--' || p.nombre as indicador_nombre,
				CASE WHEN LENGTH(p.nombre) >= 80 THEN SUBSTR(p.nombre, 0, 80) || ' ...  - ' || p.pregunta || ' - ' || cp.descripcion  
					 ELSE p.nombre || ' - ' || p.pregunta || ' - ' || cp.descripcion
				END as indicador_texto,
				ed.encuesta || ',' || ed.pregunta || ',' || ed.bloque || ',' || ed.orden as indicador_id
			FROM 
				sge_encuesta_definicion ed
				INNER JOIN sge_pregunta p ON (ed.pregunta = p.pregunta)
				INNER JOIN sge_bloque b ON (ed.bloque = b.bloque)
				INNER JOIN sge_componente_pregunta cp ON (cp.numero = p.componente_numero AND cp.componente != 'label')
			WHERE
				$where
			ORDER BY
				ed.orden
		";
		
		return consultar_fuente($sql);
	}
		
	/* 
	 * Obtiene los indicadores definidos para un formulario dado
	 */
	function get_formulario_indicadores_definidos($formulario_habilitado, $habilitacion)
	{
		$formulario = kolla_db::quote($formulario_habilitado);
		
		if (isset($habilitacion)) {
			$habilitacion = kolla_db::quote($habilitacion);
			$from = ' INNER JOIN sge_habilitacion ON (sge_encuesta_definicion.encuesta = sge_habilitacion.encuesta) ';
			$tabla = ' sge_encuesta_habilitacion_indicadores ';
			$and_habilitacion = ' AND sge_habilitacion.habilitacion = ei.habilitacion ';
			$where = " AND sge_habilitacion.habilitacion = $habilitacion";			 
		} else {
			$from = '';	
			$tabla = ' sge_encuesta_indicadores ';
			$and_habilitacion = '';
			$where = '';
		}
		
		$sql = "SELECT		sge_encuesta_definicion.encuesta,
							sge_encuesta_definicion.bloque,
							sge_encuesta_definicion.numero,
							sge_encuesta_definicion.pregunta,
							sge_pregunta.tabla_asociada,
							sge_pregunta.nombre 				AS pregunta_nombre,
							sge_bloque.nombre 					AS bloque_nombre,
							sge_componente_pregunta.numero 		AS componente,
							sge_componente_pregunta.descripcion AS componente_descripcion
				FROM 		sge_encuesta_definicion
							$from
							INNER JOIN $tabla ei ON (sge_encuesta_definicion.encuesta = ei.encuesta
												$and_habilitacion 
												AND sge_encuesta_definicion.bloque = ei.bloque
												AND sge_encuesta_definicion.numero = ei.numero
												AND sge_encuesta_definicion.pregunta = ei.pregunta)
							INNER JOIN sge_pregunta ON (ei.pregunta = sge_pregunta.pregunta)
							INNER JOIN sge_bloque ON (ei.bloque = sge_bloque.bloque)
							INNER JOIN sge_componente_pregunta ON (sge_pregunta.numero = sge_componente_pregunta.numero)
				WHERE 		sge_encuesta_definicion.encuesta = ".quote($encuesta)."
							$where
				ORDER BY 	sge_encuesta_definicion.numero
				";
		
		return consultar_fuente($sql);
	}

	/* 
	 * Obtiene los datos (respuestas posibles) de un determinado indicador de respuesta cerrada
	 */
	function get_datos_indicador_cerrado ($filtro)
	{
		if ($filtro['tabla_asociada'] != '') {
			$select = " r.codigo as respuesta, r.nombre as respuesta_nombre ";
			$join = " AND p.tabla_asociada = ".quote($filtro['tabla_asociada']);
			$rtas = " , ".$filtro['tabla_asociada']." as r ";
		} else {
			$select = " r.respuesta as respuesta, r.valor_tabulado as respuesta_nombre ";
			$join = "";
			$rtas = " INNER JOIN sge_pregunta_respuesta pr ON (p.pregunta = pr.pregunta)
					INNER JOIN sge_respuesta r ON (pr.respuesta = r.respuesta) ";
		}
				
		if ($filtro['dehabilitacion'] == 0) {
			$from = 'sge_encuesta_indicadores ei';
			$where = '';
		} else {
			$from = 'sge_encuesta_habilitacion_indicadores ei';
			$where = ' AND ei.habilitacion = '.quote($filtro['habilitacion']);
		}

		$sql = "SELECT	
					p.tabla_asociada,
					p.pregunta as pregunta,
					p.nombre as pregunta_nombre,
					$select 
				FROM $from 
						INNER JOIN sge_pregunta p ON (ei.pregunta = p.pregunta $join )
						INNER JOIN sge_bloque b ON (ei.bloque = b.bloque)
					    $rtas 
				WHERE 
					ei.encuesta = ".quote($filtro['encuesta'])." AND
					ei.pregunta = ".quote($filtro['pregunta'])." AND
					ei.bloque = ".quote($filtro['bloque'])." AND
					ei.numero = ".quote($filtro['numero'])."
					$where ;";
		
		return consultar_fuente($sql);
	}	
	
	/* 
	 * Obtiene los resultados (respuestas dadas) para una respuesta de un indicador de pregunta cerrada 
	 */
	function get_resultados_indicador_cerrado ($filtro)
	{
		if ($filtro['tabla_asociada'] != '') {
			$tabla_asociada = $filtro['tabla_asociada'];
			$from = " INNER JOIN $tabla_asociada  as r ON (er.respuesta = r.codigo) ";
		} else {
			$from = " INNER JOIN sge_pregunta_respuesta pr ON (p.pregunta = pr.pregunta)					
					  INNER JOIN sge_respuesta r ON (er.respuesta = r.respuesta AND pr.respuesta = r.respuesta) ";
		}
		
		$tabla_indicadores = ($filtro['dehabilitacion']==0) ? ' sge_encuesta_indicadores ei ' : ' sge_encuesta_habilitacion_indicadores ei ';
		$and_habilitacion = ($filtro['dehabilitacion']==0) ? '' : ' AND ei.habilitacion = '.quote($filtro['habilitacion']);
		
		$sql = "SELECT COUNT(*)
				FROM 
				  $tabla_indicadores
					INNER JOIN sge_pregunta p ON (ei.pregunta = p.pregunta)
					INNER JOIN sge_bloque b ON (ei.bloque = b.bloque)
					INNER JOIN sge_encuesta_definicion ed ON (ei.pregunta = ed.pregunta AND ei.bloque = ed.bloque AND ei.numero = ed.numero)
					INNER JOIN sge_encuestas_realizada_encabezado ere ON (ei.encuesta = ere.encuesta)
					INNER JOIN sge_encuestas_realizada er ON (ere.encuesta_encabezado = er.encuesta_encabezado AND ei.pregunta = er.pregunta AND ei.bloque = er.bloque AND ei.numero = er.numero)
					$from
				 WHERE 
					ei.encuesta = ".quote($filtro['encuesta'])." AND
					ei.pregunta = ".quote($filtro['pregunta'])." AND
					ei.bloque = ".quote($filtro['bloque'])." AND
					ei.numero = ".quote($filtro['numero'])." AND
					r.respuesta = ".quote($filtro['respuesta'])."
					$and_habilitacion ;";
		
		return consultar_fuente($sql);		
	}
		
	/* 
	 * Obtiene los resultados (cantidad de respuestas dadas) para cada respuesta de un indicador   
	 */	
	function get_resultados_indicadores_agrupados($indicador, $filtro)
	{	
		$where = '';
		$from_indicador = ($filtro['dehabilitacion']==0) ? " sge_encuesta_indicadores ei " : " sge_encuesta_habilitacion_indicadores ei ";
		$where .= isset($filtro['fecha_desde']) ? "AND ere.fecha >= ".quote($filtro['fecha_desde']) : "";
		$where .= isset($filtro['fecha_hasta']) ? "AND ere.fecha <= ".quote($filtro['fecha_hasta']) : "";
		$group_by = '';
		$order_by = '';
		
		if ($indicador['componente'] == 9) {
			$respuesta = " ml.localidad as respuesta ";
			$respuesta_nombre = " ml.nombre as respuesta_nombre ";
			$cantidad = " count(er.valor) as cantidad ";
			$from = " INNER JOIN sge_encuestas_realizada_valores er ON (ere.encuesta_encabezado = er.encuesta_encabezado AND ei.pregunta = er.pregunta
																		 AND ei.bloque = er.bloque AND ei.numero = er.numero) 
						INNER JOIN mug_localidades ml ON (ml.localidad = er.valor::int)";
			$where .=  " AND er.valor != '' ";
			$group_by = ", ml.localidad , respuesta_nombre ";
			$order_by = ", ml.nombre";			
		} else {
			if ($this->es_pregunta_cerrada($indicador['componente'])) {//es pregunta cerrada
				$from = " INNER JOIN sge_encuestas_realizada er ON (ere.encuesta_encabezado = er.encuesta_encabezado AND ei.pregunta = er.pregunta
																		 AND ei.bloque = er.bloque AND ei.numero = er.numero) ";
				if ($indicador['tabla_asociada'] != '') {
					$respuesta = " r.codigo as respuesta "; 
					$respuesta_nombre = " r.nombre as respuesta_nombre "; 
					$cantidad = " count(r.nombre) as cantidad ";
					$tabla_asociada = $indicador['tabla_asociada'];	
					$from .= " INNER JOIN $tabla_asociada r ON (er.respuesta = r.codigo) ";
					$where .=  " AND r.codigo != -1 ";
					$group_by = ", r.codigo, r.nombre";
					$order_by = ", r.codigo ";
				} else {
					$respuesta = " r.respuesta ";
					$respuesta_nombre = " r.valor_tabulado as respuesta_nombre ";
					$cantidad = " count(r.valor_tabulado) as cantidad ";
					$from .= " INNER JOIN sge_pregunta_respuesta pr ON (pr.pregunta = ed.pregunta)
								INNER JOIN sge_respuesta r ON (er.respuesta = r.respuesta AND pr.respuesta = r.respuesta) ";
					$where .=  " AND r.respuesta != -1 ";
					$group_by = ", r.respuesta, respuesta_nombre";
					$order_by = ", r.respuesta ";
				}
			} else {//es pregunta abierta
				$respuesta = " '' as respuesta ";
				$respuesta_nombre = " 'Respuesta libre' as respuesta_nombre ";
				$cantidad = " count('') as cantidad ";
				$from = " INNER JOIN sge_encuestas_realizada_valores er ON (ere.encuesta_encabezado = er.encuesta_encabezado AND ei.pregunta = er.pregunta
				 																AND ei.bloque = er.bloque AND ei.numero = er.numero) ";
				$where .=  " AND er.valor != '' ";
			}
		}

		$from .= (!$filtro['no_terminadas']) ? " INNER JOIN sge_encuestas_terminada et ON (ere.encuesta_encabezado = et.encuesta_encabezado) " : "";
		$where_indicador = ($filtro['dehabilitacion']==0) ? "" : " AND ei.habilitacion = ".quote($filtro['habilitacion']);
				
		$sql = "SELECT ed.pregunta as pregunta, $respuesta, $respuesta_nombre, $cantidad
				FROM 
					$from_indicador
					INNER JOIN sge_encuesta_definicion ed ON (ei.encuesta = ed.encuesta AND ei.bloque = ed.bloque AND ei.numero = ed.numero AND ei.pregunta = ed.pregunta)
					INNER JOIN sge_encuestas_realizada_encabezado ere ON (ere.encuesta = ei.encuesta)
					$from
				WHERE 
					ei.encuesta = ".quote($indicador['encuesta'])." AND
					ei.pregunta = ".quote($indicador['pregunta'])." AND
					ei.bloque = ".quote($indicador['bloque'])." AND
					ei.numero = ".quote($indicador['numero'])." AND
					ere.habilitacion = ".quote($filtro['habilitacion'])." 
					$where
					$where_indicador
					GROUP BY ed.pregunta, ere.habilitacion $group_by
					ORDER BY ed.pregunta $order_by;";
		
		return consultar_fuente($sql);
	}
	
	
	function get_total_de_respuestas_encuesta($filtro)
	{
		/*
		 * Terminar cuando se defina que hacer con los indicadores.
		 */
		return array();
		$where = isset($filtro['fecha_desde']) ? "AND ere.fecha >= ".quote($filtro['fecha_desde']) : '';
		$where .= isset($filtro['fecha_hasta']) ? "AND ere.fecha <= ".quote($filtro['fecha_hasta']) : '';
		$from = (!$filtro['no_terminadas']) ? " INNER JOIN sge_encuestas_terminada et ON (ere.encuesta_encabezado = et.encuesta_encabezado) " : '';

		$sql = "SELECT COUNT(DISTINCT ere.encuesta_encabezado) as respondieron
				FROM 
					sge_encuestas_realizada_encabezado ere 
					$from
				WHERE 
					ere.encuesta = ".quote($filtro['encuesta'])." AND
					ere.habilitacion = ".quote($filtro['habilitacion'])."
					$where					
					;";
		
		return consultar_fuente($sql);
	} 
	
	function get_resultados_indicador_sin_responder($indicador, $filtro)
	{
		/*
		 * Terminar cuando se defina que hacer con los indicadores.
		 */
		return array();
		$where = isset($filtro['fecha_desde']) ? " AND ere.fecha >= ".quote($filtro['fecha_desde']) : "";
		$where .= isset($filtro['fecha_hasta']) ? " AND ere.fecha <= ".quote($filtro['fecha_hasta']) : "" ;
		$from  = (!$filtro['no_terminadas']) ?	" INNER JOIN sge_encuestas_terminada et ON (ere.encuesta_encabezado = et.encuesta_encabezado) " : "";
		$from_indicador = ($filtro['dehabilitacion']==0) ? " sge_encuesta_indicadores ei " : " sge_encuesta_habilitacion_indicadores ei ";
		$where_indicador = ($filtro['dehabilitacion']==0) ? "" : " AND ei.habilitacion = ".quote($filtro['habilitacion']);

		if (($indicador['componente']==9) || (!$this->es_pregunta_cerrada($indicador['componente']))) {//es de localidad
			$tabla_respuestas = " sge_encuestas_realizada_valores ";
			$respuesta = " er.valor = '' ";
		} else {
			$tabla_respuestas = " sge_encuestas_realizada ";
			$respuesta = " er.respuesta = -1 ";
		}
		
		$sql = "SELECT count(ere.encuesta_encabezado) as sinresponder
				FROM 
					sge_encuestas_realizada_encabezado ere 
						$from
						INNER JOIN $from_indicador ON (ere.encuesta = ei.encuesta)
						LEFT JOIN $tabla_respuestas er ON (ere.encuesta_encabezado = er.encuesta_encabezado AND ei.encuesta = er.encuesta
																AND ei.bloque = er.bloque AND ei.numero = er.numero AND ei.pregunta = er.pregunta)
				WHERE 
					ei.encuesta = ".quote($indicador['encuesta'])." AND
					ei.pregunta = ".quote($indicador['pregunta'])." AND
					ei.bloque = ".quote($indicador['bloque'])." AND
					ei.numero = ".quote($indicador['numero'])." AND
					ere.habilitacion = ".quote($filtro['habilitacion'])." 
					$where 
					$where_indicador 
					AND (er.encuesta_encabezado IS NULL OR $respuesta)
				;";
		
		return consultar_fuente($sql);
	}
	
	function es_pregunta_cerrada($componente)
	{
		//componente 6 no está definido, 7 es etiqueta 
		if (($componente == 1) || ($componente > 7 && $componente < 16)) {
			return false;
		}
		if (($componente == 2) || ($componente == 3) || ($componente == 4) || ($componente == 5)) {
			return true;
		}
		return "ERROR";
	}
	
}
?>