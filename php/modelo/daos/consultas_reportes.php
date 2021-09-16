<?php

class consultas_reportes
{
	function get_habilitacion_multiple($id)
	{
		$sql = "SELECT 
					eh.habilitacion, 
					eh.externa,
					eh.anonima,
					eh.multiple,
					'NO FUNCIONA' AS concepto_descripcion,
					'N/A' AS concepto_externo
				FROM
					sge_habilitacion eh
				WHERE 
					eh.habilitacion = ".quote($id);

		$res = consultar_fuente($sql);
		if (count($res)!= 1) {
			return null;
		}
		return $res[0];
	}
	
	function resultados_por_pregunta($filtro=null)
	{
		$sql =	"SELECT
					rc.encuesta AS encuesta,
					rc.bloque,
					rc.numero,
					rc.bloque_nombre,
					rc.pregunta,
					rc.pregunta_nombre,
					rc.tabla_asociada,
					rc.tabla_asociada_codigo,
					rc.tabla_asociada_descripcion,
					rc.respuesta,
					rc.respuesta_valor,
					ee.habilitacion,
					us.usuario AS usuario
				FROM
					respuestas_completas_view rc 
					INNER JOIN sge_encuestas_realizada_encabezado ee ON ee.encuesta_encabezado = rc.encuesta_encabezado
					INNER JOIN sge_encuestado us ON us.encuestado = ee.encuestado
					";

		if (isset($filtro['fecha_desde']) || isset($filtro['fecha_hasta']) ||isset($filtro['inconclusas'])) {
			if (isset($filtro['inconclusas']) && $filtro['inconclusas']==1) {
				$join = " LEFT JOIN ";
			} else { 
				$join = " INNER JOIN ";
			}
			$sql = $sql.$join. "sge_encuestas_terminada et ON ee.encuesta_encabezado = et.encuesta_encabezado";
	 	}
		
	 	$where = $this->obtener_where($filtro);
		toba::logger()->debug($sql.$where." ORDER BY numero;");
		return consultar_fuente($sql.$where." ORDER BY numero;");
	}
	
	function obtener_where($filtro)
	{
		$where = '';
		if (isset($filtro)) {
			if (isset($filtro['encuesta'])) {
				$where = ($where == '') ? " WHERE " : $where." AND "; 
				$where = $where." ee.encuesta = ".quote($filtro['encuesta']);
			}
			if (isset($filtro['habilitacion'])) {
				$where = ($where == '') ? " WHERE " : $where." AND "; 
				$where = $where." ee.habilitacion = ".quote($filtro['habilitacion']);
			}
			if (isset($filtro['fecha_desde'])) {
				$where = ($where == '') ? " WHERE " : $where." AND "; 
				$where = $where." et.fecha >= ".quote($filtro['fecha_desde']);
			}
			if (isset($filtro['fecha_hasta'])) {
				$where = ($where == '') ? " WHERE " : $where." AND "; 
				$where = $where." et.fecha <= ".quote($filtro['fecha_hasta']);
			}
		
		}
		return $where;
	}
	
	function resultados_por_encuestado($filtro=null)
	{
		if (isset($filtro['inconclusas']) && $filtro['inconclusas'] == 1) {
			$join = " LEFT JOIN ";
		} else {
			$join = " INNER JOIN ";
		}
		
		$sql = "SELECT 
					rc.encuesta,
					rc.bloque,
					rc.numero,
					rc.bloque_nombre,
					rc.pregunta,
					rc.pregunta_nombre,
					rc.tabla_asociada,
					rc.tabla_asociada_codigo,
					rc.tabla_asociada_descripcion,
					rc.respuesta,
					rc.respuesta_valor,
					rc.encuesta_encabezado,
					ee.formulario_encabezado,
					us.encuestado,
					us.usuario,
					cp.componente,
					(substr(ee.fecha::text,9,2)||'/'||substr(ee.fecha::text,6,2)||'/'||substr(ee.fecha::text,1,4)) AS fecha_inicio,
					(substr(et.fecha::text,9,2)||'/'||substr(et.fecha::text,6,2)||'/'||substr(et.fecha::text,1,4)) AS fecha_fin
				FROM
					respuestas_completas_view rc 
					INNER JOIN sge_encuestas_realizada_encabezado ee ON ee.encuesta_encabezado = rc.encuesta_encabezado
					INNER JOIN sge_encuestado us ON us.encuestado = ee.encuestado 
				    INNER JOIN sge_pregunta p ON (p.pregunta = rc.pregunta) 
			      	INNER JOIN sge_componente_pregunta cp ON cp.numero = p.numero
		            $join sge_encuestas_terminada et ON ee.encuesta_encabezado = et.encuesta_encabezado
			";
		
		$where = $this->obtener_where_encuestados($filtro);
		toba::logger()->debug($sql.$where);
		return consultar_fuente($sql.$where ." ORDER BY encuesta_encabezado, usuario, encuesta, numero");
	}
	
		
	function obtener_where_encuestados($filtro)
	{
		//los basicos: encuesta, habilitacion, fecha_desde, fecha_hasta
		$where = $this->obtener_where($filtro); 
		if (isset($filtro)) {
			if (isset($filtro['usuario'])) {
				$where = ($where == '') ? " WHERE " : $where." AND ";
				$where = $where." us.usuario = ".quote($filtro['usuario']);
			}
			//Los filtros que siguen se usaban, pero ahora no son necesarios
			//los saco por si se siguen usando desde otro lado.
			//Si se usan en otro lado y son necesarios, descomentar la excepcion.
			if (isset($filtro['numero'])) {
				throw new toba_error("No se usa mas este filtro por eficiencia");
				$where = ($where == '') ? " WHERE " : $where." AND ";
				$where = $where." rc.numero = ".quote($filtro['numero']);
			}
			
			if (isset($filtro['componentes'])) {
				throw new toba_error("No se usa mas este filtro por eficiencia");
				if ($filtro['componentes']=='multiples') {
					
					$where = ($where == '') ? " WHERE " : $where." AND ";
					$where = $where." (cp.componente = 'list' OR cp.componente='check')";
				}
				if ($filtro['componentes']=='unicas') {
					$where = ($where == '') ? " WHERE " : $where." AND ";
					$where = $where." (cp.componente != 'list' AND cp.componente!='check')";
				}
			}

			if (isset($filtro['encuesta_encabezado'])) {
				throw new toba_error("No se usa mas este filtro por eficiencia");
				$where = ($where == '') ? " WHERE " : $where." AND ";
				$where = $where." rc.encuesta_encabezado = ".quote($filtro['encuesta_encabezado']);
			} 
		}
		return $where;
	}
	
	function get_planilla_reporte($habilitacion, $concepto)
	{
		$sql = "SELECT 
				fh.nombre as nombre_f,
				al.concepto, 
				al.descripcion as concepto_desc, 
				fd.encuesta, 
				fd.elemento,
				el.elemento_externo,
				el.descripcion as elemento_desc,
				fd.orden
				FROM 
					sge_formulario_detalle fd 
				INNER JOIN 
					sge_formulario_habilitado as fh ON fd.formulario_habilitado = fh.formulario_habilitado
				LEFT JOIN
					sge_elemento as el ON fd.elemento = el.elemento
				INNER JOIN
					sge_concepto as al ON fh.concepto = al.concepto
				WHERE 
					fh.habilitacion = " .(int)$habilitacion 
				   . " AND fh.concepto = " .(int)$concepto
				." ORDER BY orden asc";
		return kolla::db()->consultar($sql);	
	}
	
	/**
	    Retorna los datos de la respuesta almacenada en tabla asociada
	*/	
	function get_datos_respuesta_tabla_asociada($encuesta, $tabla_asociada, $codigo) 	
	{
		$sql = "SELECT 
					ed.numero,
					ed.bloque,
					ed.pregunta,
					ed.obligatoria,
					p.nombre,
					cp.componente AS componente,
					p.tabla_asociada,
					t.codigo AS respuesta,
					t.codigo AS respuesta_orden,
					t.nombre AS respuesta_valor,
					'N' AS respuesta_seleccionada
				FROM 
					sge_encuesta_definicion ed 
						INNER JOIN sge_pregunta p ON (ed.pregunta = p.pregunta)
						INNER JOIN sge_componente_pregunta cp ON (p.numero = cp.numero),
					$tabla_asociada t 
				WHERE 
					ed.encuesta = ".quote($encuesta)." AND
					p.tabla_asociada = ".quote($tabla_asociada)." AND
					t.codigo = ".quote($codigo)."
				ORDER BY 1,3,4
			";
		return consultar_fuente($sql);
    }
    
    function get_reportes_usuario($where)
    {
        $usuario = toba::db()->quote(toba::usuario()->get_id());
        
        $sql = "SELECT      sge_reporte_exportado.*,
                            sge_reporte_tipo.nombre AS reporte_tipo_nombre,
                            sge_formulario_habilitado.nombre AS formulario_habilitado_nombre,
                            sge_concepto.descripcion AS concepto_descripcion,
                            sge_encuesta_atributo.nombre AS encuesta_nombre,
                            sge_elemento.descripcion AS elemento_descripcion,
                            sge_habilitacion.descripcion AS habilitacion_descripcion
				FROM        sge_reporte_exportado
	                            JOIN sge_reporte_tipo ON (sge_reporte_tipo.reporte_tipo = sge_reporte_exportado.reporte_tipo)
	                            JOIN sge_formulario_habilitado ON (sge_formulario_habilitado.formulario_habilitado = sge_reporte_exportado.formulario_habilitado)
	                            JOIN sge_habilitacion ON (sge_formulario_habilitado.habilitacion = sge_habilitacion.habilitacion)
	                            LEFT OUTER JOIN sge_concepto ON (sge_concepto.concepto = sge_formulario_habilitado.concepto)
	                            LEFT OUTER JOIN sge_encuesta_atributo ON (sge_encuesta_atributo.encuesta = sge_reporte_exportado.encuesta)
	                            LEFT OUTER JOIN sge_elemento ON (sge_elemento.elemento = sge_reporte_exportado.elemento)
				WHERE       usuario = $usuario
                AND 		$where
                ORDER BY    exportado_codigo DESC";
        
        //Esta consulta no debe pasar por el filtrado por perfiles ya que se filtra por usuario logueado
        return toba::db()->consultar($sql);
    }

    function get_reportes_usuario_con_ug($where)
    {
        $usuario = toba::db()->quote(toba::usuario()->get_id());

        $sql = "SELECT      sge_reporte_exportado.*,
                            to_date(SUBSTRING(sge_reporte_exportado.archivo, 1, 8), 'YYYYMMDD') as fecha_reporte,
                            sge_reporte_tipo.nombre AS reporte_tipo_nombre,
                            sge_formulario_habilitado.nombre AS formulario_habilitado_nombre,
                            sge_concepto.descripcion AS concepto_descripcion,
                            sge_encuesta_atributo.nombre AS encuesta_nombre,
                            sge_elemento.descripcion AS elemento_descripcion,
                            sge_habilitacion.descripcion AS habilitacion_descripcion
				FROM        sge_reporte_exportado
	                            JOIN sge_reporte_tipo ON (sge_reporte_tipo.reporte_tipo = sge_reporte_exportado.reporte_tipo)
	                            LEFT JOIN sge_formulario_habilitado ON (sge_formulario_habilitado.formulario_habilitado = sge_reporte_exportado.formulario_habilitado)
	                            LEFT JOIN sge_habilitacion ON (sge_reporte_exportado.habilitacion = sge_habilitacion.habilitacion)
	                            JOIN sge_unidad_gestion as ug ON (ug.unidad_gestion = sge_habilitacion.unidad_gestion)
	                            LEFT OUTER JOIN sge_concepto ON (sge_concepto.concepto = sge_formulario_habilitado.concepto)
	                            LEFT OUTER JOIN sge_encuesta_atributo ON (sge_encuesta_atributo.encuesta = sge_reporte_exportado.encuesta)
	                            LEFT OUTER JOIN sge_elemento ON (sge_elemento.elemento = sge_reporte_exportado.elemento)
				WHERE       usuario = $usuario
                AND 		$where
                ORDER BY    exportado_codigo DESC";

        //Esta consulta no debe pasar por el filtrado por perfiles ya que se filtra por usuario logueado
        return toba::db()->consultar($sql);
    }
	
	function obtener_filtros($codigo)
	{	
		$sql = "SELECT	*
				FROM 	sge_reporte_exportado re 
				WHERE 	re.exportado_codigo = ".quote($codigo)."
				";
		return consultar_fuente($sql);
	}
	
	function obtener_nombre_archivo($codigo)
	{
		$sql = "SELECT	archivo
				FROM 	sge_reporte_exportado re 
				WHERE 	re.exportado_codigo = ".quote($codigo)."
				";
		
		$r = kolla_db::consultar_fila($sql);
		
		if ( $r ) {
			return $r['archivo'];
		} else {
            return null;
        }
	}

	function guardar_nombre_archivo($codigo, $nombre)
	{
		$sql = "UPDATE 	sge_reporte_exportados
   				SET 	archivo = ".quote($nombre)."
 				WHERE 	exportado_codigo = ".quote($codigo)."
				";
		kolla_db::ejecutar($sql);
	}
	
    function get_reportes_tipos() 
	{
		$sql = "SELECT	reporte_tipo,
	  					nombre,
						descripcion
				FROM 	sge_reporte_tipo rt 
				";
		return consultar_fuente($sql);
	}

    function resumen_encuesta($filtro=null) 
	{
        $w_habilitacion = (isset($filtro['habilitacion'])) ? " AND sfh.habilitacion = ".quote($filtro['habilitacion']) : "" ;
        $w_form_hab_sfh = (isset($filtro['formulario_habilitado'])) ? " AND sfh.formulario_habilitado = ".quote($filtro['formulario_habilitado']) : "" ;
        $w_form_hab_srf = (isset($filtro['formulario_habilitado'])) ? " AND srf.formulario_habilitado = ".quote($filtro['formulario_habilitado']) : "" ;
        $w_fecha  = "";
        $w_fecha .= (isset($filtro['fecha_desde'])) ? " AND srf.fecha >= ".quote($filtro['fecha_desde']) : "";
        $w_fecha .= (isset($filtro['fecha_hasta'])) ? " AND srf.fecha <= ".quote($filtro['fecha_hasta']) : "";
        
        $sql_habilitados = "SELECT COUNT (*) AS habilitados, sfh.nombre, sfh.formulario_habilitado, h.externa, h.anonima,
                                    CASE 
                                    WHEN sc.descripcion IS NULL THEN sfh.nombre || ' (' || sgdef.nombre || ')' 
                                    ELSE sfh.nombre || ' (' || sgdef.nombre || '+' || sc.descripcion || ')' 
                                    END AS descripcion
                FROM sge_formulario_habilitado sfh 
                    INNER JOIN sge_grupo_habilitado sgh ON (sfh.formulario_habilitado = sgh.formulario_habilitado)
                    LEFT JOIN sge_grupo_detalle sgd ON (sgh.grupo = sgd.grupo)
                    INNER JOIN sge_habilitacion h ON (sfh.habilitacion = h.habilitacion)
                    INNER JOIN sge_grupo_definicion sgdef ON (sgdef.grupo = sgh.grupo)
                    LEFT JOIN sge_concepto sc ON (sc.concepto = sfh.concepto)
                WHERE true ".$w_habilitacion.$w_form_hab_sfh."
                GROUP BY sfh.nombre, sfh.formulario_habilitado, h.externa, h.anonima, sgdef.nombre, sc.descripcion;";
        
        $sql_finalizados = "SELECT COUNT(*) AS finalizadas, sfh.formulario_habilitado, h.externa, h.anonima
                FROM sge_formulario_habilitado sfh 
                    INNER JOIN sge_respondido_formulario srf ON (sfh.formulario_habilitado = srf.formulario_habilitado)
                    LEFT JOIN sge_respondido_encuestado sre ON (srf.respondido_formulario = sre.respondido_formulario)
                    INNER JOIN sge_habilitacion h ON (sfh.habilitacion = h.habilitacion)
                WHERE (srf.terminado = 'S' OR sre.terminado = 'S') ".
                $w_habilitacion.$w_form_hab_srf.$w_fecha."
                GROUP BY sfh.formulario_habilitado, h.externa, h.anonima;";
        
		$sql_incompletos = "SELECT COUNT (*) AS incompletas, sfh.formulario_habilitado, h.externa, h.anonima
                FROM sge_formulario_habilitado sfh 
                    INNER JOIN sge_respondido_formulario srf ON (sfh.formulario_habilitado = srf.formulario_habilitado)
                    LEFT JOIN sge_respondido_encuestado sre ON (srf.respondido_formulario = sre.respondido_formulario)
                    INNER JOIN sge_habilitacion h ON (sfh.habilitacion = h.habilitacion)
                WHERE (srf.terminado = 'N' OR sre.terminado = 'N') ".
                $w_habilitacion.$w_form_hab_srf.$w_fecha."
                GROUP BY sfh.formulario_habilitado, h.externa, h.anonima;";
        
		$sql_ignorados = "SELECT COUNT (*) AS ignorados, sfh.formulario_habilitado, h.externa, h.anonima
                FROM sge_formulario_habilitado sfh 
                    INNER JOIN sge_respondido_formulario srf ON (sfh.formulario_habilitado = srf.formulario_habilitado)
                    INNER JOIN sge_respondido_encuestado sre ON (srf.respondido_formulario = sre.respondido_formulario)
                    INNER JOIN sge_habilitacion h ON (sfh.habilitacion = h.habilitacion)
                WHERE sre.ignorado = 'S' ".
                $w_habilitacion.$w_form_hab_srf.$w_fecha."
                GROUP BY sfh.formulario_habilitado, h.externa, h.anonima;";

        $sql_encuestado_sin_rta = "SELECT COUNT (sgd.encuestado) AS sin_respuesta, sfh.formulario_habilitado, h.externa, h.anonima
                FROM sge_grupo_detalle sgd 
                    INNER JOIN sge_grupo_habilitado sgh ON (sgh.grupo = sgd.grupo)
                    INNER JOIN sge_formulario_habilitado sfh ON (sfh.formulario_habilitado = sgh.formulario_habilitado)
                    LEFT JOIN sge_respondido_encuestado sre ON (sre.formulario_habilitado = sfh.formulario_habilitado
                                    AND sre.encuestado = sgd.encuestado)
                    INNER JOIN sge_habilitacion h ON (sfh.habilitacion = h.habilitacion)
                WHERE sre.encuestado IS NULL ".$w_habilitacion.$w_form_hab_sfh."
                GROUP BY sfh.formulario_habilitado, h.externa, h.anonima;";
                
        //HABILITADOS 
        $habilitados = kolla_db::consultar($sql_habilitados);
        
        if (empty($habilitados)) {
            $resultados[0]['formulario_habilitado'] = 0;
            $resultados[0]['nombre'] = '';
            $resultados[0]['habilitados'] = 0;
            $resultados[0]['descripcion'] = "";
        } else {
            foreach ($habilitados as $fh) {
                $form_hab = $fh['formulario_habilitado'];
                if ($fh['externa']=='S' || $fh['anonima']=='S') { $fh['habilitados'] = '-Información no disponible-'; };
                $resultados[$form_hab] = $fh;
            }
       
            //FINALIZADOS        
            $finalizados = kolla_db::consultar($sql_finalizados);
            foreach ($finalizados as $fh) {
                $form_hab = $fh['formulario_habilitado'];
                $merge = array_merge($resultados[$form_hab], $fh);
                $resultados[$form_hab] = $merge;
            }

            //INCOMPLETOS
            $incompletos = kolla_db::consultar($sql_incompletos);
            foreach ($incompletos as $fh) {
                $form_hab = $fh['formulario_habilitado'];
                if ($fh['externa']=='S' || $fh['anonima']=='S') { $fh['incompletas'] = '-Información no disponible-'; };
                $merge = array_merge($resultados[$form_hab], $fh);
                $resultados[$form_hab] = $merge;
            }

            //IGNORADAS
            $ignorados = kolla_db::consultar($sql_ignorados);
            foreach ($ignorados as $fh) {
                $form_hab = $fh['formulario_habilitado'];
                $merge = array_merge($resultados[$form_hab], $fh);
                $resultados[$form_hab] = $merge;
            }       

            //SIN RESPUESTA
            $sin_respuesta = kolla_db::consultar($sql_encuestado_sin_rta);
            foreach ($sin_respuesta as $fh) {
                $form_hab = $fh['formulario_habilitado'];
                if ($fh['externa']=='S' || $fh['anonima']=='S') { $fh['sin_respuesta'] = '-Información no disponible-'; };
                $merge = array_merge($resultados[$form_hab], $fh);
                $resultados[$form_hab] = $merge;
            }
        }
        
        return $resultados;
	}
	
	/**
	 * 
	 * Inserta un registro en la tabla para los reportes exportados.
	 * 
	 * @return integer id del reporte exportado insertado.
	 */
	function insertar_reporte_exportado($formulario_habilitado, $reporte_tipo, $fecha_desde = null, $fecha_hasta = null, $inconclusas = null, $multiples = null, $archivo = null, $codigos = null)
	{
		$into = " formulario_habilitado, reporte_tipo ";
        $values = kolla_db::quote($formulario_habilitado).", ".kolla_db::quote($reporte_tipo);
        
        $into   .= is_null($fecha_desde) ? '' : ", fecha_desde ";
		$values .= is_null($fecha_desde) ? '' : ", ".kolla_db::quote($fecha_desde);
        
        $into   .= is_null($fecha_hasta) ? '' : ", fecha_hasta ";
		$values .= is_null($fecha_hasta) ? '' : ", ".kolla_db::quote($fecha_hasta);
        
        $into   .= ", inconclusas ";
		$values .= is_null($inconclusas) ? ', 0' : ", ".kolla_db::quote($inconclusas);
		
        $into   .= ", multiples ";
		$values .= is_null($multiples) ? ', 0' : ", ".kolla_db::quote($multiples);
        
        $into   .= is_null($archivo) ? '' : ", archivo ";
		$values .= is_null($archivo) ? '' : ", ".kolla_db::quote($archivo);
        
        $into   .= ", codigos ";
		$values .= is_null($codigos) ? ', 0' : ", ".kolla_db::quote($codigos);
        
        $sql = "INSERT INTO sge_reporte_exportado ( ".$into." ) VALUES ( ".$values." )";
		kolla_db::ejecutar($sql);
		return toba::db()->recuperar_secuencia('sge_reporte_exportado_seq');
	}
    
    function get_reportes_descarga_filtros_tipos() 
    {
        $sql = "SELECT	rt.reporte_tipo,
                        rt.nombre,
                        rt.descripcion
                FROM 	sge_reporte_tipo rt 
                WHERE   rt.reporte_tipo < 3;
                ";
        
        return kolla_db::consultar($sql);
    }
    
    function get_reportes_descarga_habilitacion_tipos() 
    {
        $sql = "SELECT	rt.reporte_tipo,
                        rt.nombre,
                        rt.descripcion
                FROM 	sge_reporte_tipo rt
                WHERE   rt.reporte_tipo > 3;
                ";
        
        return kolla_db::consultar($sql);
    }
	
}
?>