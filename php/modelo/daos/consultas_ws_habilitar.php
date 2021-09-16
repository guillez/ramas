<?php

class consultas_ws_habilitar {

	function obtener_habilitacion($id, $sistema) {
		$id_hab = (int) $id;
		$sistema = (int) $sistema;

		$sql = "SELECT 
					fecha_desde ,
					fecha_hasta
				FROM sge_habilitacion
				WHERE habilitacion= $id_hab and sistema = $sistema";
		return kolla_db::consultar_fila($sql);
	}

	/**
	 * NO INICIADA es que no tiene respuestas (no chequea fechas)
	 * @param type $params
	 * @return type
	 */
	function modificar_habilitacion_no_iniciada($params) {
		$sql = "UPDATE sge_habilitacion
				SET
					fecha_desde={$params['fecha_desde']},
					fecha_hasta={$params['fecha_hasta']},
					paginado={$params['paginado']},
					estilo={$params['estilo']},
					url_imagenes_base ={$params['url_imagenes_base']},
					anonima={$params['anonima']}
				WHERE habilitacion = {$params['habilitacion']}
					  AND sistema={$params['sistema']}";
		return kolla_db::ejecutar($sql);
	}

	function modificar_habilitacion_iniciada($params) {
		$sql = "UPDATE sge_habilitacion
				SET paginado= {$params['paginado']},
					estilo={$params['estilo']},
					url_imagenes_base ={$params['url_imagenes_base']},
					fecha_hasta={$params['fecha_hasta']}
				WHERE 
					habilitacion = {$params['habilitacion']} 
					AND sistema= {$params['sistema']}";
		return kolla_db::ejecutar($sql);
	}

	function insertar_habilitacion($params) {
        $dsc = quote("Externa");
		$sql = "INSERT INTO sge_habilitacion(
				fecha_desde, fecha_hasta, paginado, estilo,
				externa, anonima, sistema, descripcion, url_imagenes_base,
                unidad_gestion)
				VALUES (
					{$params['fecha_desde']},
					{$params['fecha_hasta']}, 
					{$params['paginado']},
					{$params['estilo']},
					{$params['externa']},
					{$params['anonima']},
					{$params['sistema']},
                     $dsc,
					{$params['url_imagenes_base']},
					{$params['unidad_gestion']}   
				)	 RETURNING habilitacion";
		//	toba::logger()->var_dump($sql);
		$res = kolla_db::consultar($sql);
		$id = $res[0]['habilitacion'];
		return $id;
	}

	function get_password ($id_hab, $sistema) {
		$id_hab = (int) $id_hab;
		$sistema = (int) $sistema;
		$sql = "SELECT password_se  
				FROM 
					sge_habilitacion
				WHERE 
					habilitacion= $id_hab and sistema = $sistema";
		$res = kolla_db::consultar_fila($sql);
		return $res['password_se'];
	}

	function update_password ($password, $id_hab, $sistema) {
		$password = kolla_db::quote($password);
		$id_hab = (int) $id_hab;
		$sistema = (int) $sistema;
		$sql = "UPDATE
					sge_habilitacion
				SET 
					password_se= $password
				WHERE 
					habilitacion= $id_hab and sistema = $sistema";
		$res = kolla_db::ejecutar($sql);

		toba::logger()->debug($sql);
		if ($res != 1) {
			throw new toba_error("Fallo actualizar el password");
		}
	}

////////////////////////////////////////////////////////////////////////////
//formularios///////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////

	function obtener_formulario_habilitado($concepto, $habilitacion) {
		$con = kolla_db::quote($concepto);
		$hab = kolla_db::quote($habilitacion);
		$sql = "SELECT 
					formulario_habilitado 
				FROM 
					sge_formulario_habilitado
				WHERE 
					(concepto = $con AND habilitacion = $hab)";
		return kolla_db::consultar($sql);
	}

	function insertar_formulario_habilitado($concepto, $habilitacion, $descripcion) {
		$con = (int) ($concepto);
		$hab = (int) ($habilitacion);
		$des = kolla_db::quote($descripcion);

		$sql = "INSERT INTO sge_formulario_habilitado 
						(nombre, concepto, habilitacion)
				VALUES  ($des, $con, $hab) 
				RETURNING formulario_habilitado;";
		$res = kolla_db::consultar($sql);
		
		return $res[0]['formulario_habilitado'];
	}

	function actualizar_formulario_habilitado($id_form, $concepto, $habilitacion, $descripcion) {
		$con = (int) ($concepto);
		$hab = (int) $habilitacion;
		$des = kolla_db::quote($descripcion);
		$id_form = kolla_db::quote($id_form);

		$sql = "UPDATE sge_formulario_habilitado 
			    SET 
					nombre = $des, estado = 'A' 
			    WHERE habilitacion = $hab
					AND concepto = $con
			        AND formulario_habilitado = $id_form"; //el hab+concepto es de seguridad
		return kolla_db::ejecutar($sql);
	}

	function dar_baja_formulario($id_form, $habilitacion) {
		$id_form = (int) $id_form;
		$habilitacion = (int) $habilitacion;
		$sql = "UPDATE sge_formulario_habilitado
				SET 
					estado='B'
				WHERE 
					formulario_habilitado = $id_form
					AND habilitacion = $habilitacion";

		$res = kolla_db::consultar($sql);
		if (count($res) != 1)
			throw new toba_error("Fallo al dar de baja un formulario");
	}

	function insertar_fila_formulario_detalle($id_form, $encuesta, $id_elem, $orden) {
		$id_form = (int) ($id_form);
		$encuesta = (int) ($encuesta);
		
		if(!is_null($id_elem)){
			$id_elem = (int) ($id_elem);
		}else{
			$id_elem = 'NULL';
		}
		
		$orden = (int) ($orden);

		$sql = "INSERT INTO sge_formulario_habilitado_detalle
						(formulario_habilitado, encuesta, elemento, orden)
				VALUES	($id_form, $encuesta, $id_elem, $orden);";
		$res = kolla_db::consultar($sql);
		if (count($res) != 1)
			throw new toba_error("Error al insertar el detalle del formulario");
	}

	function eliminar_formulario_detalle($id_form) {
		$id_form = (int) $id_form;
		$sql = "DELETE FROM sge_formulario_habilitado_detalle
				WHERE 
					(formulario_habilitado = $id_form);";
		$res = kolla_db::ejecutar($sql);
		toba::logger()->debug($res . " filas eliminadas del formulario  $id_form");
	}

////////////////////////////////////////////////////////////////////////////
//elementos y conceptos ////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////

	function upsert_elemento_sp($id_externo, $descripcion, $url, $sistema, $unidad_gestion) {
		$id_ex = kolla_db::quote($id_externo);
		$des = kolla_db::quote($descripcion);
		$url = kolla_db::quote($url);
		$sistema = (int) $sistema;
                $unidad_gestion = kolla_db::quote($unidad_gestion);

		$sql = "SELECT * from sp_upsert_elemento(
					{$id_ex}, {$des}, {$url}, $sistema, {$unidad_gestion})
				AS (id int, codigo int, descrip text);";
		return kolla_db::consultar($sql);
	}

	function upsert_concepto_sp($id_externo, $descripcion, $sistema, $unidad_gestion) 
    {
		$id_externo     = kolla_db::quote($id_externo);
		$descripcion    = kolla_db::quote($descripcion);
		$sistema        = (int) $sistema;
        $unidad_gestion = kolla_db::quote($unidad_gestion);
        
		$sql = "
            SELECT 
                * 
            FROM
                sp_upsert_concepto ({$id_externo}, {$descripcion}, $sistema, {$unidad_gestion})
                AS (id int, codigo int, descrip text)
        ";
                
		return kolla_db::consultar($sql);
	}

////////////////////////////////////////////////////////////////////////////
//grupos                ////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////    
    
    function insertar_grupo_habilitado($id_form, $sistema) {
        $sistema = (int) $sistema;
        $id_form = (int) $id_form;
        
        $sql = "SELECT sgdef.grupo
                FROM sge_sistema_externo sse 
                    INNER JOIN sge_encuestado se ON (sse.usuario = se.usuario)
                    INNER JOIN sge_grupo_detalle sgdetalle ON (se.encuestado = sgdetalle.encuestado)
                    INNER JOIN sge_grupo_definicion sgdef ON (sgdetalle.grupo = sgdef.grupo)
                WHERE sse.estado = 'A' 
                    AND se.externo = 'S'
                    AND sse.sistema = $sistema";
        $res_grupo =  toba::db()->consultar_fila($sql);
        
        if (count($res_grupo) != 1)
        {
            throw new toba_error("Error: no hay grupos activos o no se puede seleccionar un único grupo.");
        }
        else 
        {
            $id_grupo = (int) $res_grupo['grupo'];
        
            $sql = "INSERT INTO sge_grupo_habilitado 
                    ( grupo, formulario_habilitado ) 
                    VALUES ($id_grupo, $id_form)";
            $res = kolla_db::consultar($sql);
            if (count($res) != 1)
                throw new toba_error("Error al insertar el grupo de encuestados habilitado.");
        }
    }
    
}

?>
	