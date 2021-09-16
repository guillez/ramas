<?php
//MGN = Módulo de gestión de Notificaciones
class consultas_mgn
{
	function get_envios_mail($params)
	{
		$where = abm::get_where($params);
		
		$sql = "SELECT      mgn_mail.mail,
                            mgn_mail.nombre,
                            mgn_mail.asunto,
                            mgn_mail.contenido,
                            mgn_mail.fecha_envio,
                            mgn_mail.hora_envio,
                            TO_CHAR(mgn_mail.fecha_envio, 'dd/mm/yyyy') || ' - ' || mgn_mail.hora_envio || ' horas' AS descripcion
                FROM        mgn_mail
                WHERE       $where
                ORDER BY    mgn_mail.fecha_envio,
                            mgn_mail.hora_envio
            ";
		
		return kolla_db::consultar($sql);
	}
	
    function get_envios_mail_combo_por_fh($formulario_habilitado)
    {
        $formulario_habilitado = kolla_db::quote($formulario_habilitado);
		
        $sql = "SELECT      DISTINCT mgn_mail.mail,
                            mgn_mail.nombre || ' - ' || TO_CHAR(mgn_mail.fecha_envio, 'dd/mm/yyyy') || ' - ' || mgn_mail.hora_envio || ' hs.' AS descripcion
                FROM        mgn_mail,
                            mgn_mail_formulario_habilitado
                WHERE       mgn_mail_formulario_habilitado.mail = mgn_mail.mail
                AND         mgn_mail_formulario_habilitado.formulario_habilitado = $formulario_habilitado
                ORDER BY    descripcion
            ";
		
		return kolla_db::consultar($sql);
    }
    
    function get_envios_mail_sin_habilitacion()
	{
		$sql = "SELECT      mgn_mail.mail,
                            mgn_mail.nombre || ' - ' || TO_CHAR(mgn_mail.fecha_envio, 'dd/mm/yyyy') || ' - ' || mgn_mail.hora_envio || ' horas' AS descripcion
                FROM        mgn_mail
                WHERE       mgn_mail.mail NOT IN (
                                                    SELECT mgn_mail_formulario_habilitado.mail
                                                    FROM   mgn_mail_formulario_habilitado
                                                 )
                ORDER BY    descripcion
            ";
		
		return kolla_db::consultar($sql);
	}
    
	function get_datos_logs_envio($where=null) 
	{
		$where = isset($where) ? ' WHERE '.$where : '';
		
        $sql = "SELECT 	log,
						mail,
						encuestado,
						mensaje,
						hash
				FROM	mgn_log_envio
				$where
				";
        
		return consultar_fuente($sql);
	}	
	
    function get_reporte_envios($where) 
	{
		$sql = "SELECT      h.descripcion                                    AS desc_habilitacion,
                            fh.nombre || ' - ' || COALESCE(c.descripcion,'') AS desc_formulario,
                            to_char(m.fecha_envio, '".kolla_sql::formato_fecha_visual."') AS fecha_envio,
                            to_char(h.fecha_desde, '".kolla_sql::formato_fecha_visual."') || ' - ' || to_char(h.fecha_hasta, '".kolla_sql::formato_fecha_visual."') AS fechas,
                            le.mensaje                          AS mensaje,
                            (e.apellidos || ', ' || e.nombres)  AS nombre_encuestado,
                            e.email                             AS email,
                            dt.descripcion                      AS documento_tipo,
                            e.documento_numero                  AS documento_numero
                FROM        mgn_mail AS m
                                LEFT JOIN mgn_log_envio AS le ON m.mail = le.mail
                                LEFT JOIN sge_encuestado AS e ON (le.encuestado = e.encuestado)
                                LEFT JOIN mgn_mail_formulario_habilitado ON m.mail = mgn_mail_formulario_habilitado.mail AND mgn_mail_formulario_habilitado.encuestado = e.encuestado
                                LEFT JOIN sge_documento_tipo AS dt ON e.documento_tipo = dt.documento_tipo
                                LEFT JOIN sge_formulario_habilitado AS fh ON mgn_mail_formulario_habilitado.formulario_habilitado = fh.formulario_habilitado
                                LEFT JOIN sge_habilitacion AS h ON fh.habilitacion = h.habilitacion
                                LEFT JOIN sge_concepto AS c ON (fh.concepto = c.concepto)
                WHERE       TRUE
                AND         $where
                ORDER BY    fh.habilitacion,
                            m.fecha_envio
            ";
        
		return kolla_db::consultar($sql);
	}

    function get_reporte_envios_con_ug($where)
    {
        $sql = "SELECT      h.descripcion                                    AS desc_habilitacion,
                            fh.nombre || ' - ' || COALESCE(c.descripcion,'') AS desc_formulario,
                            to_char(m.fecha_envio, '".kolla_sql::formato_fecha_visual."') AS fecha_envio,
                            to_char(h.fecha_desde, '".kolla_sql::formato_fecha_visual."') || ' - ' || to_char(h.fecha_hasta, '".kolla_sql::formato_fecha_visual."') AS fechas,
                            le.mensaje                          AS mensaje,
                            (e.apellidos || ', ' || e.nombres)  AS nombre_encuestado,
                            e.email                             AS email,
                            dt.descripcion                      AS documento_tipo,
                            e.documento_numero                  AS documento_numero
                FROM        mgn_mail AS m
                                LEFT JOIN mgn_log_envio AS le ON m.mail = le.mail
                                LEFT JOIN sge_encuestado AS e ON (le.encuestado = e.encuestado)
                                LEFT JOIN mgn_mail_formulario_habilitado ON m.mail = mgn_mail_formulario_habilitado.mail AND mgn_mail_formulario_habilitado.encuestado = e.encuestado
                                LEFT JOIN sge_documento_tipo AS dt ON e.documento_tipo = dt.documento_tipo
                                LEFT JOIN sge_formulario_habilitado AS fh ON mgn_mail_formulario_habilitado.formulario_habilitado = fh.formulario_habilitado
                                LEFT JOIN sge_habilitacion AS h ON fh.habilitacion = h.habilitacion
                                LEFT JOIN sge_concepto AS c ON (fh.concepto = c.concepto)
                WHERE       TRUE
                AND         $where
                ORDER BY    fh.habilitacion,
                            m.fecha_envio
            ";

        return kolla_db::consultar($sql);
    }

	function get_datos_ws($id=null) 
	{
		$where = isset($id) ? ' WHERE conexion = '.$id : '';
		
        $sql = "SELECT 	sge_ws_conexion.conexion,
                        sge_ws_conexion.unidad_gestion,
                        sge_ws_conexion.conexion_nombre,
                        sge_ws_conexion.ws_url,
                        sge_ws_conexion.ws_user,
                        sge_ws_conexion.ws_clave,
                        sge_ws_conexion.activa,
                        sge_ws_conexion.ws_tipo
				FROM 	sge_ws_conexion
				$where 
				";
        
		return consultar_fuente($sql);
	}
    
}
?>