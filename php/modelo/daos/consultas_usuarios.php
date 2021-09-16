<?php

class consultas_usuarios
{
	function get_listado($where = null)
	{
		$where = isset($where) ? " WHERE $where" : '';
		
		$sql = "SELECT	sge_grupo_definicion.grupo,
						sge_grupo_definicion.nombre,
						sge_grupo_definicion.estado,
						sge_grupo_definicion.externo,
					 	sge_grupo_definicion.descripcion
				FROM	sge_grupo_definicion
				$where
				ORDER BY nombre
				";
				
		return kolla_db::consultar($sql);
	}
	
	function get_codigo_encuestado($usuario)
	{
		$sql = "SELECT  e.encuestado as encuestado
                FROM    sge_encuestado AS e
                WHERE   e.usuario = ".kolla_db::quote($usuario);
		
		$rs = kolla_db::consultar_fila($sql);
		
		return $rs['encuestado'];
	}
	
	function es_guest_actual() 
	{
		$perfiles = toba::usuario()->get_perfiles_funcionales();
		foreach ($perfiles as $p) {
			if (($p == 'guest') || ($p == 'externo')) {
				return true;
			}
		}
		return false;
	}

	function es_admin_actual() 
	{
		$perfiles = toba::usuario()->get_perfiles_funcionales();
		foreach ($perfiles as $p) {
			if ($p == 'admin') {
				return true;
			}
		}
		return false;
	}
	
	function es_gestor_actual() 
	{
		$perfiles = toba::usuario()->get_perfiles_funcionales();
		foreach ($perfiles as $p) {
			if ($p == 'gestor') {
				return true;
			}
		}
		return false;
	}
	
	/*
	 * Retorna los grupos de encuestados
	 */
	function get_grupos_encuestados($where=null)
	{
		$where = isset($where) ? " AND $where" : '';
		
		$sql = "SELECT	sge_grupo_definicion.grupo	AS grupo,
						sge_grupo_definicion.nombre	|| ' (' || CASE
							WHEN sge_grupo_definicion.estado = 'A' THEN 'Activo'
							WHEN sge_grupo_definicion.estado = 'B' THEN 'Baja'
						END || ')' AS nombre,
						CASE
							WHEN sge_grupo_definicion.estado = 'A' THEN 'Activo'
							WHEN sge_grupo_definicion.estado = 'B' THEN 'Baja'
						END	AS estado,
                        sge_grupo_definicion.externo
				FROM 	sge_grupo_definicion
				WHERE sge_grupo_definicion.estado != 'O' $where 
				ORDER BY nombre
				";
				
		return kolla_db::consultar($sql);
	}
    
    function get_grupos_encuestados_x_unidad($unidad)
    {
        $where = "unidad_gestion = ".quote($unidad);
        return $this->get_grupos_encuestados($where);
    }
	
    function get_grupos_usuario_por_ug($encuestado, $ug)
    {
        $encuestado = kolla_db::quote($encuestado);
        $ug = kolla_db::quote($ug);
        
        $sql = "SELECT      sge_grupo_definicion.grupo,
                            sge_grupo_definicion.nombre
				FROM        sge_grupo_definicion,
                            sge_grupo_detalle
				WHERE       sge_grupo_definicion.grupo = sge_grupo_detalle.grupo
                AND         sge_grupo_detalle.encuestado = $encuestado
                AND         sge_grupo_definicion.unidad_gestion = $ug
				ORDER BY    sge_grupo_definicion.nombre
				";
				
		return kolla_db::consultar($sql);
    }
    
	function get_grupos_por_formulario($formulario_habilitado)
	{
		$formulario_habilitado = kolla_db::quote($formulario_habilitado);
		
		$sql = "
			SELECT
                def.grupo  AS grupo_encuestado,
                def.nombre AS nombre,
                def.estado AS estado
            FROM
                sge_grupo_definicion AS def,
                sge_grupo_habilitado AS g
			WHERE
				def.grupo = g.grupo AND 
				g.formulario_habilitado = $formulario_habilitado
		";
		
		return kolla_db::consultar($sql);
	}
	
	function get_grupos_por_habilitacion($habilitacion)
	{
		$habilitacion = kolla_db::quote($habilitacion);
		
		$sql = "
			SELECT
                def.grupo  AS grupo_encuestado,
                def.nombre AS nombre,
                def.estado AS estado
            FROM
                sge_grupo_definicion AS def
                    INNER JOIN sge_grupo_habilitado AS g ON (def.grupo = g.grupo)
                    INNER JOIN sge_formulario_habilitado AS fh ON (g.formulario_habilitado = fh.formulario_habilitado)  
                    INNER JOIN sge_habilitacion AS h ON fh.habilitacion = h.habilitacion AND
                                                        CASE WHEN h.externa = 'N' 
                                                            THEN h.unidad_gestion = def.unidad_gestion
                                                            ELSE TRUE
                                                        END
			WHERE h.habilitacion = $habilitacion
		";
		
		return kolla_db::consultar($sql);
	}    
    
    function get_encuestados_x_formulario($formulario_habilitado = array(), $habilitacion = null, $unidad_gestion = null, $terminada = null, $apellidos = null, $nombres = null, $usuario = null, $filtro = null)
	{
        $select    = '';
        $from      = '';
        $clausulas = array();
        
        if (isset($unidad_gestion)) {
            $clausulas[] = 'sge_habilitacion.unidad_gestion = '.kolla_db::quote($unidad_gestion);
            $from        = 'INNER JOIN sge_grupo_detalle         ON sge_encuestado.encuestado = sge_grupo_detalle.encuestado
                            INNER JOIN sge_grupo_definicion      ON sge_grupo_definicion.grupo = sge_grupo_detalle.grupo
                            INNER JOIN sge_grupo_habilitado      ON sge_grupo_definicion.grupo = sge_grupo_habilitado.grupo
                            INNER JOIN sge_formulario_habilitado ON sge_grupo_habilitado.formulario_habilitado = sge_formulario_habilitado.formulario_habilitado
                            INNER JOIN sge_habilitacion          ON sge_formulario_habilitado.habilitacion = sge_habilitacion.habilitacion
                            ';
            
            if (isset($habilitacion)) {
                $clausulas[] = 'sge_formulario_habilitado.habilitacion = '.kolla_db::quote($habilitacion);
            }
            
            if (isset($terminada)) {
                $select = ",CASE
                                WHEN sge_respondido_encuestado.terminado = 'S' THEN 'Sí'
                                ELSE 'No'
                            END	AS terminada";
                $from  .= ' LEFT JOIN sge_respondido_encuestado ON (sge_respondido_encuestado.formulario_habilitado = sge_formulario_habilitado.formulario_habilitado AND
                                      sge_respondido_encuestado.encuestado = sge_encuestado.encuestado)';
                
                if ($terminada != 'T') {
                    $clausula_terminada = "sge_respondido_encuestado.terminado = '$terminada'";
                    $clausulas[] = ($terminada == 'N') ? "($clausula_terminada OR sge_respondido_encuestado.terminado ISNULL)" : $clausula_terminada;
                }
            }
            
            if (!empty($formulario_habilitado)) {
                $clausulas[] = 'sge_grupo_habilitado.formulario_habilitado IN ('.implode(',', kolla_db::quote($formulario_habilitado)).')';
            }
        }
        
        if (isset($apellidos)) {
            $clausulas[] = kolla_sql::armar_condicion_compara_cadenas('sge_encuestado.apellidos', kolla_db::quote('%'.$apellidos.'%'));
        }
        
        if (isset($nombres)) {
            $clausulas[] = kolla_sql::armar_condicion_compara_cadenas('sge_encuestado.nombres', kolla_db::quote('%'.$nombres.'%'));
        }
        
        if (isset($usuario)) {
            $clausulas[] = kolla_sql::armar_condicion_compara_cadenas('sge_encuestado.usuario', kolla_db::quote('%'.$usuario.'%'));
        }
        
        if (isset($filtro)) {
            $clausulas[] = $filtro;
        }
        
		$where  = empty($clausulas) ? '' : 'WHERE  '.implode(' AND ', $clausulas);
        $schema = toba::instancia()->get_db()->get_schema();
		
		$sql = "SELECT  DISTINCT sge_encuestado.encuestado                           AS encuestado,
                        (sge_encuestado.apellidos || ', ' || sge_encuestado.nombres) AS nombre,
                        sge_encuestado.email                                         AS email,
                        sge_encuestado.usuario                                       AS usuario,
                        sge_documento_tipo.descripcion                               AS doc_tipo,
                        sge_encuestado.documento_numero                              AS doc_numero,
                        apex_usuario_grupo_acc.nombre                                AS perfil,
                        sge_encuestado.apellidos,
                        sge_encuestado.nombres,
                        CASE
							WHEN apex_usuario.bloqueado = '0' THEN 'No'
							ELSE 'Sí'
						END	AS bloqueado
                        $select
                FROM    sge_encuestado
                            INNER JOIN sge_documento_tipo ON sge_encuestado.documento_tipo = sge_documento_tipo.documento_tipo
                            INNER JOIN $schema.apex_usuario ON (sge_encuestado.usuario = apex_usuario.usuario)
                            JOIN $schema.apex_usuario_proyecto ON (sge_encuestado.usuario = apex_usuario_proyecto.usuario AND apex_usuario_proyecto.proyecto = 'kolla')
                            JOIN $schema.apex_usuario_grupo_acc ON (apex_usuario_proyecto.usuario_grupo_acc = apex_usuario_grupo_acc.usuario_grupo_acc AND apex_usuario_grupo_acc.proyecto ='kolla')
                        $from
                $where
                ";
        
        return kolla_db::consultar($sql);
	}
    
    function get_encuestados_x_formulario1($formulario_habilitado, $grupos=array())
	{
		$where = array('sge_grupo_habilitado.formulario_habilitado = '.kolla_db::quote($formulario_habilitado));
		
		if (!empty($grupos)) {
			$where[] = 'sge_grupo_habilitado.grupo IN ('.implode(',', kolla_db::quote($grupos)).')';
		}
		
		$where = implode(' AND ', $where);
		
		$sql = "SELECT  DISTINCT sge_encuestado.encuestado                           AS encuestado,
                        (sge_encuestado.apellidos || ', ' || sge_encuestado.nombres) AS nombre,
                        sge_encuestado.email                                         AS email,
                        sge_encuestado.usuario                                       AS usuario,
                        sge_documento_tipo.descripcion                               AS doc_tipo,
                        sge_encuestado.documento_numero                              AS doc_numero,
                        sge_encuestado.apellidos,
                        sge_encuestado.nombres
                FROM    sge_encuestado
                            INNER JOIN sge_documento_tipo   ON sge_encuestado.documento_tipo = sge_documento_tipo.documento_tipo
                            INNER JOIN sge_grupo_detalle    ON sge_encuestado.encuestado = sge_grupo_detalle.encuestado
                            INNER JOIN sge_grupo_definicion ON sge_grupo_definicion.grupo = sge_grupo_detalle.grupo
                            INNER JOIN sge_grupo_habilitado ON sge_grupo_definicion.grupo = sge_grupo_habilitado.grupo
                WHERE   $where
                ";
		
		return kolla_db::consultar($sql);
	}	
	
	function get_formularios_para_contestar($usuario = null, $vigente = true, $unidades_gestion = null)
	{
        $op_not     = $vigente ? '' : 'NOT';
		$select     = isset($usuario) ? ', '.kolla_db::quote($usuario).' AS usuario_encuestado' : '';
		$where_user = isset($usuario) ? 'e.usuario = '.kolla_db::quote($usuario) : '';
        $where_ug   = isset($unidades_gestion) ? "h.unidad_gestion IN (".implode(',', $unidades_gestion).')' : 'TRUE';
        
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
                AND         $op_not CURRENT_DATE BETWEEN h.fecha_desde AND h.fecha_hasta
                AND         $where_user
                AND         $where_ug
                ORDER BY    h.fecha_hasta
                ";
		
        return kolla_db::consultar($sql);
	}
    
    function get_formularios(/*$usuario = null, $vigente = true, $unidades_gestion = null*/)
	{
        $sql = " SELECT     DISTINCT h.habilitacion,
                            fh.formulario_habilitado AS formulario,
                            fh.nombre,
                            h.fecha_desde,
                            h.fecha_hasta,
                            h.anonima,
                            c.descripcion 								AS desc_concepto,
                            h.descripcion || ' - ' || fh.nombre 		AS descripcion_habilitacion_formulario,
                            h.descripcion || ' [' || fh.nombre || ']' 	AS formulario_descripcion_de_habilitacion
                FROM        sge_habilitacion h
                                INNER JOIN sge_formulario_habilitado fh ON h.habilitacion = fh.habilitacion
                                LEFT OUTER JOIN sge_concepto c          ON fh.concepto = c.concepto
                WHERE       fh.estado = 'A'
                ORDER BY    h.fecha_hasta
                ";
		
        return kolla_db::consultar($sql);
	}
	
	function get_encuestas_para_contestar($usuario=null)
	{
		//debo obtener todas las encuestas habilitadas para este encuestado, 
		//excepto aquellas que haya manifestado que ignora
		//Las habilitadas para el encuestado son las habilitadas para los grupos de encuestados a los que pertenezca
		$where = '';
		if (isset($usuario)) {
			$from = ",sge_encuestado e 
					INNER JOIN sge_grupo_detalle egrupos ON e.encuestado = egrupos.encuestado
					INNER JOIN sge_grupo_habilitado	gencuestas ON egrupos.grupo_encuestado = gencuestas.grupo_encuestado
					";
			
			$where = " AND e.usuario = ".kolla_db::quote($usuario)." AND
					gencuestas.habilitacion = eh.habilitacion
					";
			$sql_ignoradas = "(SELECT 
								eh.habilitacion, 
								eh.encuesta AS encuesta, 
								ea.nombre, 
								'S', 
								eh.fecha_desde, 
								eh.fecha_hasta,
								(substr(fecha_hasta::text,9,2)||'/'||substr(fecha_hasta::text,6,2)||'/'||substr(fecha_hasta::text,1,4)) AS hasta
							FROM 
								sge_encuesta_atributo ea 
								INNER JOIN sge_habilitacion eh ON ea.encuesta = eh.encuesta 
								INNER JOIN sge_encuestas_ignoradas ei ON eh.habilitacion = ei.habilitacion
								$from
							WHERE 
								ea.estado = 'A' AND
								(current_date BETWEEN eh.fecha_desde AND eh.fecha_hasta)
								$where
							ORDER BY eh.fecha_hasta
							)";
		}
		$sql = "(SELECT 
					eh.habilitacion, 
					eh.encuesta AS encuesta, 
					ea.nombre, 
					'S', 
					eh.fecha_desde, 
					eh.fecha_hasta,
					(substr(fecha_hasta::text,9,2)||'/'||substr(fecha_hasta::text,6,2)||'/'||substr(fecha_hasta::text,1,4)) AS hasta
				FROM 
					sge_encuesta_atributo ea 
					INNER JOIN sge_habilitacion eh ON ea.encuesta = eh.encuesta
					$from
				WHERE 
					ea.estado = 'A' AND
					(current_date BETWEEN eh.fecha_desde AND eh.fecha_hasta)
					 $where 
				ORDER BY eh.fecha_hasta)
		";
		if (isset($usuario)) { //entonces estoy filtrando todas las que no debo mostrar
			$sql .= " EXCEPT ".$sql_ignoradas;
		}
		return consultar_fuente($sql);
	}
		
	/*
	 * Retorna los datos del encuestado indicado y el encuestados_grupo al que pertenece
	 */
	function get_encuestado($grupo=null, $encuestado=null) 
	{
		$where = '';
		
		if ( isset($grupo) ) {
			$where = $where." AND eg.grupo = ".kolla_db::quote($grupo);
		}
		
		if ( isset($encuestado) ) {
			$where = $where." AND e.encuestado = ".kolla_db::quote($encuestado);
		}
		
		$sql = "
			SELECT 
				e.encuestado		AS encuestado,
				e.apellidos			AS apellidos,
				e.nombres			AS nombres,
				e.documento_tipo	AS documento_tipo,
				e.documento_numero	AS documento_numero,
				e.email				AS email,
				e.usuario			AS usuario,
				e.clave				AS clave,
				eg.grupo			AS grupo_encuestado
			FROM 
				sge_encuestado 		AS e,
				sge_grupo_detalle 	AS eg
			WHERE
				e.encuestado = eg.encuestado 
				$where
		";
		
		return kolla_db::consultar($sql);
	}	
		
	/*
	 * Retorna los datos del encuestado indicado y el encuestados_grupo al que pertenece
	 */	
	function get_encuestado_por_usuario($usuario) 
	{
        $usuario = kolla_db::quote($usuario);
        
		$sql = "
			SELECT 
				e.encuestado		AS encuestado,
				e.apellidos			AS apellidos,
				e.nombres			AS nombres,
				e.documento_tipo	AS documento_tipo,
				e.documento_numero	AS documento_numero,
				e.email				AS email,
				e.usuario			AS usuario,
				e.clave				AS clave,
				dt.descripcion 		AS tipo_doc
			FROM 
				sge_encuestado AS e 
				INNER JOIN sge_documento_tipo AS dt ON (e.documento_tipo = dt.documento_tipo)
			WHERE 
                e.usuario = $usuario
		";
		
		return kolla_db::consultar_fila($sql);
	}

	/*
	 * Retorna los datos del encuestado según el tipo y número de documento.
	 */	
	function get_encuestado_por_documento($tipo=null, $numero=null) 
	{
		$where	= 'true';
		$where .= (isset($numero) && $numero != '')	? ' AND sge_encuestado.documento_numero = '.kolla_db::quote($numero) : '';
		$where .= (isset($tipo) && $tipo != '') 	? ' AND sge_encuestado.documento_tipo = '.kolla_db::quote($tipo) : '';
		
		$sql = "SELECT 	sge_encuestado.encuestado,
						sge_encuestado.apellidos,
						sge_encuestado.nombres,
						sge_encuestado.documento_tipo,
						sge_encuestado.documento_numero,
						sge_encuestado.email,
						sge_encuestado.usuario,
						sge_encuestado.clave,
						sge_documento_tipo.descripcion AS tipo_doc
				FROM 	sge_encuestado INNER JOIN sge_documento_tipo ON (sge_encuestado.documento_tipo = sge_documento_tipo.documento_tipo)
				WHERE 	$where
				";
		
		return consultar_fuente($sql);
	}
    
    function es_grupo_utilizado_en_formulario($grupo)
    {
        $grupo = kolla_db::quote($grupo);
        
        $sql ="
            SELECT EXISTS(
                SELECT
                    1
                FROM 	
                    sge_grupo_habilitado sge
                WHERE
                    sge.grupo = $grupo
            ) as utilizado
        ";
        
        return kolla_db::consultar_fila($sql);
    }
	
	/*
	 * Retorna la cantidad de habilitaciones activas de encuestas asociadas al grupo de encuestados dado 
	 */		
	function get_habilitaciones_activas_grupo_encuestado($filtro=null)
	{
		$where = array('(current_date BETWEEN seh.fecha_desde AND seh.fecha_hasta)');
        
		if ( isset($filtro) && isset($filtro['grupo']) ) {
			$where[] = "sge.grupo = " . kolla_db::quote($filtro['grupo']);
		}
        
        $where = implode(' AND ', $where);
        
		$sql = "
            SELECT
                COUNT(seh.habilitacion) AS cantidad
            FROM 	
                sge_grupo_habilitado sge
                INNER JOIN sge_formulario_habilitado fhabilitado ON (sge.formulario_habilitado = fhabilitado.formulario_habilitado)
                INNER JOIN sge_habilitacion seh ON (fhabilitado.habilitacion = seh.habilitacion)
            WHERE        
                $where
         ";
        
		 $datos = kolla_db::consultar_fila($sql);
         
         return $datos['cantidad'];
	}
	
	function get_habilitaciones_grupo_encuestado($filtro=null)
	{
		$where = '';
		if (isset($filtro) && isset($filtro['grupo_encuestado'])) {
			$where = " WHERE sge.grupo_encuestado = ".$filtro['grupo_encuestado'];
		}
		$sql = "SELECT 	COUNT(sge.grupo_encuestado)
				FROM 	sge_grupo_habilitado sge 
				".$where;
		return consultar_fuente($sql);			
	}
	
	function get_lista_encuestados($where)
	{   
        $schema = toba::instancia()->get_db()->get_schema();

        $encuestados_privados = "(SELECT sge_grupo_detalle.encuestado
                                FROM sge_grupo_definicion 
                                INNER JOIN sge_grupo_detalle ON (sge_grupo_definicion.grupo = sge_grupo_detalle.grupo)
                                WHERE estado = 'O')
                                ";
        
        $sql = "SELECT      s.usuario,
                            s.apellidos || ', ' || s.nombres AS nombre,
                            g.usuario_grupo_acc AS nombre_grupo,
                            pd.nombre AS perfil_datos
                FROM        sge_encuestado AS s
                            INNER JOIN $schema.apex_usuario AS u ON (s.usuario = u.usuario)
                            INNER JOIN $schema.apex_usuario_proyecto AS p ON (u.usuario = p.usuario AND p.proyecto = 'kolla')
                            INNER JOIN $schema.apex_usuario_grupo_acc AS g ON (p.usuario_grupo_acc = g.usuario_grupo_acc AND g.proyecto = 'kolla')
                            LEFT JOIN $schema.apex_usuario_proyecto_perfil_datos AS pdu ON (pdu.usuario = u.usuario AND pdu.proyecto = 'kolla')
                            LEFT JOIN $schema.apex_usuario_perfil_datos AS pd ON (pd.usuario_perfil_datos = pdu.usuario_perfil_datos AND pd.proyecto = pdu.proyecto AND pd.proyecto = 'kolla')
                WHERE       $where AND s.encuestado NOT IN $encuestados_privados 
                ORDER BY    s.usuario";
        
		return kolla_db::consultar($sql);
	}
	
	function get_lista_encuestados_filtro($where = null)
	{
		$where .= $where ? '' : ' TRUE ';
		$schema = toba::instancia()->get_db()->get_schema();
		
		$sql = "SELECT      sge_encuestado.*,
                            sge_documento_tipo.descripcion AS documento_tipo_descripcion
				FROM        sge_encuestado
                            INNER JOIN sge_documento_tipo ON (sge_encuestado.documento_tipo = sge_documento_tipo.documento_tipo)
                            INNER JOIN $schema.apex_usuario ON (sge_encuestado.usuario = apex_usuario.usuario)
                            INNER JOIN $schema.apex_usuario_proyecto ON (apex_usuario.usuario = apex_usuario_proyecto.usuario AND apex_usuario_proyecto.proyecto = 'kolla')
				WHERE       $schema.apex_usuario_proyecto.usuario_grupo_acc = 'encuesta'
				AND			$where
				ORDER BY    apellidos ASC";
		
		return kolla_db::consultar($sql);
	}
	
	function existe_usuario($usuario)
	{
        $clave = array('usuario' => $usuario);
        return abm::existen_registros('sge_encuestado', $clave);
	}
	
	function existe_persona($documento_pais, $documento_tipo, $documento_numero, $usuario)
	{   
        $clave = array(
            'documento_pais'    => $documento_pais,
            'documento_tipo'    => $documento_tipo, 
            'documento_numero'  => $documento_numero, 
            'usuario'           => $usuario
        );
		
		return abm::existen_registros('sge_encuestado', $clave);
	}
    
    /**
     * Elimina datos de la tabla intermedia int_guarani_persona cuyas claves son fecha_proceso y usuario
     */
    function eliminar_datos_int_guarani_persona($personas=null)
	{
        $partes = array();
		if ( isset($personas) ) {
            $seleccion = array();
			foreach ($personas as $persona) {
                $fecha_proceso = kolla_db::quote($persona['fecha_proceso']);
                $usuario       = kolla_db::quote($persona['usuario']);
                $seleccion[] = "(fecha_proceso = $fecha_proceso AND usuario = $usuario)";
            }
			$partes[] = implode(' OR ', $seleccion);
		} else {
			$partes[] =	"resultado_proceso = 'E'";
		}
        
        $where = implode(' AND ', $partes);
        $sql = "DELETE
                FROM 	int_guarani_persona
                WHERE   $where";
		
		kolla_db::ejecutar($sql);
	}
	
    /**
     * Elimina datos de la tabla intermedia int_guarani_persona cuya clave es persona
     */
	function eliminar_datos_int_persona($personas=null)
	{
        $partes = array();
		if ( isset($personas) ) {
			$personas = kolla_arreglos::aplanar_matriz_sin_nulos($personas, 'persona');
			$partes[] = 'persona IN ('.implode(',', kolla_db::quote($personas)).')';
		} else {
			$partes[] =	"resultado_proceso = 'E'";
		}
        
        $where = implode(' AND ', $partes);
        $sql = "DELETE
                FROM 	int_persona
                WHERE   $where";
		
		kolla_db::ejecutar($sql);
	}
	
    /**
     * Obtiene los datos int_persona
     */
	function get_datos_int_persona($where)
	{	
		$sql = "SELECT		*	
				FROM 		int_persona
				WHERE		$where
				ORDER BY	usuario
        ";
		
		return kolla_db::consultar($sql);
	}
    
    /**
     * Obtiene los datos int_persona
     */
	function get_datos_int_guarani_persona($where)
	{	
		$sql = "SELECT		*	
				FROM 		int_guarani_persona
				WHERE		$where
				ORDER BY	usuario
        ";
		
		return kolla_db::consultar($sql);
	}
    
    function get_dato_int_guarani_persona($fecha_proceso, $usuario, $titulo_codigo=null)
    {
        $fecha_proceso = kolla_db::quote($fecha_proceso);
        $usuario       = kolla_db::quote($usuario);
        $titulo_codigo = kolla_db::quote($titulo_codigo);
		
		$sql = "SELECT	*
				FROM 	int_guarani_persona
				WHERE	fecha_proceso = $fecha_proceso
                                AND usuario = $usuario
                                AND titulo_codigo = $titulo_codigo
        ";
		
		return kolla_db::consultar_fila($sql);
    }
	
	function get_dato_int_persona($persona)
	{
		$persona = kolla_db::quote($persona);
		
		$sql = "SELECT	*
				FROM 	int_persona
				WHERE	persona = $persona
        ";
		
		return kolla_db::consultar_fila($sql);
	}
		    
    function ya_respondio($usuario, $formulario, $es_anonima=false)
	{
        if (self::es_guest_actual()) {
            $lista_encuestas = toba::memoria()->get_dato('lista_encuestas');
            return empty($lista_encuestas) ? false : in_array($formulario, $lista_encuestas);
        }
        
        $encuestado = self::get_codigo_encuestado($usuario);
        
        $clave = array(	'formulario_habilitado' => $formulario,
						'encuestado'            => $encuestado);
        
        if (!$es_anonima) {
            $clave['terminado'] = 'S';
        }

		return abm::existen_registros('sge_respondido_encuestado', $clave);
	}
    
    function get_tipos_usuarios()
    {
        $schema = toba::instancia()->get_db()->get_schema();
        
		$sql = "SELECT      usuario_grupo_acc,
                            nombre,
                            descripcion
				FROM        $schema.apex_usuario_grupo_acc
				WHERE       proyecto = 'kolla'
                ORDER BY    nombre";
        
		return toba::instancia()->get_db()->consultar($sql);
    }
    
	/*
	 *  Valida si un nombre de Grupo se puede usar o no, es decir que no
	 *  existe otro con el mismo nombre dentro de la Unidad de Gestión
     */
	function validar_nombre_grupo($nombre, $grupo = null, $unidad_gestion)
	{
		if (!isset($nombre) || !isset($unidad_gestion)) {
			return false;
		}
		
		$nombre = kolla_db::quote($nombre);
		$unidad_gestion = kolla_db::quote($unidad_gestion);
		
		$sql = "SELECT 	COUNT(sge_grupo_definicion.grupo) AS cant 	
                FROM 	sge_grupo_definicion
				WHERE 	sge_grupo_definicion.unidad_gestion = $unidad_gestion
				AND		".kolla_sql::armar_condicion_compara_cadenas('sge_grupo_definicion.nombre', $nombre);
		
		if (!is_null($grupo)) {
			$grupo = kolla_db::quote($grupo);
			$sql .= " AND sge_grupo_definicion.grupo <> $grupo ";
		}
		
		$res = kolla_db::consultar_fila($sql);
		return ($res['cant'] == 0);
	}
    
    function get_info_encuestado($encuestado)
    {
        $schema     = toba::instancia()->get_db()->get_schema();
        $encuestado = toba::db()->quote($encuestado);
        
        $sql = "SELECT  apellidos,
                        nombres,
                        sge_encuestado.usuario,
                        apex_usuario_grupo_acc.nombre AS perfil
                FROM    sge_encuestado
                        JOIN $schema.apex_usuario_proyecto ON (sge_encuestado.usuario = apex_usuario_proyecto.usuario AND proyecto = 'kolla')
                        JOIN $schema.apex_usuario_grupo_acc ON (apex_usuario_proyecto.usuario_grupo_acc = apex_usuario_grupo_acc.usuario_grupo_acc)
                WHERE   sge_encuestado.encuestado = $encuestado";
        
        return toba::db()->consultar_fila($sql);
    }
	
    function get_datos_encuestado($encuestado)
    {
        if (isset($encuestado)) {
			$encuestado = kolla_db::quote($encuestado);
		}
		
		$sql = "SELECT  sge_encuestado.*,
                        dt.descripcion AS tipo_doc
                FROM    sge_encuestado
                            INNER JOIN sge_documento_tipo AS dt ON (sge_encuestado.documento_tipo = dt.documento_tipo)
                WHERE   sge_encuestado.encuestado = $encuestado
                ";
		
		return kolla_db::consultar_fila($sql);
    }

    function get_datos_encuestado_x_usuario($usuario)
    {
        if (isset($usuario)) {
            $usuario = kolla_db::quote($usuario);
        }

        $sql = "SELECT  sge_encuestado.*,
                        dt.descripcion AS tipo_doc
                FROM    sge_encuestado
                            INNER JOIN sge_documento_tipo AS dt ON (sge_encuestado.documento_tipo = dt.documento_tipo)
                WHERE   sge_encuestado.usuario = $usuario
                ";

        return kolla_db::consultar_fila($sql);
    }

    function get_datos_encuestado_x_usuario_sin_documento($usuario)
    {
        if (isset($usuario)) {
            $usuario = kolla_db::quote($usuario);
        }

        $sql = "SELECT  sge_encuestado.*
                FROM    sge_encuestado
                WHERE   sge_encuestado.usuario = $usuario
                ";

        return kolla_db::consultar_fila($sql);
    }

    /**
     * @todo traer el schema por consulta y no hardcoding
     * @param unknown $usuario
     */
    function get_ultima_conexion($usuario){
    	$schema_logs = toba::instancia()->get_db()->get_schema().'_logs';
    	
    	$where = " AND usuario = '$usuario' ";
    	$query = "SELECT    se.sesion_browser as id,
	                        usuario,
	                          ingreso,
	                        egreso,
	                         se.ip as ip,
	                         count(so.solicitud_browser) as solicitudes
	                     FROM $schema_logs.apex_sesion_browser se
	                         LEFT OUTER JOIN $schema_logs.apex_solicitud_browser so
	                          ON se.sesion_browser = so.sesion_browser
	                          AND se.proyecto = so.proyecto
	                      WHERE se.proyecto = 'kolla'
	                      $where
	                      GROUP BY 1,2,3,4,5
	                      ORDER BY ingreso DESC limit 1 offset 1 ";
	   return toba::db()->consultar_fila($query);
    	
    }

    function get_datos_usuario_privado() {

        $sql = "SELECT se.encuestado, 
                    se.usuario,
                    se.guest
                FROM sge_grupo_definicion definicion
                INNER JOIN sge_grupo_detalle detalle ON (definicion.grupo = detalle.grupo)
                INNER JOIN sge_encuestado se ON (detalle.encuestado = se.encuestado)
                WHERE estado = 'O'
                ";

        return toba::db()->consultar_fila($sql);
    }
    
    function get_unidades_gestion_usuario($usuario)
    {
        $usuario = kolla_db::quote($usuario);
		
		$sql = "SELECT  definicion.unidad_gestion
                FROM    sge_grupo_definicion definicion
                            INNER JOIN sge_grupo_detalle detalle ON (definicion.grupo = detalle.grupo)
                            INNER JOIN sge_encuestado se ON (detalle.encuestado = se.encuestado)
                WHERE   se.usuario = $usuario
                ";
		
		return kolla_db::consultar($sql);
    }
    
    function get_usuario_invitado()
    {
        $sql = "SELECT  encuestado
                FROM    sge_encuestado
                WHERE   usuario = 'invitado_kolla';
                ";
        
        return kolla_db::consultar_fila($sql);
    }
    
    function get_grupo_anonimo_predefinido($unidad_gestion)
	{
        $unidad_gestion = kolla_db::quote($unidad_gestion);
        
        $sql = "SELECT		sge_grupo_definicion.grupo,
							sge_grupo_definicion.nombre
				FROM 		sge_grupo_definicion
				WHERE		sge_grupo_definicion.unidad_gestion = $unidad_gestion
                AND         sge_grupo_definicion.estado = 'O'
                ";

        return kolla_db::consultar_fila($sql);
	}
    
}
?>