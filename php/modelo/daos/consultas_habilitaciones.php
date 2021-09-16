<?php

class consultas_habilitaciones
{
	/*
	 * Retorna el listado de habilitaciones.
	 */
	function get_habilitaciones_where($where=null)
	{
		$where = $where ? " WHERE $where " : '';
		
		$sql = "SELECT
                    habilitacion,
                    fecha_desde,
                    fecha_hasta,
                    paginado,
                    externa,
                    anonima,
                    estilo,
                    sistema,
                    password_se,
                    descripcion,
                    texto_preliminar,
                    archivada,
                    destacada,                  
                    descarga_pdf,
                    generar_cod_recuperacion,
                    imprimir_respuestas_completas,
                    mostrar_progreso,
                    publica,
                    unidad_gestion,
                    url_imagenes_base                     
				FROM sge_habilitacion
				$where
				ORDER BY descripcion";
		
		return kolla_db::consultar($sql);
	}
    
    function get_habilitacion($habilitacion = null)
	{
        $where = $habilitacion ? " WHERE habilitacion = ".kolla_db::quote($habilitacion) : '';
        
		$sql = "SELECT      sge_habilitacion.habilitacion,
                            sge_habilitacion.fecha_desde,
                            sge_habilitacion.fecha_hasta,
                            sge_habilitacion.paginado,
                            sge_habilitacion.externa,
                            sge_habilitacion.anonima,
                            sge_habilitacion.estilo,
                            sge_habilitacion.sistema,
                            sge_habilitacion.password_se,
                            sge_habilitacion.descripcion,
                            to_char(sge_habilitacion.fecha_desde, 'YYYY/MM/DD')
                                || '-'
                                || to_char(sge_habilitacion.fecha_hasta, 'YYYY/MM/DD')
                                || ' - '
                                || coalesce(sge_habilitacion.descripcion, '') AS descripcion_combo,
                            sge_habilitacion.texto_preliminar,
                            sge_habilitacion.archivada,
                            sge_habilitacion.destacada,                  
                            sge_habilitacion.descarga_pdf,
                            sge_habilitacion.generar_cod_recuperacion,
                            sge_habilitacion.imprimir_respuestas_completas,
                            sge_habilitacion.mostrar_progreso,
                            sge_habilitacion.publica,
                            sge_habilitacion.unidad_gestion,
                            sge_habilitacion.url_imagenes_base,
                            sge_encuesta_estilo.descripcion as estilo_descripcion
				FROM        sge_habilitacion 
				            inner join sge_encuesta_estilo on (sge_habilitacion.estilo = sge_encuesta_estilo.estilo)
				$where
				ORDER BY    sge_habilitacion.descripcion";
        
        return kolla_db::consultar($sql);
	}
	
    /*
	 * Chequea si se trata de una habilitación anónima
	 */
	function es_habilitacion_anonima($habilitacion)
	{
		$sql = "SELECT	sge_habilitacion.habilitacion	AS habilitacion,
						sge_habilitacion.anonima		AS anonima
				FROM	sge_habilitacion
				WHERE	sge_habilitacion.habilitacion = $habilitacion
				";
		$res = kolla_db::consultar($sql);
		return $res[0]['anonima'];
	}
    
	/*
	 *	Retorna los datos de una habilitación determinada que viene como parámetro
	 */
	function get_datos_habilitacion($filtro = null)
	{
		$where = '';
        
		if (isset($filtro)) {
			$where = 'WHERE sge_habilitacion.habilitacion = '.kolla_db::quote($filtro);
		}
		
		$sql = "SELECT	sge_habilitacion.*
				FROM 	sge_habilitacion
				$where
                ";
		
		return kolla_db::consultar($sql);
	}    
	
	/**
	 * Obtiene formularios de una habilitacion. Filtro['habilitacion] es obligatorio.
	 * @param unknown $filtro
	 * @return multitype:
	 */
	function get_lista_formularios($filtro)
	{
		$where = 'habilitacion = '.(int)$filtro['habilitacion'];
		
		if (isset($filtro['concepto'])) {
			$where .= ' AND c.concepto = '.kolla_db::quote($filtro['concepto']);
		}
		
        if (isset($filtro['grupo'])) {
			$where .= ' AND g.grupo = '.kolla_db::quote($filtro['grupo']);
		}
        
		$sql = "SELECT 	  f.formulario_habilitado,
						  f.nombre 	AS nombre_form,
						  en.nombre AS nombre_encuesta,
						  en.encuesta,
						  c.descripcion AS concepto_descripcion,
						  e.descripcion AS elemento_descripcion,
						  e.elemento,
						  d.orden,
                          g.nombre || ' (' || COALESCE(c.descripcion, 'sin concepto') || ')' AS grupo_concepto_descripcion
                          ,g.grupo
                          ,c.concepto
				FROM      sge_encuesta_atributo en
							  INNER JOIN sge_formulario_habilitado_detalle d ON d.encuesta = en.encuesta
							  INNER JOIN sge_formulario_habilitado f ON d.formulario_habilitado = f.formulario_habilitado
							  LEFT JOIN sge_elemento e ON  e.elemento = d.elemento
							  LEFT JOIN sge_concepto c ON c.concepto = f.concepto
                              INNER JOIN sge_grupo_habilitado gh ON gh.formulario_habilitado = f.formulario_habilitado
                              INNER JOIN sge_grupo_definicion g ON g.grupo = gh.grupo
				WHERE 	  $where
				ORDER BY  d.formulario_habilitado
				";
		return kolla_db::consultar($sql);
	}
	
	/*
	 * Retorna la lista de habilitaciones con encuestas asociadas (todas, no solo de las encuestas activas)
	 */
	function get_habilitaciones_combo($where=null)
	{
		//VER PARA SINCRONIZAR CON EL GET_HABILITACIONES DE CONSULTAS_ENCUESTAS

		$where = isset($where) ? " AND $where" : '';
		
		$sql = "SELECT DISTINCT sge_habilitacion.habilitacion	AS habilitacion,
							sge_habilitacion.fecha_desde	AS fecha_desde,
							sge_habilitacion.fecha_hasta	AS fecha_hasta,
                            CASE WHEN fecha_hasta ISNULL 
                            THEN
                                sge_habilitacion.descripcion ||' - Desde el '||
                                to_char(fecha_desde, '".kolla_sql::formato_fecha_visual."' )
                            ELSE
                                sge_habilitacion.descripcion ||' - '||
                                to_char(fecha_desde, '".kolla_sql::formato_fecha_visual."' ) ||' al '||
                                to_char(fecha_hasta, '".kolla_sql::formato_fecha_visual."' )
                            END AS descripcion,
                            sge_habilitacion.habilitacion ||' - '|| 
                            sge_habilitacion.descripcion ||' - Desde el '|| 
                                COALESCE(to_char(fecha_desde, '".kolla_sql::formato_fecha_visual."' ), '')  ||' al '|| 
                                COALESCE(to_char(fecha_hasta, '".kolla_sql::formato_fecha_visual."' ), '') || ' (' ||
                                sge_formulario_habilitado.nombre || ')' AS descripcion_completa,
                                
                            '(id:' || sge_habilitacion.habilitacion || ') ' 
                                || sge_habilitacion.descripcion 
                                ||' - Desde el '
                                || to_char(fecha_desde, '".kolla_sql::formato_fecha_visual."' )
                                ||' al '|| to_char(fecha_hasta, '".kolla_sql::formato_fecha_visual."' )
                                as descripcion_id,
                                
                            CASE WHEN (sge_habilitacion.destacada = 'S')
		                        THEN
		                            '»»» '  
		                        ELSE '' 
		                    END ||
                            CASE WHEN (sge_habilitacion.archivada = 'S')
		                        THEN
		                            '[Archivada] '  
		                        ELSE '' 
		                    END || sge_habilitacion.descripcion 
                                ||' (id:' || sge_habilitacion.habilitacion || ') ' 
                                ||' - Desde el '
                                || to_char(fecha_desde, '".kolla_sql::formato_fecha_visual."' )
                                ||' al '|| to_char(fecha_hasta, '".kolla_sql::formato_fecha_visual."' )
                                as desc_id_rango,
                            sge_habilitacion.archivada,
                            sge_habilitacion.destacada
				FROM		sge_habilitacion INNER JOIN sge_formulario_habilitado ON (sge_habilitacion.habilitacion = sge_formulario_habilitado.habilitacion)
				WHERE		TRUE $where
				ORDER BY 	sge_habilitacion.destacada DESC,
				            sge_habilitacion.fecha_desde DESC,
				            sge_habilitacion.habilitacion DESC
				";
		
		return kolla_db::consultar($sql);
	}
	
	/*
	 * Retorna la lista de las habilitaciones pertenecientes a la Unidad de Gestión dada
	 */
	function get_habilitaciones_combo_por_ug($unidad_gestion)
	{
		$unidad_gestion = kolla_db::quote($unidad_gestion);
		$where = "sge_habilitacion.unidad_gestion = $unidad_gestion";

		return $this->get_habilitaciones_combo($where);
	}

    /*
     * Retorna la lista de las habilitaciones pertenecientes a la Unidad de Gestión dada y teniendo
     * en cuenta si se encuetra archivada la habilitacion.
     */
    function get_habilitaciones_combo_por_ug_y_archivada($unidad_gestion, $archivada)
    {
        $unidad_gestion = kolla_db::quote($unidad_gestion);
        $where = "sge_habilitacion.unidad_gestion = $unidad_gestion";

        if ($archivada != 1) {
            $where .= " AND sge_habilitacion.archivada = 'N'";
        }

        return $this->get_habilitaciones_combo($where);
    }

    function get_habilitaciones_combo_por_ug_publicas($unidad_gestion)
    {
        $unidad_gestion = kolla_db::quote($unidad_gestion);
        $where = "sge_habilitacion.unidad_gestion = $unidad_gestion";
        $where .= " AND sge_habilitacion.publica = 'S'";
        return $this->get_habilitaciones_combo($where);
    }

    function get_habilitaciones_combo_resultados_envio_emails($unidad_gestion)
    {
        $unidad_gestion = kolla_db::quote($unidad_gestion);
        $where = "sge_habilitacion.unidad_gestion = $unidad_gestion";
        $where .= "AND sge_habilitacion.archivada = 'N'";

        return $this->get_habilitaciones_combo($where);
    }
	
	/*
     * Retorna la lista de habilitaciones de acuerdo al formulario que recibe como parámetro.
     */
	function get_habilitaciones_por_formulario($formulario)
    {
    	$formulario = kolla_db::quote($formulario);
    	
    	$sql = "SELECT		sge_habilitacion.habilitacion,
							sge_habilitacion.fecha_desde,
							sge_habilitacion.fecha_hasta,
							(substr(fecha_desde::text,9,2)||'/'||substr(fecha_desde::text,6,2)||'/'||substr(fecha_desde::text,1,4)
							|| ' - ' || substr(fecha_hasta::text,9,2)||'/'||substr(fecha_hasta::text,6,2)||'/'||substr(fecha_hasta::text,1,4)) AS fechas
				FROM 		sge_habilitacion,
							sge_formulario_habilitado,
							sge_formulario_habilitado_detalle,
							sge_formulario_definicion
				WHERE		sge_formulario_definicion.formulario = $formulario
				AND			sge_formulario_definicion.encuesta = sge_formulario_habilitado_detalle.encuesta
				AND			sge_formulario_habilitado_detalle.formulario_habilitado = sge_formulario_habilitado.formulario_habilitado
				AND			sge_formulario_habilitado.habilitacion = sge_habilitacion.habilitacion
				ORDER BY 	sge_habilitacion.habilitacion
				";
		return kolla_db::consultar($sql);
    }
  

    function get_respuestas_registradas_habilitacion($habilitacion) 
	{
        $habilitacion = kolla_db::quote($habilitacion);
        
		$sql = "SELECT      COUNT (srf.respondido_formulario) AS cantidad
                FROM        sge_respondido_formulario srf
                                INNER JOIN sge_formulario_habilitado sfh ON (srf.formulario_habilitado = sfh.formulario_habilitado)
                WHERE       sfh.habilitacion = $habilitacion
                GROUP BY    sfh.habilitacion;
				";
        
        $res = kolla_db::consultar_fila($sql);
        return isset($res['cantidad']) ? $res['cantidad'] : '0';
	}
	
	function get_datos_habilitacion_x_form_hab($formulario_habilitado)
	{
		$formulario_habilitado = kolla_db::quote($formulario_habilitado);
        
		$sql = "SELECT	sge_habilitacion.*
                FROM	sge_habilitacion,
                		sge_formulario_habilitado
                WHERE	sge_formulario_habilitado.habilitacion = sge_habilitacion.habilitacion
                AND		sge_formulario_habilitado.formulario_habilitado = $formulario_habilitado
				";
        
        return kolla_db::consultar_fila($sql);
	}

    function get_datos_formulario_habilitado_publico($id_habilitacion, $id_formulario)
    {
        $id_habilitacion = kolla::db()->quote($id_habilitacion);
        $id_formulario = kolla::db()->quote($id_formulario);

        $sql = "SELECT  eh.habilitacion,
                        eh.fecha_desde,
                        eh.fecha_hasta,
                        eh.paginado,
                        eh.externa,
                        eh.anonima,
                        eh.estilo,
                        eh.mostrar_progreso,
                        eh.sistema,
                        eh.password_se,
                        eh.url_imagenes_base,
                        eh.generar_cod_recuperacion,
                        eh.texto_preliminar,
                        eh.imprimir_respuestas_completas,
                        eh.publica,
                        sis.estado as estado_sistema,
                        encu.encuestado as encuestado,
                        sfh.formulario_habilitado,
                        sfh.concepto,
                        sfh.nombre
				FROM    sge_habilitacion eh
                        LEFT JOIN sge_sistema_externo sis ON sis.sistema = eh.sistema
                        LEFT JOIN sge_encuestado encu ON encu.usuario = sis.usuario
                        INNER JOIN sge_formulario_habilitado sfh ON (sfh.habilitacion = eh.habilitacion)
				WHERE 	eh.habilitacion = $id_habilitacion 
				      AND sfh.formulario_habilitado = $id_formulario
				      AND CURRENT_DATE BETWEEN eh.fecha_desde AND eh.fecha_hasta
				";
        kolla::logger()->debug('obtener form publico');
        return kolla::db()->consultar($sql);
    }

    function get_resumen_estado_habilitacion ($where=null)
    {
        $where = $where ? " WHERE $where " : '';

	    $sql = "select *
	            from resumen_estado_habilitacion
	            $where ;";
	    return kolla_db::consultar($sql);
    }  
  
    function tiene_concepto ($id_habilitacion)
    {
        $id_habilitacion = kolla::db()->quote($id_habilitacion);
        $sql = "select sfh.concepto
                from sge_habilitacion sh 
                    inner join sge_formulario_habilitado sfh on (sh.habilitacion = sfh.habilitacion)
					inner join sge_concepto sc on sfh.concepto = sc.concepto
                where sh.habilitacion = $id_habilitacion;";
        $concepto = kolla::db()->consultar($sql);
        return (count($concepto) > 0 && $concepto[0] != 'null' && $concepto[0] != '');
    }
}
?>
