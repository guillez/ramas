<?php

class consultas_formularios
{
	/*
	 * Retorna el listado de formularios de encuestas.
	 */
	function get_formularios($filtro=null)
	{
		$where = array('TRUE');

        if (is_array($filtro)) {
            if (isset($filtro['unidad_gestion'])) {
                $unidad_gestion = kolla_db::quote($filtro['unidad_gestion']['valor']);
                $where[] = "formulario IN (
		                        SELECT	d.formulario 
		                        FROM	sge_formulario_definicion  AS d
		                            	JOIN sge_encuesta_atributo AS e ON (d.encuesta = e.encuesta AND e.unidad_gestion = $unidad_gestion))
                			";
            }

            if ( isset($filtro['estado']) ) {
                $estado = kolla_db::quote($filtro['estado']['valor']);
                $where[] = 'estado = ' . $estado;
            }

            if ( isset($filtro['descripcion']) ) {
                $desc = kolla_db::quote('%'.$filtro['descripcion']['valor'].'%');
                $where[] = 'descripcion ILIKE ' . $desc;
            }

            if ( isset($filtro['nombre']) ) {
                $nombr = kolla_db::quote('%'.$filtro['nombre']['valor'].'%');
                $where[] = 'nombre ILIKE ' . $nombr;
            }

            if ( isset($filtro['formulario']) ) {
                $formulario = kolla_db::quote($filtro['formulario']['valor']);
                $where[] = 'formulario = ' . $formulario;
            }
        } else {
            $where[] = $filtro;
        }
		
        $where = implode(' AND ', $where);
        
		$sql = "SELECT		formulario,
							nombre,
							descripcion,
							estado,
							texto_preliminar,
							CASE
								WHEN estado = 'A' THEN 'Activo'
								WHEN estado = 'I' THEN 'Inactivo'
							END	AS estado_descr
				FROM		sge_formulario_atributo
				WHERE		$where
				ORDER BY	nombre
				";
		
		return kolla_db::consultar($sql);
	}
	
	/*
	 * Retorna el listado de tipos de elementos.
	 */
	function get_tipos_elemento($where=null)
	{
        $where .= $where ? '' : ' TRUE ';

        $sql = "SELECT      sge_tipo_elemento.tipo_elemento,
                            sge_tipo_elemento.descripcion,
                            ug.unidad_gestion,
                            ug.nombre as ug_nombre,
                            sge_tipo_elemento.tipo_elemento_externo,
                            sge_tipo_elemento.sistema,
                            se.nombre as sistema_descripcion
                FROM        sge_tipo_elemento
                            LEFT OUTER JOIN sge_unidad_gestion AS ug ON (sge_tipo_elemento.unidad_gestion = ug.unidad_gestion)
                            LEFT OUTER JOIN sge_sistema_externo AS se ON (sge_tipo_elemento.sistema = se.sistema)	
                WHERE       $where
                ORDER BY sge_tipo_elemento.descripcion";

        return kolla_db::consultar($sql);
	}
	
	/*
	 * Obtiene la descripción de un tipo de elemento a partir del id dado para el ef_popup de Tipo Elemento.
	 */
	static function get_descripcion_tipo_elemento($id = null)
	{
		if (!isset($id) || trim($id) == '') {
			return array();
		}
		
		$id = kolla_db::quote($id);
		$sql = "SELECT	sge_tipo_elemento.descripcion
				FROM	sge_tipo_elemento
				WHERE	sge_tipo_elemento.tipo_elemento = $id
				";
		
		$result = kolla_db::consultar_fila($sql);
		
		if (!empty($result)) {
			return $result['descripcion'];
		}
		return null;
	}
	
	/*
	 * Retorna el listado para el combo de tipos de elementos.
	 */
	function get_combo_tipos_elemento($where='TRUE')
	{
		$sql = "SELECT		sge_tipo_elemento.tipo_elemento	AS valor,
							sge_tipo_elemento.descripcion	AS descr
				FROM		sge_tipo_elemento
                WHERE       $where
				ORDER BY 	sge_tipo_elemento.descripcion";
		
		return kolla_db::consultar($sql);
	}
	
	/*
	 * Obtiene una lista con los estados posibles para un formulario.
	 */
	function get_estado_activo_inactivo_formulario()
	{
		return array(array('valor' => 'A', 'descr' => 'Activo'),
					 array('valor' => 'I', 'descr' => 'Inactivo'));
	}
	
	/*
	 * Retorna true en caso de que el tipo de elemento este siendo
	 * usado por algún formulario, y false en caso contrario.
	 */
	function es_tipo_elemento_en_uso($tipo_elemento)
	{
		return abm::existen_registros('sge_formulario_definicion', array('tipo_elemento' => $tipo_elemento));
	}
	
	/*
	 *  Valida si un nombre de Tipo de Elemento se puede usar o no.
     */
	function validar_descripcion_tipo_elemento($descripcion, $tipo_elemento=null)
	{
		if (!isset($descripcion)) {
			return false;
		}
		
		$descripcion = kolla_db::quote($descripcion);
		$sql = 'SELECT 	COUNT(sge_tipo_elemento.tipo_elemento) AS cant 	
                FROM 	sge_tipo_elemento
				WHERE 	'.kolla_sql::armar_condicion_compara_cadenas('sge_tipo_elemento.descripcion', $descripcion);
		
		if (!is_null($tipo_elemento)) {
			$tipo_elemento = kolla_db::quote($tipo_elemento);
			$sql .= " AND sge_tipo_elemento.tipo_elemento <> $tipo_elemento ";
		}
		
		$res = kolla_db::consultar_fila($sql);
		return ($res['cant'] == 0);
	}
	
	/*
	 *  Valida si un nombre de Formulario se puede usar o no.
     */
	function validar_nombre_formulario($nombre, $formulario = null, $unidad_gestion = null)
	{
		if (!isset($nombre)) {
			return false;
		}
		
		$nombre = kolla_db::quote($nombre);
		$sql = 'SELECT 	COUNT(sge_formulario_atributo.formulario) AS cant 	
                FROM 	sge_formulario_atributo
				WHERE 	'.kolla_sql::armar_condicion_compara_cadenas('sge_formulario_atributo.nombre', $nombre);
		
		if (!is_null($formulario)) {
			$formulario = kolla_db::quote($formulario);
			$sql .= " AND sge_formulario_atributo.formulario <> $formulario ";
		}
		
        if (!is_null($unidad_gestion)) {
            $unidad_gestion = kolla_db::quote($unidad_gestion);
            $sql .= " AND formulario IN (
                            SELECT	d.formulario 
                            FROM	sge_formulario_definicion  AS d
                                        JOIN sge_encuesta_atributo AS e ON (e.encuesta = d.encuesta AND
                                                                            e.unidad_gestion = $unidad_gestion))
                        ";
        }
        
		$res = kolla_db::consultar_fila($sql);
		return ($res['cant'] == 0);
	}
	
	/*
	 * Retorna las habilitaciones de acuerdo al filtro recibido.
	 */
	function get_habilitaciones($where=null)
	{
		$where .= $where ? '' : ' TRUE ';

        $sql = "SELECT sge_habilitacion.habilitacion,
							sge_habilitacion.fecha_desde,
							sge_habilitacion.fecha_hasta,
							CASE
								WHEN sge_habilitacion.paginado = 'S' THEN 'Si'
								WHEN sge_habilitacion.paginado = 'N' THEN 'No'
							END	AS paginado_descr,
							CASE
								WHEN sge_habilitacion.externa = 'S' THEN 'Si'
								WHEN sge_habilitacion.externa = 'N' THEN 'No'
							END	AS externa_descr,
							CASE
								WHEN sge_habilitacion.anonima = 'S' THEN 'Si'
								WHEN sge_habilitacion.anonima = 'N' THEN 'No'
							END	AS anonima_descr,
							CASE
								WHEN sge_habilitacion.archivada = 'S' THEN 'Si'
								WHEN sge_habilitacion.archivada = 'N' THEN 'No'
							END	AS archivada,
							CASE
								WHEN sge_habilitacion.destacada = 'S' THEN 'Si'
								WHEN sge_habilitacion.destacada = 'N' THEN 'No'
							END	AS destacada,
							sge_habilitacion.sistema,
							sge_habilitacion.descripcion
                            ,sge_habilitacion.descripcion || ' (id:' || sge_habilitacion.habilitacion || ') ' AS descripcion_completa
                            ,sge_habilitacion.unidad_gestion as unidad_gestion
							,CASE
								WHEN sge_habilitacion.publica = 'S' THEN 'Si'
								WHEN sge_habilitacion.publica = 'N' THEN 'No'
							END	AS publica                            
                            
                FROM	 sge_habilitacion 
				WHERE		$where
				ORDER BY 	sge_habilitacion.descripcion";

        return kolla_db::consultar($sql);
	}
	
	/*
	 * Retorna el listado para el combo de formularios activos.
	 */
	function get_combo_formularios_activos()
	{
		$sql = "SELECT		sge_formulario_atributo.formulario,
							sge_formulario_atributo.nombre
				FROM		sge_formulario_atributo
				WHERE		sge_formulario_atributo.estado = 'A'
				ORDER BY 	sge_formulario_atributo.nombre";
		
        return kolla_db::consultar($sql);
	}
	
	/*
	 * Retorna el listado para el combo de formularios activos.
	 */
	function get_combo_conceptos($where='TRUE')
	{
		$sql = "SELECT		sge_concepto.concepto		AS valor,
							sge_concepto.descripcion	AS descr
				FROM		sge_concepto
                WHERE       $where
				ORDER BY 	sge_concepto.descripcion";
		
		return kolla_db::consultar($sql);
	}

    function get_combo_conceptos_para_formulario($where, $formulario)
    {
        if (!isset($formulario)) {
        	return null;
        }

        $sql = "SELECT	DISTINCT  sge_concepto.concepto		AS valor,
							      sge_concepto.descripcion	AS descr
                FROM sge_concepto 
                  INNER JOIN sge_elemento_concepto_tipo sect ON (sect.concepto = sge_concepto.concepto)
                  INNER JOIN sge_tipo_elemento ste ON (ste.tipo_elemento = sect.tipo_elemento)
                  WHERE $where 
                  		AND ste.tipo_elemento IN (SELECT sfd.tipo_elemento 
                                              FROM sge_formulario_definicion sfd 
                                              WHERE sfd.formulario = $formulario)                       
				ORDER BY 	sge_concepto.descripcion;";

        $conceptos = kolla_db::consultar($sql);
        return $conceptos;
    }

	
	function get_combo_elementos($where='TRUE')
	{
		$sql = "SELECT		elemento 	AS valor,
							descripcion	AS descr
				FROM		sge_elemento
                WHERE       $where
				ORDER BY	descripcion";
		
		return kolla_db::consultar($sql);
	}
	
	/*
	 * Retorna los datos de un formulario pasado como parámetro.
	 */
	function get_datos_formulario($formulario)
	{
		$filtro['formulario']['valor'] = $formulario;
		return current($this->get_formularios($filtro));
	}

	/*
	 * Retorna los datos de la definicion de un formulario 
	 */
	function get_datos_formulario_definicion($formulario)
	{
		$formulario = kolla_db::quote($formulario);
        
        $sql ="	SELECT	encuesta,
						tipo_elemento,
                        orden
				FROM	sge_formulario_definicion
				WHERE	formulario = $formulario
				;";
		return kolla_db::consultar($sql);
	}
    
    /*
	 * Retorna los datos de un formulario utilizado originalmente en una habilitacion.
	 */
	function get_datos_formulario_plantilla($habilitacion)
	{
		$habilitacion = kolla_db::quote($habilitacion);

        $sql =" SELECT  nombre,
                        estado
                FROM    sge_formulario_habilitado
                WHERE   habilitacion = $habilitacion
                LIMIT 1
        ";

        return kolla_db::consultar_fila($sql);        
	}
	
	/*
	 * Retorna el listado de las encuestas de un formulario y concepto dados.
	 */
	function get_datos_formulario_encuestas($formulario, $concepto = null)
	{   
        $formulario = kolla_db::quote($formulario);
        $where_concepto = '';
        if ( !is_null($concepto) ) {
            $concepto = kolla_db::quote($concepto);
            $where_concepto = " AND sge_elemento_concepto_tipo.concepto = $concepto";
        }

        $sql = "SELECT  sge_formulario_definicion.encuesta,
                        sge_elemento_concepto_tipo.elemento,
                        sge_formulario_definicion.tipo_elemento,
                        sge_formulario_definicion.orden AS orden_definicion
                FROM    sge_formulario_definicion
	                    INNER JOIN sge_elemento_concepto_tipo
                            ON sge_formulario_definicion.tipo_elemento = sge_elemento_concepto_tipo.tipo_elemento
                WHERE   sge_formulario_definicion.formulario = $formulario
                        $where_concepto
                
                UNION

                SELECT  sge_formulario_definicion.encuesta,
                        null AS elemento,
                        sge_formulario_definicion.tipo_elemento,
                        sge_formulario_definicion.orden AS orden_definicion
                FROM    sge_formulario_definicion
                WHERE   sge_formulario_definicion.formulario = $formulario 
                        AND sge_formulario_definicion.tipo_elemento IS NULL
                ORDER BY orden_definicion
                ";
        return kolla_db::consultar($sql);        
        
	}

	function get_datos_formulario_plantilla_encuestas($habilitacion, $concepto)
	{
        $habilitacion = kolla_db::quote($habilitacion);
        $where_concepto = '';
        if ( !is_null($concepto) ) {
            $concepto = kolla_db::quote($concepto);
            $where_concepto = " AND sect.concepto = $concepto";
        }

        $sql ="	SELECT	slfdh.encuesta,
                        sect.elemento,
						slfdh.tipo_elemento,
                        sfh.nombre,
                        slfdh.orden as orden_definicion
				FROM	sge_log_formulario_definicion_habilitacion slfdh
                        INNER JOIN sge_formulario_habilitado sfh ON (slfdh.habilitacion = sfh.habilitacion)
                        INNER JOIN sge_elemento_concepto_tipo sect ON (slfdh.tipo_elemento = sect.tipo_elemento)
				WHERE	sfh.habilitacion = $habilitacion
                        $where_concepto 

                UNION

                SELECT  slfdh.encuesta,
                        null AS elemento,
                        slfdh.tipo_elemento,
                        sfh.nombre,
                        slfdh.orden as orden_definicion
                FROM	sge_log_formulario_definicion_habilitacion slfdh
                        INNER JOIN sge_formulario_habilitado sfh ON (slfdh.habilitacion = sfh.habilitacion)
                WHERE	sfh.habilitacion = $habilitacion
                        AND slfdh.tipo_elemento IS NULL
                ORDER BY orden_definicion
				;";
		return kolla_db::consultar($sql);
	}
	
	/*
	 * Retorna la estructura de un formulario: nombre, encuesta, elemento y orden. 
	 */
	function get_formulario_estructura($id_f_atributo)
	{
		$id = kolla_db::quote($id_f_atributo);
		
		$sql = "SELECT 		sge_formulario_atributo.nombre, 
							sge_formulario_atributo.descripcion, 
							sge_formulario_atributo.texto_preliminar, 
							sge_formulario_definicion.encuesta, 
							sge_tipo_elemento.descripcion AS tipo_elemento,
							sge_formulario_definicion.orden
			  	FROM 		sge_formulario_atributo, 
							sge_formulario_definicion 
								LEFT JOIN sge_tipo_elemento ON sge_formulario_definicion.tipo_elemento = sge_tipo_elemento.tipo_elemento 
			  	WHERE 		sge_formulario_definicion.formulario = sge_formulario_atributo.formulario
				AND			sge_formulario_definicion.formulario = $id
				ORDER BY 	sge_formulario_definicion.orden";
		
		return consultar_fuente($sql);
	}
	
	/*
	 * Retorna los datos de un concepto pasado como parámetro.
	 */
	function get_datos_conceptos($concepto = null)
	{
		if (is_null($concepto)) {
			return array();
		}
		
		$concepto 	= kolla_db::quote($concepto);
		
		$sql = "SELECT	sge_concepto.concepto_externo,
						sge_concepto.sistema,
						sge_concepto.descripcion
				FROM	sge_concepto
				WHERE	sge_concepto.concepto = $concepto";
		
		return kolla_db::consultar_fila($sql);
	}
	
	/*
	 * Retorna el listado para el combo de grupos que contengan encuestados.
	 */
	function get_combo_grupos_encuestados($where = null)
	{
		$where .= $where ? '' : ' TRUE ';

		//se filtra el grupo predefinido para anónimos por el nombre ya que no se puede asegurar un id fijo
		$sql = "SELECT		sge_grupo_definicion.grupo	AS valor,
							sge_grupo_definicion.nombre	AS descr
				FROM 		sge_grupo_definicion
				WHERE		EXISTS
							(
								SELECT	1
								FROM	sge_grupo_detalle
								WHERE	sge_grupo_detalle.grupo = sge_grupo_definicion.grupo 
							)
				AND			sge_grupo_definicion.estado != 'B'
				AND 		sge_grupo_definicion.externo = 'N' 
				AND			$where
				ORDER BY	sge_grupo_definicion.nombre";

		return kolla_db::consultar($sql);
	}

	function get_grupo_anonimo_predefinido($unidad_gestion)
	{
        $unidad_gestion = kolla_db::quote($unidad_gestion);
        $sql = "SELECT		sge_grupo_definicion.grupo,
							sge_grupo_definicion.nombre
				FROM 		sge_grupo_definicion
							INNER JOIN sge_grupo_detalle ON sge_grupo_definicion.grupo = sge_grupo_detalle.grupo
							INNER JOIN sge_encuestado ON sge_grupo_detalle.encuestado = sge_encuestado.encuestado
				WHERE		sge_grupo_definicion.unidad_gestion = $unidad_gestion
							AND sge_grupo_definicion.estado = 'O'
							AND sge_encuestado.guest = 'S'  
				ORDER BY	sge_grupo_definicion.nombre";

        return kolla_db::consultar_fila($sql);
	}

	function get_formulario_habilitado($formulario_habilitado)
	{		
		$sql = '
			SELECT		
				sfh.formulario_habilitado,
				sfh.habilitacion,
				sfh.concepto,
				sfh.nombre
			FROM
				sge_formulario_habilitado sfh
			WHERE
				formulario_habilitado = '.kolla_db::quote($formulario_habilitado);
		
		return kolla_db::consultar_fila($sql);
	}

	/*
	 * Retorna el listado de formularios habilitados con parámetro para combo
	 */
	function get_formularios_habilitados_habilitacion($habilitacion)
	{
        $habilitacion = kolla_db::quote($habilitacion);
        
		$sql = "SELECT DISTINCT
     						sge_formulario_habilitado.formulario_habilitado,
                            sge_formulario_habilitado.habilitacion,
                            sge_formulario_habilitado.concepto,
                            sge_concepto.descripcion AS concepto_descripcion,
                            sge_formulario_habilitado.nombre,
                            sge_formulario_habilitado.nombre || ' - ' || COALESCE(sge_concepto.descripcion, 'Sin concepto asociado') AS descr,
                            sge_formulario_habilitado.nombre || ' - ' || COALESCE(sge_concepto.descripcion, 'Sin concepto asociado') AS descripcion_completa,
                            sge_grupo_definicion.nombre || ' (' || COALESCE(sge_concepto.descripcion, 'sin concepto') || ')' AS descripcion_grupo_concepto,
                            sge_formulario_habilitado.nombre || ' - ' || COALESCE(sge_concepto.descripcion, 'Sin concepto asociado')  || ' - Grupo: ' || sge_grupo_definicion.nombre AS formulario_concepto_grupo
				FROM        sge_formulario_habilitado
                                LEFT JOIN sge_concepto ON sge_formulario_habilitado.concepto = sge_concepto.concepto
                                INNER JOIN sge_grupo_habilitado ON sge_formulario_habilitado.formulario_habilitado = sge_grupo_habilitado.formulario_habilitado
                                INNER JOIN sge_grupo_definicion ON (sge_grupo_habilitado.grupo = sge_grupo_definicion.grupo 
                                									AND
																	CASE 
																		WHEN sge_concepto.unidad_gestion IS NOT NULL AND sge_formulario_habilitado.formulario_habilitado_externo IS NULL
																		THEN sge_concepto.unidad_gestion = sge_grupo_definicion.unidad_gestion
																		ELSE TRUE
																	END )
				WHERE		sge_formulario_habilitado.habilitacion = $habilitacion
				ORDER BY	sge_formulario_habilitado.nombre,
							sge_concepto.descripcion
				";
		return kolla_db::consultar($sql);
	}
    
	/*
	 * Retorna el listado de formularios habilitados
	 */
	function get_elementos_formulario_encuesta($formulario_habilitado=null, $encuesta=null)
	{
		$where = array('TRUE');
		
		if ( $formulario_habilitado  ) {
			$where[] = 'sfhd.formulario_habilitado = ' . kolla_db::quote($formulario_habilitado);
		}
		if ( $encuesta ) {
			$where[] = 'sfhd.encuesta = ' . kolla_db::quote($encuesta);
		}
		
		$where = implode(' AND ', $where);
		
		$sql = "
			SELECT 
				sfhd.formulario_habilitado_detalle,
				sfhd.formulario_habilitado,
				sfhd.encuesta,
				sfhd.elemento,
				sfhd.orden, 
				se.elemento_externo,
				se.sistema,
				se.url_img,
				se.descripcion
			FROM
				sge_formulario_habilitado_detalle sfhd
                INNER JOIN sge_elemento se ON (sfhd.elemento = se.elemento)
            WHERE 
                $where
		";
		
		return kolla_db::consultar($sql);
	}
    
    function get_formulario_con_codigo_recuperacion($where) 
    {
        $sql = "SELECT
                    srf.respondido_formulario,
                    srf.formulario_habilitado,
                    srf.fecha,
                    srf.codigo_recuperacion,
                    srf.version_digest,
                    sfh.nombre,
                    sre.encuestado
                    ,se.usuario
                FROM
                    sge_respondido_formulario srf 
                    INNER JOIN sge_formulario_habilitado sfh ON (srf.formulario_habilitado = sfh.formulario_habilitado)
                    LEFT JOIN sge_respondido_encuestado sre ON (sre.respondido_formulario = srf.respondido_formulario)
                    LEFT JOIN sge_encuestado se ON (sre.encuestado = se.encuestado)
                WHERE $where ;";
        return consultar_fuente($sql);
    }
    
    function get_datos_formulario_anonimo_respondido($respondido_formulario)
    {
        $sql = "SELECT
                    srf.respondido_formulario,
                    srf.formulario_habilitado,
                    srf.fecha,
                    srf.codigo_recuperacion,
                    srf.version_digest,
                    sfh.nombre,
                    sre.encuestado,
                   	se.usuario,
                    sh.habilitacion,
                    sh.anonima	
                FROM
                    sge_respondido_formulario srf
                    INNER JOIN sge_formulario_habilitado sfh ON (srf.formulario_habilitado = sfh.formulario_habilitado)
                    INNER JOIN sge_habilitacion sh ON (sfh.habilitacion = sh.habilitacion)
                    LEFT JOIN sge_respondido_encuestado sre ON (sre.respondido_formulario = srf.respondido_formulario)
                    LEFT JOIN sge_encuestado se ON (sre.encuestado = se.encuestado)
                WHERE srf.respondido_formulario = ".quote($respondido_formulario).";";
        return consultar_fuente($sql);
    }
    
    function get_es_moderada($respondido_formulario, $codigo_recuperacion)
    {
        $sql = "SELECT
                    COUNT (srd.moderada) AS moderadas
                FROM
                    sge_respondido_detalle srd inner join sge_respondido_encuesta sre
                                on (srd.respondido_encuesta = sre.respondido_encuesta)
                                inner join sge_respondido_formulario srf
                                on (srf.respondido_formulario = sre.respondido_encuesta)
                WHERE srf.respondido_formulario = $respondido_formulario and srd.moderada = 'S'
                GROUP BY srf.respondido_formulario";
        
    }
    
    /*
     * Retorna la lista de formularios de acuerdo al estado que recibe como parámetro.
     */
    function get_formularios_por_estado($estado)
    {
    	$estado = kolla_db::quote($estado);
    	$where = "sge_formulario_atributo.estado = $estado";
		return $this->get_formularios($where);
    }
    
	/*
     * Retorna las respuestas de un formulario habilitado.
     */
    function get_respuestas_completas_formulario_habilitado($formulario_habilitado, $definicion_tabla_resultados, $where)
    {
    	$sql = "SELECT 	respondido_detalle, usuario, bloque_nombre, pregunta_nombre, respuesta_valor AS valor, moderada, componente
				FROM 	respuestas_completas_formulario_habilitado ($formulario_habilitado) $definicion_tabla_resultados 
				WHERE 	TRUE $where";
        
        return consultar_fuente($sql);
    }
    
    /*
	 * Retorna el listado de los formularios habilitados que contienen sólo la encuesta 4,
	 * correspondiente a Relevamiento de Datos Censales - Ingeniería
	 */
	function get_formularios_habilitados_exportar()
	{
		$sql = "SELECT  sge_formulario_habilitado.formulario_habilitado, 
                    sge_grupo_habilitado.grupo, 
                    sge_habilitacion.fecha_desde, 
                    sge_habilitacion.fecha_hasta, 
                    sge_habilitacion.descripcion || ' - ' || 
                        sge_habilitacion.fecha_desde || ' a ' || 
                        sge_habilitacion.fecha_hasta || ' - Grupo: ' || 
                        sge_grupo_definicion.nombre AS descripcion_completa
                FROM 	sge_habilitacion 
                        INNER JOIN sge_formulario_habilitado 
                            ON (sge_formulario_habilitado.habilitacion = sge_habilitacion.habilitacion)
                        INNER JOIN sge_grupo_habilitado 
                            ON (sge_grupo_habilitado.formulario_habilitado = sge_formulario_habilitado.formulario_habilitado)
                        INNER JOIN sge_grupo_definicion 
                            ON (sge_grupo_definicion.grupo = sge_grupo_habilitado.grupo)
                WHERE NOT EXISTS (
                        SELECT	sge_formulario_habilitado_detalle.encuesta
                        FROM	sge_formulario_habilitado_detalle
                        WHERE	sge_formulario_habilitado_detalle.formulario_habilitado = sge_formulario_habilitado.formulario_habilitado
                        AND	sge_formulario_habilitado_detalle.encuesta <> '4'
                        )
                ORDER BY fecha_hasta, fecha_desde, descripcion_completa
				";
		
		return consultar_fuente($sql);
	} 
	
	function get_respondido_formulario($encuestado, $formulario_habilitado)
	{
		$encuestado = kolla_db::quote($encuestado);
	    $formulario_habilitado = kolla_db::quote($formulario_habilitado);
	
	    $sql = "SELECT	respondido_formulario,
	    				terminado
		        FROM 	sge_respondido_encuestado
		        WHERE 	encuestado = $encuestado
		        AND		formulario_habilitado = $formulario_habilitado
		       	";
		
	    return  kolla_db::consultar_fila($sql);
	}
	
	function get_formularios_respondidos($where = null)
	{
		$where .= $where ? '' : ' TRUE ';
		
		$sql = "SELECT	sge_respondido_formulario.respondido_formulario,
						sge_formulario_habilitado.formulario_habilitado,
						sge_respondido_formulario.fecha_terminado AS fecha,
						to_char(sge_respondido_formulario.fecha_terminado, '".kolla_sql::formato_fecha_visual."') AS fecha_formato_visual,
						sge_respondido_formulario.codigo_recuperacion,
						sge_formulario_habilitado.nombre,
						sge_habilitacion.anonima,
						COALESCE(sge_encuestado.usuario, 'Anónimo') AS usuario
				FROM	sge_respondido_formulario
                        LEFT JOIN sge_respondido_encuestado ON 
                        sge_respondido_formulario.respondido_formulario = sge_respondido_encuestado.respondido_formulario AND
                        sge_respondido_formulario.formulario_habilitado = sge_respondido_encuestado.formulario_habilitado
                        LEFT JOIN sge_encuestado ON
                        sge_respondido_encuestado.encuestado = sge_encuestado.encuestado,
						sge_formulario_habilitado,
						sge_habilitacion
				WHERE 	sge_formulario_habilitado.formulario_habilitado = sge_respondido_formulario.formulario_habilitado
				AND 	sge_formulario_habilitado.habilitacion = sge_habilitacion.habilitacion
				AND 	$where";
		
		return kolla_db::consultar($sql);
	}

    function get_conceptos_por_habilitacion($habilitacion, $grupo=null, $formulario_habilitado=null)
    {
        $habilitacion = kolla_db::quote($habilitacion);
        $grupo = ($grupo != null) ? kolla_db::quote($grupo) : null;
        $formulario_habilitado = ($formulario_habilitado != null) ? kolla_db::quote($formulario_habilitado) : null;
        
        $where = '';
        $where .= ($grupo != null) ? " AND sgh.grupo = $grupo " : '';
        $where .= ($formulario_habilitado != null) ? " AND sfh.formulario.habilitado = $formulario_habilitado " : '';
        
        $sql = "SELECT DISTINCT sc.concepto,
                    sc.descripcion as descripcion,
                    sfh.habilitacion,
                    sfh.nombre as nombre
                FROM sge_concepto sc
                    INNER JOIN sge_formulario_habilitado sfh ON (sc.concepto = sfh.concepto)
                    INNER JOIN sge_grupo_habilitado sgh ON (sfh.formulario_habilitado = sgh.formulario_habilitado)
                    WHERE sfh.habilitacion = $habilitacion 
                    $where ;
                ";
        
        return kolla_db::consultar($sql);
    }
    
    function get_respuestas_registradas_formulario_habilitado($form_hab) 
	{
        $form_hab = kolla_db::quote($form_hab);
        
		$sql = "SELECT	srf.formulario_habilitado, 
                    	COUNT(srf.respondido_formulario) AS cantidad
                FROM 	sge_respondido_formulario srf
                    		INNER JOIN sge_formulario_habilitado sfh ON (srf.formulario_habilitado = sfh.formulario_habilitado)
                WHERE 	srf.formulario_habilitado = $form_hab
                GROUP BY srf.formulario_habilitado;
				";
        
        $res = kolla_db::consultar_fila($sql);
        return isset($res['cantidad']) ? $res['cantidad'] : '0';
	}
	
    function get_cantidad_formularios_habilitados_por_concepto($concepto)
    {
        $concepto = kolla_db::quote($concepto);
        
		$sql = "SELECT	COUNT(sge_formulario_habilitado.formulario_habilitado) AS cant
                FROM 	sge_formulario_habilitado
                WHERE 	sge_formulario_habilitado.concepto = $concepto
				";
        
        $res = kolla_db::consultar_fila($sql);
        return $res['cant'];
    }
    
    function tiene_encuestas_implementadas($formulario = null)
    {
        if (is_null($formulario)) {
            return true;
        }
        
        //Aca recorrer todas las encuestas para ver si son todas implementadas == false
        return false;
    }
    
    function get_tipos_elementos_habilitacion_encuesta($habilitacion, $encuesta)
    {
        $encuesta = kolla_db::quote($encuesta);
        $habilitacion = kolla_db::quote($habilitacion);
            
        $sql = "SELECT 
                    h.habilitacion, 
                    h.unidad_gestion, 
                    h.fecha_desde, 
                    h.fecha_hasta, 
                    fh.concepto, 
                    fh.formulario_habilitado, 
                    fhd.formulario_habilitado_detalle, 
                    fhd.encuesta, 
                    fhd.tipo_elemento, 
                    ea.encuesta
                  FROM 
                    sge_habilitacion h INNER JOIN sge_formulario_habilitado fh ON (h.habilitacion = fh.habilitacion)
                                    INNER JOIN sge_formulario_habilitado_detalle fhd ON (fhd.formulario_habilitado = fh.formulario_habilitado)
                                    INNER JOIN sge_encuesta_atributo ea ON (ea.encuesta = fhd.encuesta)
                                    INNER JOIN kolla.sge_concepto c ON (c.concepto = fh.concepto)
                                    INNER JOIN sge_tipo_elemento te ON (te.tipo_elemento = fhd.tipo_elemento)	

                  WHERE h.habilitacion = $habilitacion
                        AND ea.encuesta = $encuesta
                  ORDER BY h.habilitacion, ea.encuesta;";
        return kolla_db::consultar($sql);
    }

    function get_datos_formulario_habilitado_publico($habilitacion, $formulario_habilitado, $usuario)
	{
        $select = isset($usuario) ? ', '.kolla_db::quote($usuario).' AS usuario_encuestado' : '';
        $where  = isset($usuario) ? 'e.usuario = '.kolla_db::quote($usuario) : '';

        $sql = " SELECT     DISTINCT h.habilitacion,
                            fh.formulario_habilitado AS formulario,
                            fh.nombre,
                            h.fecha_desde,
                            h.fecha_hasta,
                            h.anonima,
                            c.descripcion 								AS desc_concepto,
                            h.descripcion || ' - ' || fh.nombre 		AS descripcion_habilitacion_formulario,
                            h.descripcion || ' [' || fh.nombre || ']' 	AS formulario_descripcion_de_habilitacion
                            $select
                FROM        sge_habilitacion h
                                INNER JOIN sge_formulario_habilitado fh ON h.habilitacion = fh.habilitacion
                                LEFT OUTER JOIN sge_concepto c          ON fh.concepto = c.concepto
                                INNER JOIN sge_grupo_habilitado gh      ON gh.formulario_habilitado = fh.formulario_habilitado
                                INNER JOIN sge_grupo_detalle gd         ON (gd.grupo = gh.grupo)
                                INNER JOIN sge_encuestado e             ON (gd.encuestado = e.encuestado)
                WHERE       fh.estado = 'A'
                AND         CURRENT_DATE BETWEEN h.fecha_desde AND h.fecha_hasta
                AND         $where
                ORDER BY    h.fecha_hasta
                ";

        return kolla_db::consultar($sql);
	}

	function get_elementos_por_concepto ($concepto) {
		$sql = "SELECT 
					sge_elemento.elemento,
					sge_elemento.elemento_externo,
					sge_elemento.descripcion,
					sge_elemento.sistema,
					sge_elemento.unidad_gestion,
					sge_elemento.url_img
				FROM sge_elemento_concepto_tipo INNER JOIN sge_elemento ON (sge_elemento_concepto_tipo.elemento = sge_elemento.elemento)
				WHERE sge_elemento_concepto_tipo.concepto = ".$concepto.";";

		return kolla_db::consultar($sql);
	}
}
?>
