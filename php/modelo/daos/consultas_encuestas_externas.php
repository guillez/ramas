<?php

class consultas_encuestas_externas
{
		
	function get_sistemas_externos($filtro=null) 
	{
            $partes = array('TRUE');
		if ( $filtro ) {
            if ( isset($filtro['nombre']) && $filtro['nombre'] != '' ) {
                $partes[] = 'nombre = '. toba::db()->quote($filtro['nombre']);
            }
            if ( isset($filtro['usuario']) && $filtro['usuario'] != '' ) {
                $partes[] = 'usuario = '. toba::db()->quote($filtro['usuario']);
            }
            if ( isset($filtro['sistema']) && $filtro['sistema'] != '' ) {
                $partes[] = 'sistema = '. toba::db()->quote($filtro['sistema']);
            }
            if ( isset($filtro['estado']) && $filtro['estado'] != '' ) {
                $partes[] = 'sse.estado = '. toba::db()->quote($filtro['estado']);
            }
		}
        $where = implode(' AND ', $partes);
        
		$sql = "SELECT  sse.sistema,
                        sse.nombre,
                        sse.usuario,
                        sse.estado,
                        CASE    WHEN (sse.estado::text = 'A') 
                                THEN 'Activo'
                                ELSE 'Baja'
                        END as estado_descripcion
				FROM    sge_sistema_externo sse
				WHERE   $where";
		return kolla_db::consultar($sql);
	}
	
	/*
	 Retorna la lista de habilitaciones con encuestas asociadas (todas, no solo de las encuestas activas)
	*/
	function get_habilitaciones_where($where=null)
	{
		//VER PARA SINCRONIZAR CON EL GET_HABILITACIONES DE CONSULTAS_ENCUESTAS

		$where = isset($where) ? " AND ".$where : '';
		
		$sql = "SELECT		eh.habilitacion	AS habilitacion,
							eh.fecha_desde	AS fecha_desde,
							eh.fecha_hasta	AS fecha_hasta,
							(substr(fecha_desde::text,9,2)||'/'||substr(fecha_desde::text,6,2)||'/'||
							substr(fecha_desde::text,1,4)||' - '|| substr(fecha_hasta::text,9,2)||'/'||
							substr(fecha_hasta::text,6,2)||'/'||substr(fecha_hasta::text,1,4)||
							' -- (ver formulario)')
							AS descripcion
				FROM		sge_habilitacion eh
				WHERE		true $where
				ORDER BY 	fecha_desde
			;";
		
		return consultar_fuente($sql);
	}
	
	//REEMPLAZAR POR GET FORMULARIOS POR HABILITACION. EL CONCEPTO VA A PODER SER NULO
	function get_conceptos_por_habilitacion($habilitacion=null)
	{
		$where = '';
		if (isset($habilitacion))
		{
			$where .= " AND habilitacion = ".$habilitacion;
		}

		$sql = "SELECT
					sc.concepto,
					sc.descripcion		AS concepto_descripcion,
					sc.sistema,
					sc.concepto_externo,
					sfh.formulario_habilitado,
					sfh.nombre AS formulario_nombre,
					sfh.habilitacion
				FROM sge_concepto sc INNER JOIN 
						sge_formulario_habilitado sfh ON sc.concepto = sfh.concepto
				WHERE 
					true $where;
		";
		return consultar_fuente($sql);
	}
	
	function get_grupo_encuestados_sistema_externo($where=null)
	{
		$where = isset($where) ? " WHERE $where " : '';
		$sql = "SELECT
					sge.grupo				AS grupo_encuestado,
					sge.nombre				AS nombre,
					sge.estado				AS estado,
					sge.externo				AS externo,
					sge.descripcion			AS descripcion,
					sge.externo				AS externo,
					CASE WHEN sge.descripcion != ''
					THEN sge.nombre || ' - ' || sge.descripcion 
					ELSE sge.nombre 
					END AS grupo_nombre_descripcion,  
					se.encuestado			AS encuestado,
					se.usuario				AS usuario,
					sse.sistema				AS sistema,
					sse.nombre				AS nombre_sistema,
					sse.estado				AS estado_sistema
				FROM 
					sge_grupo_definicion sge  
						INNER JOIN sge_grupo_detalle seg ON (sge.grupo = seg.grupo)
						INNER JOIN sge_encuestado se ON (seg.encuestado = se.encuestado)
						LEFT JOIN sge_sistema_externo sse ON (sse.usuario = se.usuario)						
					$where
				ORDER BY nombre
		;";	
		return consultar_fuente($sql);
	}
	
	function get_conceptos($where)
	{
		$sql = "SELECT		sge_concepto.concepto,
							sge_concepto.concepto_externo,
							sge_concepto.sistema,
							sge_concepto.descripcion,
							sge_sistema_externo.nombre,
                                                        ug.nombre AS ug_nombre
				FROM 		sge_concepto
						LEFT OUTER JOIN sge_sistema_externo ON sge_concepto.sistema = sge_sistema_externo.sistema
                                                LEFT OUTER JOIN sge_unidad_gestion AS ug ON (sge_concepto.unidad_gestion = ug.unidad_gestion)
				WHERE		$where
				ORDER BY	sge_sistema_externo.nombre,
							sge_concepto.descripcion
				";
		
		return kolla_db::consultar($sql);
	}
		
	function get_elementos($where)
	{
		$sql = "SELECT      sge_elemento.elemento,
                            sge_elemento.elemento_externo,
                            sge_elemento.sistema,
                            sge_elemento.descripcion,
                            sge_elemento.url_img,
                            sge_sistema_externo.nombre,
                            sge_sistema_externo.nombre AS sistema_descripcion,
                            ug.nombre AS ug_nombre
                FROM        sge_elemento
                            LEFT OUTER JOIN sge_sistema_externo ON sge_elemento.sistema = sge_sistema_externo.sistema
                            LEFT OUTER JOIN sge_unidad_gestion AS ug ON (sge_elemento.unidad_gestion = ug.unidad_gestion)
                WHERE       $where
                ORDER BY    sge_sistema_externo.nombre,
                            sge_elemento.descripcion";
		
		return kolla_db::consultar($sql);
	}

}

?>