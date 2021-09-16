<?php

class consultas_encuestas
{
	protected $separador_localidad	 = '|||';
    private $encuesta_reservada_max  = 100;
    private $bloques_reservada_max	 = 1000;
    private $pregunta_reservada_max  = 10000;
    private $respuesta_reservada_max = 100000;
    private $componentes_cerrados_pregunta = array(2, 3, 4, 5);
    
    const COMBO_DINAMICO = 19;
    
	/**
	 Retorna los datos de todas las preguntas de la encuesta dada
	 ordenados por bloque y numero de pregunta
	 */
	function get_datos_preguntas($encuesta)
	{
		$sql = 'SELECT
					ed.encuesta,
		            ed.bloque,
		            ed.pregunta,
		            b.nombre 		AS bloque_nombre,
		            p.nombre 		AS pregunta_nombre,
		            cp.numero		AS comp_numero,
		            cp.componente 	AS componente,
		            ed.obligatoria 	AS obligatoria,
		            ea.nombre 		AS encuesta_nombre,
		            p.tabla_asociada AS tabla_asociada,
		            p.tabla_asociada_codigo AS tabla_asociada_codigo,
		            p.tabla_asociada_descripcion AS tabla_asociada_descripcion
				FROM
					sge_encuesta_definicion ed 
						INNER JOIN sge_pregunta p ON (ed.pregunta = p.pregunta)
						INNER JOIN sge_bloque b ON (ed.bloque = b.bloque)
						INNER JOIN sge_componente_pregunta cp ON (cp.numero = p.componente_numero)
						INNER JOIN sge_encuesta_atributo ea ON (ea.encuesta = ed.encuesta)
				WHERE 
					ed.encuesta = '.kolla_db::quote($encuesta).' 
				ORDER BY numero, bloque';
		return consultar_fuente($sql);
	}

    /**
    Retorna los datos de todas las preguntas de la encuesta dada
    ordenados por bloque y numero de pregunta sin las de tipo etiqueta
     */
    function get_datos_preguntas_para_filtro_resultados($encuesta)
    {
        $sql = "SELECT
					ed.encuesta,
					ed.encuesta_definicion,
		            ed.bloque,
		            ed.pregunta,
		            b.nombre 		AS bloque_nombre,
		            p.nombre 		AS pregunta_nombre,
		            cp.numero		AS comp_numero,
		            cp.componente 	AS componente,
		            ed.obligatoria 	AS obligatoria,
		            ea.nombre 		AS encuesta_nombre,
		            p.tabla_asociada AS tabla_asociada,
		            p.tabla_asociada_codigo AS tabla_asociada_codigo,
		            p.tabla_asociada_descripcion AS tabla_asociada_descripcion
				FROM
					sge_encuesta_definicion ed 
						INNER JOIN sge_pregunta p ON (ed.pregunta = p.pregunta)
						INNER JOIN sge_bloque b ON (ed.bloque = b.bloque)
						INNER JOIN sge_componente_pregunta cp ON (cp.numero = p.componente_numero)
						INNER JOIN sge_encuesta_atributo ea ON (ea.encuesta = ed.encuesta)
				WHERE 
				    ed.encuesta = ".kolla_db::quote($encuesta)."
					AND cp.componente IN ('radio', 'combo', 'list', 'check', 'combo_autocompletado', 'combo_dinamico')  
				ORDER BY bloque, ed.orden";
        return consultar_fuente($sql);
    }


	/*
	Retorna la lista de bloques con un filtro
	*/
	function get_bloques($where=null)
	{
		$where = isset($where) ? ' WHERE '.$where : '';
		$sql = "SELECT
					tb.bloque		AS bloque, 
					tb.nombre		AS nombre, 
					tb.descripcion	AS descripcion 
				FROM sge_bloque AS tb
				$where
				ORDER BY bloque;
		";
		return consultar_fuente($sql);
	}

	function get_bloques_encuesta($encuesta)
	{
		$encuesta = kolla_db::quote($encuesta);
		
		$sql = "
			SELECT
				sb.bloque,
				sb.nombre,
				sed.pregunta
			FROM 
				sge_bloque AS sb
				INNER JOIN sge_encuesta_definicion sed ON (sed.bloque = sb.bloque)
			WHERE 
				sed.encuesta = $encuesta
			ORDER BY 
				sed.pregunta ASC
		";
		
		return kolla_db::consultar($sql);
	}

	/*
	 * Retorna la lista de preguntas con un filtro
	 */
	function get_preguntas($where=null)
	{
		if (!isset($where) || $where == null) {
			$where = 'TRUE';
		}
		
		$sql = "SELECT	p.pregunta, 
						p.nombre,
						p.tabla_asociada,
						c.descripcion AS componente,
						c.numero,
                        ug.nombre AS ug_nombre
				FROM 	sge_pregunta AS p
							JOIN sge_componente_pregunta AS c ON (p.componente_numero = c.numero)
	                        LEFT OUTER JOIN sge_unidad_gestion AS ug ON (p.unidad_gestion = ug.unidad_gestion)
				WHERE	$where
				ORDER BY pregunta
				";
        
		return kolla_db::consultar($sql);
	}

	/*
	 * Retorna la lista de preguntas de una encuesta (para reporte por encuestado)
	 */
	function get_preguntas_encuesta_reporte($id_encuesta)
	{
		$sql = 'SELECT 
                   ed.numero,
                   ed.pregunta,
                   p.nombre AS pregunta_nombre,
                   p.tabla_asociada as tabla_asociada,
                   cp.componente as componente,
                   r.respuesta AS respuesta,
                   r.valor_tabulado AS respuesta_valor
              FROM sge_encuesta_definicion ed,
                   sge_componente_pregunta cp,
                   sge_pregunta p 
                   LEFT JOIN sge_pregunta_respuesta pr 
                        ON (p.pregunta = pr.pregunta)
                   LEFT JOIN sge_respuesta r 
                        ON (pr.respuesta = r.respuesta)
             WHERE 
			 	ed.encuesta = '.kolla_db::quote($id_encuesta)." AND
                ed.pregunta = p.pregunta AND 
                cp.numero = p.numero
                AND (cp.componente <> 'label') 
                AND (cp.componente = 'list' OR cp.componente='check') --multiples
			UNION 
			SELECT 
                   ed.numero,
                   ed.pregunta,
                   p.nombre AS pregunta_nombre,
                   p.tabla_asociada as tabla_asociada,
                   cp.componente as componente,
                   -1 as a,
                   '' as b
                   --r.respuesta AS respuesta,
                   --r.valor_tabulado AS respuesta_valor
                   
              FROM sge_encuesta_definicion ed,
                   sge_componente_pregunta cp,
                   sge_pregunta p 
                 --  LEFT JOIN sge_pregunta_respuesta pr 
                 --       ON (p.pregunta = pr.pregunta)
                 --  LEFT JOIN sge_respuesta r 
                 --       ON (pr.respuesta = r.respuesta)
             WHERE 
                ed.encuesta = ".kolla_db::quote($id_encuesta)." AND
                ed.pregunta = p.pregunta AND 
                cp.numero = p.numero
                AND (cp.componente <> 'label')
                AND (cp.componente != 'list' AND cp.componente !='check') --unicas
			ORDER BY numero, respuesta";
			return consultar_fuente($sql);
	}

	/*
		Retorna la lista de respuestas posibles para una pregunta mï¿½ltiple
	*/
	function get_opciones_respuesta_pregunta_multiple($id_encuesta, $pregunta)
	{
		$sql = "SELECT ed.encuesta,
			       ed.numero,
			       ed.bloque,
			       b.nombre AS bloque_nombre,
			       ed.pregunta,
			       p.nombre AS pregunta_nombre,
			       cp.componente as componente,
			       r.respuesta AS respuesta,
			       r.valor_tabulado AS respuesta_valor
			  FROM sge_encuesta_definicion ed,
			       sge_bloque b,
			       sge_componente_pregunta cp,
			       sge_pregunta p 
				   INNER JOIN sge_pregunta_respuesta pr ON (p.pregunta = pr.pregunta)
					INNER JOIN sge_respuesta r ON (pr.respuesta = r.respuesta)
			 WHERE 
				ed.encuesta = ".kolla_db::quote($id_encuesta)." AND
				ed.pregunta = p.pregunta AND 
				ed.bloque = b.bloque AND 
				cp.numero = p.numero
				AND (cp.componente = 'list' OR cp.componente='check')
				AND (cp.componente <> 'label')
				AND p.pregunta = ".kolla_db::quote($pregunta)."
				ORDER BY respuesta";
		return consultar_fuente($sql);
	}
	
	/*
	 *	Retorna la lista de respuestas posibles de una pregunta dada. 
	 */
	function get_opciones_respuesta_pregunta_para_pdf($pregunta)
	{
		$sql = "SELECT		pr.orden,
							p.tabla_asociada,
		               		r.respuesta,
							r.valor_tabulado AS respuesta_valor
				FROM		sge_pregunta p 
					   			INNER JOIN sge_pregunta_respuesta pr ON (p.pregunta = pr.pregunta)
								INNER JOIN sge_respuesta r ON (pr.respuesta = r.respuesta)
				WHERE		p.pregunta = ".kolla_db::quote($pregunta )."
				UNION 
 		        SELECT		0,
 		        			p.tabla_asociada,
			               	-1 AS respuesta,
			               	'' AS respuesta_valor
				FROM 		sge_pregunta p	               
				WHERE		p.pregunta = ".kolla_db::quote($pregunta)."
				AND			p.pregunta NOT IN (SELECT DISTINCT pregunta FROM sge_pregunta_respuesta)
		        ORDER BY 	1";
		return consultar_fuente($sql);
	}
	
	function get_opciones_respuesta_pregunta_multiple_tabla_asociada($id_encuesta, $pregunta, $tabla_asociada) 
	{
		$sql = "SELECT ed.pregunta,
				       p.nombre AS pregunta_nombre,
				       cp.componente as componente,
				       t.codigo AS respuesta,
				       t.nombre AS respuesta_valor
					FROM sge_encuesta_definicion ed
				       INNER JOIN sge_bloque b ON (ed.bloque = b.bloque)
				       INNER JOIN sge_pregunta p ON (ed.pregunta = p.pregunta)
				       INNER JOIN sge_componente_pregunta cp ON (cp.numero = p.numero),
				       $tabla_asociada t 
					WHERE 
						ed.encuesta = ".quote($id_encuesta)."
						AND (cp.componente = 'list' OR cp.componente='check')
						AND (cp.componente <> 'label')
						AND p.pregunta = ".quote($pregunta)."
						ORDER BY t.codigo";
		return consultar_fuente($sql);
	}

	function get_opciones_respuesta_pregunta_tabla_asociada($id_encuesta, $pregunta, $tabla_asociada)
	{
		$sql = "SELECT	DISTINCT t.codigo	AS respuesta,
						t.nombre 			AS respuesta_valor
				FROM   	sge_encuesta_definicion ed
							INNER JOIN sge_pregunta p ON (ed.pregunta = p.pregunta),
						$tabla_asociada t 
				WHERE	ed.encuesta = ".quote($id_encuesta)."
				AND		p.pregunta = ".quote($pregunta)."
				ORDER BY t.codigo";
		return consultar_fuente($sql);
	}

    function get_opciones_respuesta($pregunta)
    {
        if (isset($pregunta)) {
            $sql = "SELECT sr.respuesta,
                            sr.valor_tabulado                        
                    FROM   	sge_respuesta sr 
                            INNER JOIN sge_pregunta_respuesta spr ON (spr.respuesta = sr.respuesta)
                            INNER JOIN sge_pregunta sp ON (sp.pregunta = spr.pregunta AND sp.pregunta = $pregunta)
                    ORDER BY spr.orden 
                    ";
            return consultar_fuente($sql);
        }
    }

    function get_opciones_respuesta_tabla_asociada($tabla_asociada, $ta_codigo, $ta_descripcion)
    {
        $sql = "SELECT $ta_codigo	AS respuesta,
                       $ta_descripcion AS valor_tabulado
				FROM   	$tabla_asociada t 
				ORDER BY $ta_codigo";
        return consultar_fuente($sql);
    }
	
	/*
	 * Retorna la lista de respuestas con un filtro
	 */
	function get_respuestas($where = null, $order_by = null)
	{
		$where = isset($where) && $where != null ? ' WHERE '.$where : '';
		
		if (is_null($order_by)) {
			$order_by = ' valor_tabulado, respuesta';
		}
		
		$sql = "SELECT	tr.respuesta		AS respuesta,
						tr.valor_tabulado	AS valor_tabulado,
                        ug.nombre AS ug_nombre
				FROM 	sge_respuesta AS tr
                        LEFT OUTER JOIN sge_unidad_gestion AS ug ON (tr.unidad_gestion = ug.unidad_gestion)
				$where
				ORDER BY $order_by
				";
		return kolla_db::consultar($sql);
	}

	/*
	 * Retorna una respuesta a partir de un código o la lista de respuestas
	 */
	function get_respuestas_codigo($codigo=null)
	{
		if (!isset($codigo)) {
			return array();
		}
		
		$sql = "SELECT	tr.respuesta		AS respuesta, 
						tr.valor_tabulado	AS valor_tabulado,
						tr.valor_tabulado || ' - ' || tr.respuesta AS codigo_descripcion 
				FROM 	sge_respuesta	AS tr 
				WHERE	tr.respuesta::text ILIKE '%".$codigo."%'::text
				";
		$resultado = consultar_fuente($sql);
		
		if (!empty($resultado)) {
			return $resultado[0]['codigo_descripcion'];
		}
	}

	/*
	 * Retorna las respuestas para el combo editable
	 */
	static function get_respuestas_para_combo($codigo=null, $unidad_gestion=null)
	{
		if ( !isset($codigo) ) {
			return array();
		}
        
        $where = 'TRUE';
        
        if (isset($unidad_gestion)) {
            $where = 'tr.unidad_gestion = ' . kolla_db::quote($unidad_gestion);
        }
        
        $codigo = kolla_db::quote('%'.$codigo.'%');

		$sql = "SELECT      tr.respuesta							AS respuesta,
                            tr.valor_tabulado						AS valor_tabulado,
                            tr.valor_tabulado || ' - ' || tr.respuesta AS codigo_descripcion 
				FROM        sge_respuesta	AS tr 
				WHERE       (tr.respuesta::text ILIKE $codigo::text	OR tr.valor_tabulado::text ILIKE $codigo::text)
                AND         $where
				ORDER BY    respuesta DESC
				";

		$resultado = kolla_db::consultar($sql);
        
		if (!empty($resultado)) {
			return $resultado;
		}
	}

	/**
	 * Retorna la lista de preguntas con respuestas cerradas con un filtro 
	 */
	function get_preguntas_con_respuesta($where=null)
	{
		$where = isset($where) ? " AND $where" : '';
		
		$sql = "SELECT		p.pregunta, 
							p.nombre, 
							p.tabla_asociada,
							cp.descripcion,
							cp.numero,
			                p.unidad_gestion,
			                ug.nombre AS ug_nombre
				FROM 		sge_pregunta p
								LEFT OUTER JOIN sge_unidad_gestion AS ug ON (p.unidad_gestion = ug.unidad_gestion),
							sge_componente_pregunta cp
				WHERE		p.componente_numero = cp.numero
				AND			cp.componente IN ('radio', 'combo', 'list', 'check')
				AND			(p.tabla_asociada = '' OR p.tabla_asociada is null)
							$where
				ORDER BY 	p.pregunta
			";

		return kolla_db::consultar($sql);
	}
	
	/*
	 *	Retorna la lista de encuestas con un filtro y ordenada por encuesta
	 */
	function get_encuestas($where=null)
	{
		$where = isset($where) && $where != '' ? " WHERE $where" : '';
		
		$sql = "SELECT		sge_encuesta_atributo.encuesta		AS encuesta,
							sge_encuesta_atributo.nombre        AS nombre,
							sge_encuesta_atributo.descripcion	AS descripcion,
                            sge_encuesta_atributo.implementada,
							CASE
								WHEN sge_encuesta_atributo.estado = 'A' THEN 'Activa'
								WHEN sge_encuesta_atributo.estado = 'I' THEN 'Inactiva'
								WHEN sge_encuesta_atributo.estado = 'B' THEN 'Baja'
							ELSE
								'NULL'
							END         AS estado,
                            ug.nombre   AS ug_nombre
				FROM 		sge_encuesta_atributo
                                LEFT OUTER JOIN sge_unidad_gestion AS ug ON (sge_encuesta_atributo.unidad_gestion = ug.unidad_gestion)
				$where
				ORDER BY 	encuesta
				";
        
		return kolla_db::consultar($sql);
	}
    
    /*
	 *	Retorna la lista de encuestas con un filtro y ordenada por encuesta, lo
     *  devuelve con el formato: <codigo_encuesta> + " - " + <nombre_encuesta>
	 */
	function get_combo_encuestas($where=null)
	{
		$where = isset($where) && $where != '' ? " WHERE $where" : '';
		
		$sql = "SELECT		sge_encuesta_atributo.encuesta AS encuesta,
							sge_encuesta_atributo.encuesta || ' - ' || sge_encuesta_atributo.nombre	AS nombre
				FROM 		sge_encuesta_atributo
				$where
				ORDER BY 	encuesta
				";
        
		return kolla_db::consultar($sql);
	}
    
	/*
	 *	Retorna la lista de encuestas que se pueden copiar, filtrando por unidad de gestión
	 */
	function get_encuestas_copiar($ug=null)
	{
        $ug = kolla_db::quote($ug);
		$where = isset($ug) ? " WHERE ug.unidad_gestion = $ug " : '';
		
		$sql = "SELECT		sge_encuesta_atributo.encuesta		AS encuesta,
							sge_encuesta_atributo.nombre		AS nombre,
							sge_encuesta_atributo.descripcion	AS descripcion,
							CASE
								WHEN sge_encuesta_atributo.estado = 'A' THEN 'Activa'
								WHEN sge_encuesta_atributo.estado = 'I' THEN 'Inactiva'
								WHEN sge_encuesta_atributo.estado = 'B' THEN 'Baja'
							ELSE
								'NULL'
							END	AS estado,
                            ug.nombre AS ug_nombre
				FROM 		sge_encuesta_atributo
                            JOIN sge_unidad_gestion AS ug ON (sge_encuesta_atributo.unidad_gestion = ug.unidad_gestion)
				$where
				ORDER BY 	encuesta
				";
		
		return kolla_db::consultar($sql);
	}
	
	function get_encuestas_ug($ug=null){
		$ug = kolla_db::quote($ug);
		$where = isset($ug) ? " ug.unidad_gestion = $ug " : '1=1';
		
		$sql = "SELECT		sge_encuesta_atributo.encuesta AS encuesta,
		sge_encuesta_atributo.encuesta || ' - ' || sge_encuesta_atributo.nombre	AS nombre
		FROM 		sge_encuesta_atributo
						JOIN sge_unidad_gestion AS ug ON (sge_encuesta_atributo.unidad_gestion = ug.unidad_gestion)
		WHERE sge_encuesta_atributo.implementada='S' AND $where
		ORDER BY 	encuesta
		";
                
                return kolla_db::consultar($sql);
	}
	    
	function get_encuesta($encuesta)
	{
		$encuesta = kolla_db::quote($encuesta);
		
		$sql = "
			SELECT
				encuesta,
				nombre
			FROM 
				sge_encuesta_atributo
			WHERE
				encuesta = $encuesta
		";
		
		return kolla_db::consultar_fila($sql);
	}
	
	/**
	 *Obtiene las encuestas que no estan dadas de baja 
	 */
	function get_encuestas_no_bajas($where = null)
	{
		if ($where != null) {
			$where .= " AND estado != 'B'";
		} else {
			$where = "estado != 'B'";
		}
		
		return $this->get_encuestas($where);
	}
	
	/**
	 * Encuestas segun el estado
	 * @param type $bajas
	 * @return type 
	 */
	function get_encuestas_estado($estado)
	{
		return $this->get_encuestas("estado = '$estado'");
	}

	/*
	Retorna la lista de habilitaciones con encuestas asociadas (todas, no solo de las encuestas activas)
	*/
	//VER PARA SINCRONIZAR CON EL GET_HABILITACIONES DE CONSULTAS_ENCUESTAS_EXTERNAS
	function get_habilitaciones($where=null)
	{
		$where = isset($where) ? " AND ".$where : '';
		$sql = "SELECT
					eh.habilitacion							AS habilitacion,
					eh.encuesta 							AS encuesta,
					ea.nombre								AS nombre,
					eh.fecha_desde							AS fecha_desde,
					eh.fecha_hasta							AS fecha_hasta,
					(substr(fecha_desde::text,9,2)||'/'||substr(fecha_desde::text,6,2)||'/'||
						substr(fecha_desde::text,1,4)||' - '|| substr(fecha_hasta::text,9,2)||'/'||
						substr(fecha_hasta::text,6,2)||'/'||substr(fecha_hasta::text,1,4)) 	AS fechas
				FROM 
					sge_encuesta_atributo ea, 
					sge_habilitacion eh
				WHERE 
					ea.encuesta = eh.encuesta
					$where
				ORDER BY nombre
		";		
		return consultar_fuente($sql);
	}
	
	/*
	 * Retorna la lista de habilitaciones con formularios asociados (todos, no solo de los formularios activos)
	 */
	function get_habilitaciones_where($where = null)
	{
		$where = isset($where) ? ' AND '.$where : '';
		
		$sql = "SELECT		sge_habilitacion.habilitacion	AS habilitacion,
							sge_habilitacion.fecha_desde	AS fecha_desde,
							sge_habilitacion.fecha_hasta	AS fecha_hasta,
							(substr(fecha_desde::text,9,2) || '/'   || substr(fecha_desde::text,6,2) || '/' ||
							 substr(fecha_desde::text,1,4) || ' - ' || substr(fecha_hasta::text,9,2) || '/' ||
							 substr(fecha_hasta::text,6,2) || '/'   || substr(fecha_hasta::text,1,4)) AS fechas
				FROM 		sge_habilitacion,
							sge_formulario_habilitado,
							sge_formulario_habilitado_detalle,
							sge_formulario_definicion
				WHERE		sge_formulario_definicion.encuesta = sge_formulario_habilitado_detalle.encuesta
				AND			sge_formulario_habilitado_detalle.formulario_habilitado = sge_formulario_habilitado.formulario_habilitado
				AND			sge_formulario_habilitado.habilitacion = sge_habilitacion.habilitacion
				ORDER BY 	sge_habilitacion.habilitacion
				";
		
		return consultar_fuente($sql);
	}	

	/*
	 *	Retorna la lista de encuestas con un filtro
	 */
	function get_encuesta_habilitada($where = null)
	{
		$where = ($where != null) ? "WHERE $where" : '';
		
		$sql = "SELECT		sge_habilitacion.habilitacion	AS habilitacion,
							sge_habilitacion.encuesta 		AS encuesta,
							sge_habilitacion.externa		AS externa,
							sge_encuesta_atributo.nombre	AS nombre,
							CASE
								WHEN sge_encuesta_atributo.estado = 'A' THEN 'Activa'
								WHEN sge_encuesta_atributo.estado = 'I' THEN 'Inactiva'
								WHEN sge_encuesta_atributo.estado = 'B' THEN 'Baja'
							ELSE
								'NULL'
							END	AS estado,
							(substr(fecha_desde::text,9,2) || '/' || substr(fecha_desde::text,6,2) || '/' || substr(fecha_desde::text,1,4)) AS fecha_desde,
							(substr(fecha_hasta::text,9,2) || '/' || substr(fecha_hasta::text,6,2) || '/' || substr(fecha_hasta::text,1,4)) AS fecha_hasta,
							(SELECT COUNT(*)
							 FROM sge_encuestas_terminada INNER JOIN sge_encuestas_realizada_encabezado ON (sge_encuestas_terminada.encuesta_encabezado = sge_encuestas_realizada_encabezado.encuesta_encabezado)
							 WHERE sge_encuestas_realizada_encabezado.habilitacion = sge_habilitacion.habilitacion) AS contador
				FROM 		sge_encuesta_atributo
								INNER JOIN sge_habilitacion ON (sge_encuesta_atributo.encuesta = sge_habilitacion.encuesta)
				$where
				ORDER BY 	estado ASC,
							sge_habilitacion.fecha_hasta DESC,
							habilitacion
				";

		return consultar_fuente($sql);
	}
	
	/**
	 *Obtiene las encuestas habilitadas que no estan dadas de baja 
	 */
	function get_encuesta_habilitada_no_bajas($where = null)
	{
		if($where != null) {
			$where .= " AND estado != 'B'";
		}else $where = "estado != 'B'";
		return $this->get_encuesta_habilitada($where);
	}
	
	/**
	 * Retorna la lista de encuestas que se habilitaron para completar con un filtro
	 */
	function get_encuestas_definicion($filtro=null)
	{
		$where = array('TRUE');
		
		if ( isset($filtro) ) {
			if ( isset($filtro['encuesta']) ) {
				$where[] = 'ea.encuesta = '.kolla_db::quote($filtro['encuesta']);
			}
		}
		
		$where = implode(' AND ', $where);
		
		$sql = "
			SELECT
				ed.encuesta		AS encuesta, 
				ea.nombre		AS encuesta_nombre,
				ed.orden		AS pregunta_orden,
				ed.bloque		AS bloque,
				ed.orden		AS bloque_orden,
				b.nombre		AS bloque_nombre,
				ed.pregunta		AS pregunta,
				p.nombre		AS pregunta_nombre,
				ed.obligatoria	AS obligatoria
			FROM 
				sge_encuesta_definicion ed,
				sge_encuesta_atributo ea,
				sge_bloque b,
				sge_pregunta p
			WHERE 
				ed.bloque = b.bloque AND
				ed.pregunta = p.pregunta AND
				ed.encuesta = ea.encuesta AND
				$where
			ORDER BY 
				bloque_orden,
				pregunta_orden
		";
		
		return kolla_db::consultar($sql);
	}

	/*
		Retorna los datos de una habilitacion determinada que viene como parametro
		*/
	function get_datos_habilitacion($filtro=null)
	{
		$where = '';
		if (isset($filtro)) {
			$where = 'WHERE eh.habilitacion = '.kolla_db::quote($filtro);
		}
		$sql = "SELECT
					eh.habilitacion,
					eh.fecha_desde,
					eh.fecha_hasta,
					eh.paginado,
					eh.externa,
					eh.anonima,
					eh.sistema
				FROM 
					sge_habilitacion eh 
					$where
				";
		return consultar_fuente($sql);
	}
		
	/**
	 Retorna todos los componente_pregunta para llenar combo de estilos de pregunta
	 */
	function get_componentes_preguntas()
	{
		$sql = "SELECT      sge_componente_pregunta.numero,
							sge_componente_pregunta.descripcion
				FROM    	sge_componente_pregunta
                WHERE       sge_componente_pregunta.numero <> '".self::COMBO_DINAMICO."'
				ORDER BY	tipo,
							descripcion";
		
		return consultar_fuente($sql);
	}
    
    function get_componente_pregunta($pregunta)
    {
        $pregunta = toba::db()->quote($pregunta);
                
        $sql = "SELECT  sge_componente_pregunta.*,
                        sge_pregunta.tabla_asociada,
                        sge_pregunta.tabla_asociada_codigo,
                        sge_pregunta.tabla_asociada_descripcion,
                        sge_pregunta.tabla_asociada_orden_campo,
                        sge_pregunta.tabla_asociada_orden_tipo
				FROM    sge_componente_pregunta
                        JOIN sge_pregunta ON sge_pregunta.componente_numero = sge_componente_pregunta.numero
                WHERE   pregunta = $pregunta";
        
        return kolla_db::consultar_fila($sql);
    }
	
	function get_lista_tablas($unidad_gestion)
	{
        $schema_kolla = toba::db()->get_schema();
        $unidad_gestion = kolla_db::quote($unidad_gestion);
        
		$sql = "SELECT  tablename AS nombre
                FROM    pg_tables
                            INNER JOIN sge_tabla_asociada ON pg_tables.tablename = sge_tabla_asociada.tabla_asociada_nombre
                WHERE   tablename LIKE 'ta_%'
                AND     schemaname = '$schema_kolla'
                AND     sge_tabla_asociada.unidad_gestion = $unidad_gestion
                
                UNION
                
                SELECT  tablename AS nombre
                FROM    pg_tables
                            INNER JOIN sge_tabla_externa ON pg_tables.tablename = sge_tabla_externa.tabla_externa_nombre
                WHERE   schemaname = '$schema_kolla'
                AND     sge_tabla_externa.unidad_gestion = $unidad_gestion
                ";
        
        return kolla_db::consultar($sql);
	}
    
    function get_lista_tablas_esquema_kolla()
    {
        $schema_kolla = toba::db()->get_schema();
        
        $sql = "SELECT      tablename AS nombre
                FROM        pg_tables
                WHERE       tablename NOT LIKE 'ta_%'
                AND         tablename NOT LIKE 'sge_%'
                AND         schemaname = '$schema_kolla'
                ORDER BY    nombre
                ";
        
        return kolla_db::consultar($sql);
    }

	/*
		Retorna la descripcion de un bloque dado su codigo
		*/
	function get_bloque_codigo($codigo=null)
	{
		if (!isset($codigo)) {
			return array();
		} else {
			$sql = "SELECT
						bloque, 
						CASE WHEN length(nombre)>=70 THEN substr(nombre,0,70) || ' ...' 
						     ELSE nombre 
						END	as nombre,
						CASE WHEN length(nombre)>=70 THEN substr(nombre,0,70) || ' ... - [' || bloque || ']'  
						     ELSE nombre || ' - [' || bloque || ']'
						END	as nombre_codigo
					FROM sge_bloque 
					WHERE
						bloque::text ILIKE '".$codigo."%'::text 
						OR
						nombre ILIKE '%".$codigo."%'::text
					ORDER BY nombre_codigo, bloque
			";

			$resultado = consultar_fuente($sql);
			
			if (!empty($resultado)) {
				return $resultado[0]['nombre_codigo'];
			}
		}
	}

	/*
		Retorna la lista de bloques con nombre reducido para combo
		*/
	function get_bloques_para_combo($codigo=null)
	{
		if ( isset($codigo) )	{
			$where = " WHERE bloque::text ILIKE '".$codigo."%'::text
						OR nombre ILIKE '%".$codigo."%'::text ";
		} else {
			$where = "";
		}
		$sql = "SELECT
					bloque, 
					CASE WHEN length(nombre)>=70 THEN substr(nombre,0,70) || ' ...' 
					     ELSE nombre 
					END	as nombre,
					CASE WHEN length(nombre)>=70 THEN substr(nombre,0,70) || ' ... - [' || bloque || ']'   
					     ELSE nombre || ' - [' || bloque || ']'
					END	as nombre_codigo
				FROM sge_bloque
				$where
				ORDER BY nombre_codigo, bloque
		";
					
		$resultado = consultar_fuente($sql);
		
		if (!empty($resultado)) {
			return $resultado;
		}
	}

	/**
	 * Retorna la descripcion de una pregunta dado su codigo
	*/
	function get_pregunta_codigo($codigo=null)
	{
		$where = array('TRUE');
		
		if (isset($codigo)) {
			$codigo = kolla_db::quote($codigo);
			$where[] = "pregunta = $codigo";
		}
		
		$where = implode(' AND ', $where);

		$sql = "
			SELECT      pregunta,
                        CASE WHEN length(nombre)>=70 THEN substr(nombre,0,70) || ' ...' 
                             ELSE nombre
                        END as nombre,
                        CASE WHEN length(nombre)>=70 THEN substr(nombre,0,70) || ' ... - [' ||  cp.descripcion || ']'  
                             ELSE nombre || ' - [' ||  cp.descripcion || ']'
                        END as nombre_tipo,
                        CASE WHEN length(nombre)>=70 THEN substr(nombre,0,70) || ' ...  - [' || pregunta || ']'  
                             ELSE nombre || ' - [' || pregunta || '] - [' ||  cp.descripcion || ']'
                        END as nombre_codigo
			FROM 		sge_pregunta p 
                            INNER JOIN sge_componente_pregunta cp ON (p.componente_numero = cp.numero)
			WHERE		$where
			AND         p.componente_numero != '22'
            
            UNION
            
            SELECT      pregunta,
                        CASE WHEN length(nombre)>=70 THEN substr(nombre,0,70) || ' ...' 
                             ELSE nombre
                        END as nombre,
                        CASE WHEN length(nombre)>=70 THEN substr(nombre,0,70) || ' ... - [' ||  cp.descripcion || ']'  
                             ELSE nombre || ' - [' ||  cp.descripcion || ']'
                        END as nombre_tipo,
                        descripcion_resumida || ' (desc. resumida) - [' || pregunta || '] - [' ||  cp.descripcion || ']' as nombre_codigo
			FROM 		sge_pregunta p 
                            INNER JOIN sge_componente_pregunta cp ON (p.componente_numero = cp.numero)
			WHERE		$where
			AND         p.componente_numero = '22'

            ORDER BY 	nombre, 
                        nombre_codigo, 
                        nombre_tipo
		";
				
		$resultado = kolla_db::consultar_fila($sql);
		
		if (!empty($resultado)) {
			return $resultado['nombre_codigo'];
		}
	}

	/*
		Retorna la lista de preguntas con nombre reducido para combo
		*/
	function get_preguntas_para_combo($codigo=null, $unidad_gestion=null)
	{
        $where = array("p.oculta = 'N'");
        $where_texto_enriquecido = $where;
                
		if (isset($codigo)) {
            $codigo = kolla_db::quote('%'.$codigo.'%');
			$where[] = "(pregunta::text ILIKE $codigo::text OR nombre ILIKE $codigo::text)";
            $where_texto_enriquecido[] = "(pregunta::text ILIKE $codigo::text OR descripcion_resumida ILIKE $codigo::text)";
		}
        
        if (isset($unidad_gestion)) {
            $where[] = 'p.unidad_gestion = ' . kolla_db::quote($unidad_gestion);
            $where_texto_enriquecido[] = 'p.unidad_gestion = ' . kolla_db::quote($unidad_gestion);
        }

        $where = implode(' AND ', $where);
        $where_texto_enriquecido = implode(' AND ', $where_texto_enriquecido);
                
		$sql = "
			SELECT      pregunta,
                        CASE WHEN length(nombre)>=70 THEN substr(nombre,0,70) || ' ...' 
                             ELSE nombre
                        END as nombre,
                        CASE WHEN length(nombre)>=70 THEN substr(nombre,0,70) || ' ... - [' ||  cp.descripcion || ']'  
                             ELSE nombre || ' - [' ||  cp.descripcion || ']'
                        END as nombre_tipo,
                        CASE WHEN length(nombre)>=70 THEN substr(nombre,0,70) || ' ...  - [' || pregunta  || ']' 
                             ELSE nombre || ' - [' || pregunta  || ']'
                        END as nombre_codigo,
                        CASE WHEN length(nombre)>=70 THEN substr(nombre,0,70) || ' ...  - [' || pregunta || '] - [' ||  cp.descripcion || ']'  
                             ELSE nombre || ' - [' || pregunta || '] - [' ||  cp.descripcion || ']'
                        END as nombre_codigo_tipo
			FROM        sge_pregunta p 
                            INNER JOIN sge_componente_pregunta cp ON (p.componente_numero = cp.numero)
            WHERE       $where
            AND         p.componente_numero != '22'
			
            UNION
            
            SELECT      pregunta,
                        CASE WHEN length(nombre)>=70 THEN substr(nombre,0,70) || ' ...' 
                             ELSE nombre
                        END as nombre,
                        CASE WHEN length(nombre)>=70 THEN substr(nombre,0,70) || ' ... - [' ||  cp.descripcion || ']'  
                             ELSE nombre || ' - [' ||  cp.descripcion || ']'
                        END as nombre_tipo,
                        CASE WHEN length(nombre)>=70 THEN substr(nombre,0,70) || ' ...  - [' || pregunta  || ']' 
                             ELSE nombre || ' - [' || pregunta  || ']'
                        END as nombre_codigo,
                        descripcion_resumida || ' (desc. resumida) - [' || pregunta || '] - [' ||  cp.descripcion || ']' as nombre_codigo_tipo
			FROM        sge_pregunta p 
                            INNER JOIN sge_componente_pregunta cp ON (p.componente_numero = cp.numero)
            WHERE       $where_texto_enriquecido
            AND         p.componente_numero = '22'
            
			ORDER BY 	nombre, 
                        nombre_codigo, 
                        nombre_tipo
			";

        $resultado = kolla_db::consultar($sql);

		if (!empty($resultado)) {
			return $resultado;
		}
	}

	function get_estilos($where=array(), $single=false)
	{
		$where = abm::get_where($where);

		$sql = "
            SELECT
                ee.estilo		AS estilo,
                ee.nombre		AS nombre,
                ee.descripcion	AS descripcion,
                ee.archivo		AS archivo
            FROM
                sge_encuesta_estilo	AS ee
            WHERE
                $where
		";

        if ( $single ) {
            return kolla_db::consultar_fila($sql);
        } else {
            return kolla_db::consultar($sql);
        }
		
	}
	
	function get_encuestas_habilitadas_hoy($externa=null)
	{ //INTEGRAR CON LAS OTRAS CONSULTAS DE HABILITACION SI SE PUEDE
		$es_externa = '';
		if (isset($externa)) {
			$es_externa = ' AND eh.externa = ';
			$es_externa .= ($externa) ? " 'S' " : " 'N' "; 
		}
		$sql = "SELECT
					eh.habilitacion, 
					eh.encuesta, 
					ea.nombre, 
					eh.fecha_desde, 
					eh.fecha_hasta,
					ea.descripcion,
					(substr(fecha_desde::text,9,2)||'/'||substr(fecha_desde::text,6,2)||'/'||substr(fecha_desde::text,1,4)) AS desde,
					(substr(fecha_hasta::text,9,2)||'/'||substr(fecha_hasta::text,6,2)||'/'||substr(fecha_hasta::text,1,4)) AS hasta
				FROM 
					sge_encuesta_atributo ea, 
					sge_habilitacion eh 
				WHERE 
					ea.encuesta = eh.encuesta 
					AND current_date BETWEEN eh.fecha_desde AND eh.fecha_hasta
					$es_externa
		";
		return consultar_fuente($sql);
	}

	function get_tipodoc($tipodoc=null)
	{
		$where = isset($tipodoc) ? ' WHERE documento_tipo = '.kolla_db::quote($tipodoc) : '';
		
		$sql = "SELECT	documento_tipo,
						descripcion
				FROM	sge_documento_tipo
				$where
				";
				
		return consultar_fuente($sql);
	}
	
	/**
	 Retorna los bloques que hay definidos en una encuesta
	 */
	function get_bloques_encuesta_combo($encuesta)
	{
		$encuesta = kolla_db::quote($encuesta);
		
		$sql = "
			SELECT
				sb.bloque		AS bloque,
				sb.nombre		AS nombre,
				sb.descripcion	AS descripcion
			FROM 
				sge_bloque AS sb
				INNER JOIN sge_encuesta_definicion sed ON (sed.bloque = sb.bloque)
			WHERE 
				sed.encuesta = $encuesta
			ORDER BY 
				bloque
		";
		
		return consultar_fuente($sql);
	}

	/* 
	 Chequea si la respuesta a una encuesta (formulario) posee al menos una respuesta moderada
	 */
	function es_encuesta_moderada($form_real_encab=null, $codigo_recuperacion=null) 
	{
		$where = '';
		$where .= (isset($form_real_encab)) ? " AND sfre.formulario_encabezado = ".$form_real_encab : '';
		$where .= (isset($codigo_recuperacion)) ? " AND sfre.codigo_recuperacion = ".$codigo_recuperacion : '';
		$sql = "SELECT DISTINCT
					sfre.formulario_encabezado,
					sfre.codigo_recuperacion,
					count(serv.moderada) as cantidad_moderadas
				FROM sge_formulario_realizado_encabezado sfre
						INNER JOIN sge_encuestas_realizada_encabezado sere ON (sfre.formulario_encabezado = sere.formulario_encabezado)
						INNER JOIN sge_encuestas_realizada_valores serv ON (sere.encuesta_encabezado = serv.encuesta_encabezado)
				WHERE serv.moderada = true
					$where
				GROUP BY sfre.formulario_encabezado, sfre.codigo_recuperacion, serv.moderada
				ORDER BY sfre.formulario_encabezado
				";
		$rs = consultar_fuente($sql);
		return $rs;
	}
	
	/*
	 	Chequea si se trata de una habilitación anónima
	*/
	function es_habilitacion_anonima($habilitacion)
	{
		$habilitacion = kolla_db::quote($habilitacion);
		
		$sql = "SELECT
					eh.habilitacion		AS habilitacion,
					eh.anonima			AS anonima
				FROM
					sge_habilitacion eh
				WHERE
					eh.habilitacion = $habilitacion
				";
		
		$res = consultar_fuente($sql);
		return $res[0]['anonima'];
	}
	
	/*
	 * Chequea si una encuesta dada tiene alguna respuesta.
	 */
	function tiene_respuestas_encuesta($id_encuesta)
	{
		$id_encuesta = kolla_db::quote($id_encuesta);
		
		$sql = "SELECT EXISTS
				(
					SELECT	1
					FROM  	sge_respondido_formulario
								INNER JOIN sge_formulario_habilitado_detalle ON sge_formulario_habilitado_detalle.formulario_habilitado = sge_respondido_formulario.formulario_habilitado
					WHERE 	sge_formulario_habilitado_detalle.encuesta = $id_encuesta
				) AS rta";
		
		$tiene_rtas = kolla_db::consultar_fila($sql);
		return $tiene_rtas['rta'];
	}
	
	/*
	 * Chequea si una habilitación dada tiene alguna respuesta.
	 */
	function tiene_respuestas_habilitacion($id_hab)
	{
		$sql = "SELECT EXISTS (
					SELECT 
					  1
					FROM  
						sge_respondido_formulario rf 
					INNER JOIN 
						sge_formulario_habilitado fh
					ON fh.formulario_habilitado = rf.formulario_habilitado
					WHERE 
						fh.habilitacion = $id_hab)
				AS rta";
		$tiene_rtas = kolla_db::consultar_fila($sql);
		return $tiene_rtas['rta'];
	}
	
	/**
	 * Determina si la encuesta se usa en alguna habilitacion
	 * @param type $id_encuesta
	 * @return type
	 */
	function tiene_habilitaciones_encuesta($id_encuesta)
	{
		return abm::existen_registros('sge_formulario_habilitado_detalle', array('encuesta' => $id_encuesta));
	}
	
	/**
	 * Testea que no haya una habilitación que termina después de hoy. Son las en curso
	 * y las futuras.
	 * @param type $id_encuesta
	 * @return type 
	 */
	function tiene_habilitaciones_pendientes($id_encuesta)
	{
		$sql = "SELECT EXISTS  (
			SELECT 	1
			  FROM 
				sge_formulario_habilitado_detalle fhd, 
				sge_habilitacion h, 
				sge_formulario_habilitado fh
			  WHERE 
				fhd.formulario_habilitado = fh.formulario_habilitado AND
				fh.habilitacion = h.habilitacion
			  
			  AND encuesta = $id_encuesta
			  AND h.fecha_hasta  >= now()
			  )
			as rta";
		$tiene_habs = kolla_db::consultar_fila($sql);
		return $tiene_habs['rta'];
	}
	
	/*
	 * Chequea si un número ya esta usado para una encuesta dada.
	 */
	function existe_numero_en_encuesta($numero, $id_encuesta)
	{
		return abm::existen_registros('sge_encuesta_definicion', array('encuesta' => $id_encuesta, 'numero' => $numero));
	}
	
	function get_encuesta_x_habilitacion($encuesta)
	{
		$sql = '
			SELECT
				sh.descripcion AS desc_habilitacion,
				sfh.nombre AS desc_formulario,
				ste.descripcion AS desc_tipo_elemento,
				sea.nombre AS desc_encuesta,
				sc.descripcion AS desc_concepto
			FROM 
				sge_formulario_habilitado sfh
				INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sfh.formulario_habilitado = sfhd.formulario_habilitado)
				INNER JOIN sge_habilitacion sh ON (sfh.habilitacion = sh.habilitacion)
				LEFT OUTER JOIN sge_tipo_elemento AS ste ON (sfhd.tipo_elemento = ste.tipo_elemento)
				LEFT OUTER JOIN sge_concepto AS sc ON (sfh.concepto = sc.concepto)
				INNER JOIN sge_encuesta_atributo sea ON (sfhd.encuesta = sea.encuesta)
			WHERE
                sfhd.encuesta = '.kolla_db::quote($encuesta);
		
		return kolla_db::consultar_fila($sql);
	}
    
    function get_encuestas_habilitacion ($habilitacion)
    {
        $habilitacion = kolla_db::quote($habilitacion);
        
        $sql = "SELECT DISTINCT sh.habilitacion,
                                sh.descripcion,
                                slfdh.encuesta,
                                ste.descripcion as desc_tipo_elemento,
                                sea.nombre as nombre
                FROM sge_habilitacion sh, 
                    sge_log_formulario_definicion_habilitacion slfdh
                    LEFT JOIN sge_tipo_elemento ste ON (slfdh.tipo_elemento = ste.tipo_elemento), 
                    sge_encuesta_atributo sea 
                WHERE sh.habilitacion = slfdh.habilitacion
                    AND slfdh.encuesta = sea.encuesta
                    AND sh.habilitacion = $habilitacion
                ORDER BY slfdh.encuesta";
        
        return kolla_db::consultar($sql);
    }
    
    function get_encuestas_de_habilitacion ($habilitacion)
    {
        $habilitacion = kolla_db::quote($habilitacion);
        
        $sql = "SELECT DISTINCT sh.habilitacion,
                            sh.descripcion,
                            sea.encuesta as encuesta,
                            sea.nombre as nombre
                FROM sge_habilitacion sh 
                        INNER JOIN sge_formulario_habilitado sfh ON (sh.habilitacion = sfh.habilitacion)
                        INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sfhd.formulario_habilitado = sfh.formulario_habilitado)
                        INNER JOIN sge_encuesta_atributo sea ON (sea.encuesta = sfhd.encuesta)
                WHERE sh.habilitacion =  $habilitacion ;";
        
        return kolla_db::consultar($sql);
    }
    
    function get_encuestas_formulario_habilitado($formulario_habilitado=null)
    {
    	$where = $formulario_habilitado ? 'WHERE sfh.formulario_habilitado = '.kolla_db::quote($formulario_habilitado) : '';
		
        $sql = "
			SELECT
				sfh.formulario_habilitado,
				sfh.habilitacion,
				sfhd.formulario_habilitado_detalle,
				sfhd.encuesta,
				sfhd.elemento,
				sfhd.tipo_elemento,
				ste.descripcion AS desc_tipo_elemento,
				sfhd.orden,
				sea.nombre,
				sea.descripcion,
				sea.implementada,
				sea.estado,
				sea.texto_preliminar
			FROM 
				sge_formulario_habilitado sfh
				INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sfh.formulario_habilitado = sfhd.formulario_habilitado)
				LEFT OUTER JOIN sge_tipo_elemento AS ste ON (sfhd.tipo_elemento = ste.tipo_elemento)
				INNER JOIN sge_encuesta_atributo sea ON (sfhd.encuesta = sea.encuesta)	
				$where
		";
        return kolla_db::consultar($sql);
    }    
    
    function es_pregunta_no_editable($pregunta)
    {
        //si esta en el rango de reservadas no es editable
        $reservada = false;
        $en_uso = false;
        if ($pregunta <= $this->pregunta_reservada_max) {
            $reservada = true;
        }
        //si esta en uso en alguna habilitacion no es editable
        $sql = "SELECT count(sed.pregunta)
                FROM sge_formulario_habilitado_detalle sfhd 
                    INNER JOIN sge_encuesta_definicion sed 
                        ON (sfhd.encuesta = sed.encuesta)
                WHERE sed.pregunta = ".$pregunta.";";
        $res = kolla_db::consultar_fila($sql);
        if ($res['count'] > 0) {
            $en_uso = true;
        }        
        return ($reservada || $en_uso);
    }
  
    function es_respuesta_no_editable($respuesta)
    {
        //si esta en el rango de reservadas no es editable
        $reservada = false;
        $en_uso = false;
        if ($respuesta <= $this->respuesta_reservada_max) {
            $reservada = true;
        }
        //si esta en uso en alguna habilitacion no es editable
        $sql = "SELECT count(distinct sed.pregunta)
                FROM sge_formulario_habilitado_detalle sfhd 
                    INNER JOIN sge_encuesta_definicion sed ON (sfhd.encuesta = sed.encuesta)
                    INNER JOIN sge_pregunta_respuesta spr ON (sed.pregunta = spr.pregunta)
                WHERE spr.respuesta = ".$respuesta.";";
        $res = kolla_db::consultar_fila($sql);
        if ($res['count'] > 0) {
            $en_uso = true;
        }        
        return ($reservada || $en_uso);
    }  
    
    function es_bloque_no_editable($bloque)
    {
        //si esta en el rango de reservadas no es editable
        $reservada = false;
        $en_uso = false;
        if ($bloque <= $this->bloques_reservada_max) {
            $reservada = true;
        }
        //si esta en uso en alguna habilitacion no es editable
        $sql = "SELECT count(distinct sb.bloque)
                FROM sge_formulario_habilitado_detalle sfhd 
                    INNER JOIN sge_encuesta_definicion sed ON (sfhd.encuesta = sed.encuesta)
                    INNER JOIN sge_bloque sb ON (sed.bloque = sb.bloque)	
                WHERE sb.bloque = ".$bloque.";";
        $res = kolla_db::consultar_fila($sql);
        if ($res['count'] > 0) {
            $en_uso = true;
        }        
        return ($reservada || $en_uso);
    } 
    
    function es_encuesta_no_editable($encuesta)
    {
        //Si la encuesta está en el rango de reservadas no es editable
        if ($encuesta <= $this->encuesta_reservada_max) {
            return true;
        }
        
        //Sino, si está implementada no es editable
        $encuesta_implementada = $this->get_implementada_encuesta($encuesta);
 	
        if ($encuesta_implementada == 'S') { 
            return true;
        }
        
        //Sino, si está en uso en alguna habilitación con fecha desde menor o igual a hoy no es editable
        $encuesta = kolla_db::quote($encuesta);
        $sql = "SELECT	COUNT(DISTINCT sge_formulario_habilitado_detalle.encuesta)
                FROM 	sge_formulario_habilitado_detalle
                            INNER JOIN sge_formulario_habilitado ON (sge_formulario_habilitado_detalle.formulario_habilitado = sge_formulario_habilitado.formulario_habilitado)
                            INNER JOIN sge_habilitacion ON (sge_formulario_habilitado.habilitacion = sge_habilitacion.habilitacion)
                WHERE 	sge_formulario_habilitado_detalle.encuesta = $encuesta
                AND     sge_habilitacion.fecha_desde <= current_date";
        
		$res = kolla_db::consultar_fila($sql);
		if ($res['count'] > 0) {
			return true;
		}
        
        //Caso contrario, la encuesta es editable
        return false;
    }
    
    function encuesta_incluida_en_formulario_habilitado($encuesta)
    {
        $encuesta = kolla_db::quote($encuesta);
        
        $sql = "SELECT	COUNT(DISTINCT sge_formulario_habilitado_detalle.encuesta)
                FROM 	sge_formulario_habilitado_detalle
                            INNER JOIN sge_formulario_habilitado ON (sge_formulario_habilitado_detalle.formulario_habilitado = sge_formulario_habilitado.formulario_habilitado)
                            INNER JOIN sge_habilitacion ON (sge_formulario_habilitado.habilitacion = sge_habilitacion.habilitacion)
                WHERE 	sge_formulario_habilitado_detalle.encuesta = $encuesta
                AND     sge_habilitacion.fecha_desde <= current_date";
        
		$res = kolla_db::consultar_fila($sql);
		
        if ($res['count'] > 0) {
			return true;
		} else {
            return false;
        }
    }
    
    function validar_eliminacion_encuesta($encuesta)
    {
    	//Si el usuario no tiene perfil administrador no puede eliminar la encuesta
    	if (!toba::consulta_php('consultas_usuarios')->es_admin_actual()) {
    		return false;
    	}
    	
    	//Si la encuesta ya tiene respuestas no puede eliminarla
    	if ($this->tiene_respuestas_encuesta($encuesta)) {
    		return false;
    	}
    	
    	//Sino, si la encuesta no está implementada se puede eliminar
    	$encuesta = kolla_db::quote($encuesta);
    	
    	$sql = "SELECT	sge_encuesta_atributo.implementada
	            FROM 	sge_encuesta_atributo
	            WHERE 	sge_encuesta_atributo.encuesta = $encuesta
				";
        
		$res = kolla_db::consultar_fila($sql);
		return $res['implementada'] == 'N' ? true : false;
    }
    
	function clonar_bloque($id_bloque)
    {
    	$consulta_bloque = "SELECT	sge_bloque.*
        					FROM 	sge_bloque
        					WHERE 	sge_bloque.bloque = $id_bloque";
        
        $datos_bloque = kolla_db::consultar_fila($consulta_bloque);
        $datos_bloque = kolla_db::quote($datos_bloque);
        
		$this->modelo_act = kolla::abm('act_encuestas');
		$this->modelo_act->insertar_bloque($datos_bloque['nombre'], $datos_bloque['descripcion'], $datos_bloque['orden']);
		
        $bloque_nuevo = kolla_db::consultar_fila("SELECT CURRVAL('sge_bloque_seq')");
        return $bloque_nuevo['currval'];
    }
    
    function get_encuesta_definicion($encuesta)
    {
    	$encuesta = kolla_db::quote($encuesta);
		
		$sql = "SELECT	sge_encuesta_definicion.*
				FROM	sge_encuesta_definicion
				WHERE	sge_encuesta_definicion.encuesta = $encuesta
				";
		
		return kolla_db::consultar($sql);
    }
    
    function get_implementada_encuesta($encuesta)
    {
        $encuesta = kolla_db::quote($encuesta);
		
		$sql = "SELECT	sge_encuesta_atributo.*
				FROM	sge_encuesta_atributo
				WHERE	sge_encuesta_atributo.encuesta = $encuesta
				";
		
		$datos = kolla_db::consultar_fila($sql);
        return $datos['implementada'];
    }
    
    function get_encuestas_predefinidas()
    {
        $sql = "SELECT	sge_encuesta_atributo.encuesta
				FROM	sge_encuesta_atributo
				WHERE	sge_encuesta_atributo.encuesta <= ".$this->encuesta_reservada_max;
				;
		
		return kolla_db::consultar($sql);
    }
    
    function get_preguntas_encuesta($encuesta)
	{
        $encuesta = kolla_db::quote($encuesta);
        
		$sql = "SELECT  sge_encuesta_definicion.encuesta,
                        sge_encuesta_definicion.bloque,
                        sge_encuesta_definicion.pregunta,
                        sge_componente_pregunta.numero		AS comp_numero,
                        sge_componente_pregunta.componente 	AS componente
				FROM    sge_encuesta_definicion
                            INNER JOIN sge_pregunta ON (sge_encuesta_definicion.pregunta = sge_pregunta.pregunta)
                            INNER JOIN sge_componente_pregunta ON (sge_componente_pregunta.numero = sge_pregunta.componente_numero)
				WHERE   sge_encuesta_definicion.encuesta = $encuesta
				";
		
        return kolla_db::consultar($sql);
	}
    
    /*
	 * Retorna true en caso de que la pregunta o alguna de sus respuestas hayan sido
     * definidas en una encuesta que esta habilitada o sea una de las precargadas, y
     * false en caso contrario.
	 */
    function es_pregunta_usada_o_predefinida($pregunta, $componente_numero, $encuesta, $unidad_gestion)
    {
        //Si está en el rango de reservadas pertenece a las predefinidas
        if ($pregunta <= $this->pregunta_reservada_max) {
            return true;
        }
        
        $pregunta       = kolla_db::quote($pregunta);
        $encuesta       = kolla_db::quote($encuesta);
        $unidad_gestion = kolla_db::quote($unidad_gestion);
        
        //Valida que la pregunta no este usada en otra encuesta dentro de la UG
        $sql = "SELECT EXISTS (
					SELECT	1
					FROM  	sge_encuesta_atributo,
                            sge_encuesta_definicion
                    WHERE 	sge_encuesta_definicion.encuesta = sge_encuesta_atributo.encuesta
                    AND     sge_encuesta_atributo.encuesta <> $encuesta
                    AND     sge_encuesta_atributo.unidad_gestion = $unidad_gestion
                    AND     sge_encuesta_definicion.pregunta = $pregunta
				) AS rta";
		
		$existe = kolla_db::consultar_fila($sql);
        
        if ($existe['rta']) {
            return true;
        }
        
        if (in_array($componente_numero, $this->componentes_cerrados_pregunta)) {
            
            //Valida que la respuesta no este usada en otra encuesta dentro de la UG, ni sea reservada
            $sql = "SELECT	sge_pregunta_respuesta.respuesta
                    FROM  	sge_pregunta_respuesta
                    WHERE 	sge_pregunta_respuesta.pregunta = $pregunta
                    AND    (sge_pregunta_respuesta.respuesta <= ".$this->respuesta_reservada_max."
                            OR EXISTS (
                                    SELECT	1
                                    FROM  	sge_pregunta,
                                            sge_pregunta_respuesta pr,
                                            sge_encuesta_atributo,
                                            sge_encuesta_definicion
                                    WHERE 	sge_pregunta.pregunta = pr.pregunta
                                    AND     sge_pregunta.pregunta = sge_encuesta_definicion.pregunta
                                    AND     sge_encuesta_definicion.encuesta = sge_encuesta_atributo.encuesta
                                    AND     sge_encuesta_atributo.encuesta <> $encuesta
                                    AND     sge_encuesta_atributo.unidad_gestion = $unidad_gestion
                                    AND     pr.respuesta = sge_pregunta_respuesta.respuesta
                                )
                            )
                    ";
            
            $respuestas = kolla_db::consultar($sql);
            
            if (!empty($respuestas)) {
                return true;
            }
        }
        
        return false;
    }
    
    function get_encuestas_por_ug_copiar($ug = null)
	{
        $ug = kolla_db::quote($ug);
        
        $where = isset($ug) ? " WHERE sge_unidad_gestion.unidad_gestion = $ug " : '';
		
		$sql = "SELECT		sge_encuesta_atributo.encuesta		AS encuesta,
							sge_encuesta_atributo.nombre		AS nombre
				FROM 		sge_encuesta_atributo
                                JOIN sge_unidad_gestion ON (sge_encuesta_atributo.unidad_gestion = sge_unidad_gestion.unidad_gestion)
				$where
				ORDER BY 	encuesta
				";
		
        return kolla_db::consultar($sql);
    }
    
    function get_encuestas_por_ug_mover($ug = null)
	{
        $ug = kolla_db::quote($ug);
        
        $sql = "SELECT      sge_encuesta_atributo.encuesta  AS encuesta,
                            sge_encuesta_atributo.nombre	AS nombre
                FROM        sge_encuesta_atributo
                                JOIN sge_unidad_gestion ON (sge_encuesta_atributo.unidad_gestion = sge_unidad_gestion.unidad_gestion)
                WHERE       sge_unidad_gestion.unidad_gestion = $ug
                AND         sge_encuesta_atributo.encuesta > ".$this->encuesta_reservada_max."
                AND         sge_encuesta_atributo.encuesta NOT IN (
                                SELECT	ea.encuesta
                                FROM 	sge_formulario_definicion,
                                        sge_encuesta_atributo ea
                                WHERE 	sge_formulario_definicion.encuesta = ea.encuesta
                                AND    	ea.unidad_gestion = sge_unidad_gestion.unidad_gestion
                                )
                AND         sge_encuesta_atributo.encuesta NOT IN (
                                SELECT	sge_formulario_habilitado_detalle.encuesta
                                FROM	sge_habilitacion,
                                        sge_formulario_habilitado,
                                        sge_formulario_habilitado_detalle
                                WHERE   sge_formulario_habilitado.habilitacion = sge_habilitacion.habilitacion
                                AND     sge_formulario_habilitado.formulario_habilitado = sge_formulario_habilitado_detalle.formulario_habilitado
                                AND     sge_habilitacion.unidad_gestion = sge_unidad_gestion.unidad_gestion
                                AND     sge_habilitacion.externa = 'S'
                                )
                ORDER BY    encuesta
				";
		
        return kolla_db::consultar($sql);
    }
    
    function es_pregunta_libre($pregunta) 
    {
        $sql = "SELECT *
                FROM sge_pregunta p 
                WHERE p.pregunta = $pregunta ;
            ";
        $datos_pregunta = kolla_db::consultar_fila($sql);
        return $this->es_componente_libre($datos_pregunta['componente_numero']);
    }
    
    function es_componente_libre($componente_id)
    {
        switch ($componente_id) {
            case 1:
            case 8:
            case 10:
            case 11:
            case 12:
            case 13:
            case 14:
            case 15:
            case 16:
                return true;
                break;
            case 2:
            case 3:
            case 4:
            case 5:
            case 7:
            case 9:
            case 17:
            case 18:
            case 19:
                return false;
                break;
        }        
    }

    function get_pregunta_encuesta_definicion ($encuesta_definicion)
    {
        $sql = "SELECT	*
                FROM sge_encuesta_definicion sed 
                    INNER JOIN sge_pregunta sp ON (sed.pregunta = sp.pregunta)
                WHERE sed.encuesta_definicion = $encuesta_definicion
                ";
        return kolla_db::consultar_fila($sql);
    }
}
?>