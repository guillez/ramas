<?php

use SIUToba\rest\lib\rest_error;
use SIUToba\rest\lib\rest_validador;

class act_habilitaciones 
{
    function actualizar_habilitacion($id_habilitacion, $data)
    {
        if ( isset($data['password_se']) ) {
            unset($data['password_se']);
        }
        $where = array(
            'habilitacion'  => $id_habilitacion, 
            'sistema'       => $data['sistema']
        );
        $sql = sql_array_a_update('sge_habilitacion', $data, $where);
        
        kolla_db::ejecutar($sql);
    }
    
    function crear_habilitacion($data)
    {   
        if ( isset($data['password_se']) ) {
            unset($data['password_se']);
        }
        
        if ( kolla_fecha::es_mayor($data['fecha_desde'], $data['fecha_hasta']) ) {
            throw new rest_error(400, 'La fecha desde debe ser menor o igual que la fecha hasta');
        }
        
        $data['externa'] = 'S';
        
        if(!isset($data['estilo']))
        {
        	$data['estilo']  = 1;
        }
        
        // Si estos datos no vienen
        if ( !isset($data['generar_cod_recuperacion']) || empty($data['generar_cod_recuperacion']) ) {
            $data['generar_cod_recuperacion'] = 'S';
        }
        if ( !isset($data['paginado']) || empty($data['paginado']) ) {
            $data['paginado'] = 'N';
        }
        
        
        if ( !isset($data['anonima']) || empty($data['anonima']) ) {
        
            $data['anonima'] = 'N';
        }
        
        $sql = sql_array_a_insert('sge_habilitacion', $data);
		$sql = substr($sql, 0, -1);
		$sql .= " RETURNING habilitacion";
        try{
        	if ( $h = kolla_db::consultar_fila($sql) ) {
        		$id_habilitacion = $h['habilitacion'];
        	
        		$datos = array(
        				'password_se' => url_encuestas::gen_password($id_habilitacion)
        		);
        		$where = array(
        				'habilitacion'  => $id_habilitacion,
        				'sistema'       => $data['sistema']
        		);
        	
        		$sql = sql_array_a_update('sge_habilitacion', $datos, $where);
        	
        		kolla_db::ejecutar($sql);
        	
        		return $id_habilitacion;
        	}
        	return false;
        }
        catch (toba_error_db $e){
  			/**
  			 * @todo El mensaje podría ser más especifico.
  			 */
        	throw new rest_error(400, "Error por errores de restriccion de integridad");
        }
		
    }
    
    function agregar_formularios($habilitacion, $id_concepto, $formularios)
    {
        //return true;
        try {
            kolla::db()->abrir_transaccion();

            $fh = array(
                'nombre'        => $formularios['nombre'],
                'habilitacion'  => $habilitacion,
                'concepto'      => $id_concepto,
                'estado'        => $formularios['estado']
            );

            $sql = sql_array_a_insert('sge_formulario_habilitado', $fh);
            $sql = substr($sql, 0, -1);
            $sql .= " RETURNING formulario_habilitado";

            if ( $res = kolla_db::consultar_fila($sql) ) {
                $formulario_habilitado = $res['formulario_habilitado'];
            }

            $orden = 1;
            foreach ($formularios['detalle'] as $formulario) {
                $fhd = array(
                    'formulario_habilitado' => $formulario_habilitado,
                    'encuesta'              => $formulario['encuesta'],
                    'elemento'              => $formulario['elemento'],
                    'orden'                 => $orden,
                    'tipo_elemento'         => $fomulario['tipo_elemento'],
                );
                $sql = sql_array_a_insert('sge_formulario_habilitado_detalle', $fhd);
                toba::logger()->error($sql);
                kolla_db::ejecutar($sql);
                $orden++;
                // Grupos?
            }

            kolla::db()->cerrar_transaccion();
        } catch (toba_error_db $e) {
            kolla::db()->abortar_transaccion();
	        throw new rest_error(500, 'Ocurrió un error al intentar asociar el formulario.', array('error' => $e->get_mensaje()));
        }
        
    }

	function upsert_formulario_habilitado($habilitacion, $formulario_externo, $form, $sistema)
	{
		try {
			kolla::db()->abrir_transaccion();
			$res = $this->obtener_formulario_habilitado($habilitacion, $formulario_externo);
            
            if ( $res ) { // Actualizacion
                $id_form = $res['formulario_habilitado'];
                $res = $this->actualizar_formulario_habilitado($habilitacion, $formulario_externo, $form);
                $this->eliminar_formulario_detalle($id_form); // Lo limpio para agregarle lo nuevo.
            } else { // Nuevo
                $id_form = $this->insertar_formulario_habilitado($habilitacion, $formulario_externo, $form);
                $this->insertar_grupo_habilitado($id_form, $sistema);
            }
            $this->insertar_formulario_detalle($id_form, $form);

			kolla::db()->cerrar_transaccion();
		} catch (toba_error_db $e) {
			kolla::db()->abortar_transaccion();
			throw new rest_error(500, 'Ocurrió un error al intentar asociar el formulario.', array('error' => $e->get_mensaje()));
		}
	}

	protected function insertar_formulario_detalle($id_form, $formulario)
	{
		$orden = 1;
		foreach ($formulario['detalle'] as $form) {
			$fhd = array(
				'formulario_habilitado' => $id_form,
				'encuesta'              => $form['encuesta'],
				'elemento'              => $form['elemento'],
				'orden'                 => $orden,
				'tipo_elemento'         => $form['tipo_elemento'],
			);
			$sql = sql_array_a_insert('sge_formulario_habilitado_detalle', $fhd);
			toba::logger()->debug($sql);
			kolla_db::ejecutar($sql);
                        
                        //SE ACTUALIZA LA ENCUESTA COMO IMPLEMENTADA 
                        $enc = array('encuesta' => $form['encuesta']);
                        $imp = array('implementada' => 'S');
                        $sql_enc = sql_array_a_update('sge_encuesta_atributo', $imp, $enc);
                        toba::logger()->debug($sql_enc);
			kolla_db::ejecutar($sql_enc);
                        
			$orden++;
		}
	}
    
    protected function insertar_grupo_habilitado($id_form, $sistema) 
    {
        $sistema = (int) $sistema;
        $id_form = (int) $id_form;
        
        $sql = "SELECT  sge_grupo_definicion.grupo
                FROM    sge_sistema_externo
                        INNER JOIN sge_encuestado ON (sge_encuestado.usuario = sge_sistema_externo.usuario)
                        INNER JOIN sge_grupo_detalle ON (sge_encuestado.encuestado = sge_grupo_detalle.encuestado)
                        INNER JOIN sge_grupo_definicion ON (sge_grupo_detalle.grupo = sge_grupo_definicion.grupo)
                WHERE   sge_sistema_externo.estado = 'A'
				AND 	sge_encuestado.externo = 'S'
				AND 	sge_sistema_externo.sistema = $sistema";
        
        $res_grupo =  kolla_db::consultar_fila($sql);
        
        if ( $res_grupo === false || count($res_grupo) != 1 ) {
            throw new rest_error(400, 'No hay grupos activos o no se puede seleccionar un único grupo.');
        } else {
            $id_grupo = (int) $res_grupo['grupo'];
        
            $sql = "INSERT INTO     sge_grupo_habilitado
                                    (grupo, formulario_habilitado)
                    VALUES          ($id_grupo, $id_form)";
            
            kolla_db::ejecutar($sql);
        }
    }

	protected function obtener_formulario_habilitado($habilitacion, $id_externo) 
    {
		$habilitacion = kolla_db::quote($habilitacion);
        $id_externo   = kolla_db::quote($id_externo);
        
		$sql = "SELECT  formulario_habilitado
				FROM	sge_formulario_habilitado
				WHERE	habilitacion = $habilitacion
				AND 	formulario_habilitado_externo = $id_externo";
        
		return kolla_db::consultar_fila($sql);
	}

	protected function insertar_formulario_habilitado($habilitacion, $formulario_externo, $formulario)
    {
	    $fh = array(
			'nombre'                        => $formulario['nombre'],
			'habilitacion'                  => $habilitacion,
			'concepto'                      => $formulario['concepto'],
			'estado'                        => $formulario['estado'],
            'formulario_habilitado_externo' => $formulario_externo
		);

		$sql = sql_array_a_insert('sge_formulario_habilitado', $fh);
		$sql = substr($sql, 0, -1);
		$sql .= " RETURNING formulario_habilitado";

		if ( $res = kolla_db::consultar_fila($sql) ) {
			return $res['formulario_habilitado'];
		}
		return null;
	}

	protected function actualizar_formulario_habilitado($habilitacion, $formulario_externo, $formulario)
    {
        $fh = array(
			'nombre'                        => $formulario['nombre'],
			'habilitacion'                  => $habilitacion,
			'concepto'                      => $formulario['concepto'],
			'estado'                        => $formulario['estado'],
            'formulario_habilitado_externo' => $formulario_externo
		);
        
        $where = array(
            'habilitacion'                  => $habilitacion,
            'formulario_habilitado_externo' => $formulario_externo
        );
		
        $sql = sql_array_a_update('sge_formulario_habilitado', $fh, $where);
        return kolla_db::ejecutar($sql);
	}

	function eliminar_formulario_externo($habilitacion, $formulario_externo)
    {
		$habilitacion = kolla_db::quote($habilitacion);
        $formulario_externo = kolla_db::quote($formulario_externo);
        
		$sql = "UPDATE  sge_formulario_habilitado
				SET     estado = 'B'
				WHERE   formulario_habilitado_externo = $formulario_externo
                AND 	habilitacion = $habilitacion";

		kolla_db::ejecutar($sql);
	}

	function eliminar_formulario_detalle($id_form)
    {
		$id_form = kolla_db::quote($id_form);
		
		$sql = "DELETE FROM sge_formulario_habilitado_detalle
				WHERE       formulario_habilitado = $id_form";
		
		kolla_db::ejecutar($sql);
	}

	function eliminar_habilitaciones_por_encuesta($encuesta)
	{
		$encuesta = kolla_db::quote($encuesta);
		
		/*
		 * A continuación, se eliminan en cadena la definición de la encuesta junto con todos
		 * los formularios en donde se usa y todas las habilitaciones en donde este habilitado
		 */
		
		$sql = "BEGIN;
				
				-- Difiero todas las constraints
				SET CONSTRAINTS ALL DEFERRED;
				
                DELETE FROM sge_pregunta_cascada
				WHERE		pregunta_disparadora IN
							(
								SELECT	edd.pregunta
								FROM	sge_encuesta_definicion edd
								WHERE	edd.encuesta = $encuesta
							)
                AND 		pregunta_receptora IN
							(
								SELECT	edr.pregunta
								FROM	sge_encuesta_definicion edr
								WHERE	edr.encuesta = $encuesta
							);
                            
                DELETE FROM sge_pregunta_dependencia_definicion
                WHERE       dependencia_definicion IN
                            (
								SELECT	sge_pregunta_dependencia_definicion.dependencia_definicion
								FROM	sge_pregunta_dependencia_definicion,
                                        sge_pregunta_dependencia,
                                        sge_encuesta_definicion
								WHERE	sge_pregunta_dependencia_definicion.pregunta_dependencia = sge_pregunta_dependencia.pregunta_dependencia
                                AND     sge_pregunta_dependencia.encuesta_definicion = sge_encuesta_definicion.encuesta_definicion
                                AND     sge_encuesta_definicion.encuesta = $encuesta
							);
                            
				DELETE FROM sge_pregunta_dependencia
                WHERE       pregunta_dependencia IN
                            (
								SELECT	sge_pregunta_dependencia.pregunta_dependencia
								FROM	sge_pregunta_dependencia,
                                        sge_encuesta_definicion
								WHERE	sge_pregunta_dependencia.encuesta_definicion = sge_encuesta_definicion.encuesta_definicion
                                AND     sge_encuesta_definicion.encuesta = $encuesta
							);
                            
				DELETE FROM sge_bloque
				WHERE		bloque IN
							(
								SELECT	sge_encuesta_definicion.bloque
								FROM	sge_encuesta_definicion
								WHERE	sge_encuesta_definicion.encuesta = $encuesta
							);
							
				DELETE FROM sge_encuesta_definicion
				WHERE		sge_encuesta_definicion.encuesta = $encuesta;
				
				DELETE FROM sge_formulario_atributo
				WHERE 		formulario IN
							(
								SELECT	sge_formulario_atributo.formulario
								FROM 	sge_formulario_atributo,
										sge_formulario_definicion
								WHERE	sge_formulario_atributo.formulario = sge_formulario_definicion.formulario
								AND		sge_formulario_definicion.encuesta = $encuesta
							);
				
				DELETE FROM sge_formulario_definicion
				WHERE 		formulario_definicion IN
							(
								SELECT	formulario_definicion
								FROM 	sge_formulario_definicion
								WHERE	formulario IN
										(
											SELECT	fd.formulario
											FROM 	sge_formulario_definicion AS fd
											WHERE 	EXISTS
													(
														SELECT	1
														FROM	sge_formulario_definicion
														WHERE	sge_formulario_definicion.formulario = fd.formulario
														AND		sge_formulario_definicion.encuesta = $encuesta
													)
										)
							);
				
				DELETE FROM sge_log_formulario_definicion_habilitacion
				WHERE 		habilitacion IN
							(
								SELECT	sge_habilitacion.habilitacion
								FROM 	sge_habilitacion,
										sge_formulario_habilitado,
										sge_formulario_habilitado_detalle
								WHERE	sge_habilitacion.habilitacion = sge_formulario_habilitado.habilitacion
								AND		sge_formulario_habilitado.formulario_habilitado = sge_formulario_habilitado_detalle.formulario_habilitado
								AND		sge_formulario_habilitado_detalle.encuesta = $encuesta
							);
				
				DELETE FROM sge_encuesta_atributo
				WHERE		sge_encuesta_atributo.encuesta = $encuesta;

				DELETE FROM sge_habilitacion
				WHERE 		habilitacion IN
							(
								SELECT	sge_habilitacion.habilitacion
								FROM 	sge_habilitacion,
										sge_formulario_habilitado,
										sge_formulario_habilitado_detalle
								WHERE	sge_habilitacion.habilitacion = sge_formulario_habilitado.habilitacion
								AND		sge_formulario_habilitado.formulario_habilitado = sge_formulario_habilitado_detalle.formulario_habilitado
								AND		sge_formulario_habilitado_detalle.encuesta = $encuesta
							);
				
				DELETE FROM sge_grupo_habilitado
				WHERE		formulario_habilitado IN
							(
								SELECT 	sge_formulario_habilitado.formulario_habilitado
								FROM	sge_formulario_habilitado,
										sge_formulario_habilitado_detalle
								WHERE	sge_formulario_habilitado.formulario_habilitado = sge_formulario_habilitado_detalle.formulario_habilitado
								AND		sge_formulario_habilitado_detalle.encuesta = $encuesta
							);
				
				DELETE FROM sge_formulario_habilitado
				WHERE		formulario_habilitado IN
							(
								SELECT 	sge_formulario_habilitado.formulario_habilitado
								FROM	sge_formulario_habilitado,
										sge_formulario_habilitado_detalle
								WHERE	sge_formulario_habilitado.formulario_habilitado = sge_formulario_habilitado_detalle.formulario_habilitado
								AND		sge_formulario_habilitado_detalle.encuesta = $encuesta
							);
				
				DELETE FROM sge_formulario_habilitado_detalle
				WHERE		formulario_habilitado_detalle IN
							(
								SELECT	formulario_habilitado_detalle
								FROM 	sge_formulario_habilitado_detalle
								WHERE	formulario_habilitado IN
										(
											SELECT	fhd.formulario_habilitado
											FROM 	sge_formulario_habilitado_detalle AS fhd
											WHERE 	EXISTS
													(
														SELECT	1
														FROM	sge_formulario_habilitado_detalle
														WHERE	sge_formulario_habilitado_detalle.formulario_habilitado = fhd.formulario_habilitado
														AND		sge_formulario_habilitado_detalle.encuesta = $encuesta
													)
										)
							);
				
				END;
				";
		
		kolla_db::ejecutar($sql);
	}
	
	function eliminar_encuesta($encuesta)
	{
		$encuesta = kolla_db::quote($encuesta);
		
		$sql = "BEGIN;
				
				-- Difiero todas las constraints
				SET CONSTRAINTS ALL DEFERRED;
                
                DELETE FROM sge_pregunta_cascada
				WHERE		pregunta_disparadora IN
							(
								SELECT	edd.pregunta
								FROM	sge_encuesta_definicion edd
								WHERE	edd.encuesta = $encuesta
							)
                AND 		pregunta_receptora IN
							(
								SELECT	edr.pregunta
								FROM	sge_encuesta_definicion edr
								WHERE	edr.encuesta = $encuesta
							);
                            
                DELETE FROM sge_pregunta_dependencia_definicion
                WHERE       dependencia_definicion IN
                            (
								SELECT	sge_pregunta_dependencia_definicion.dependencia_definicion
								FROM	sge_pregunta_dependencia_definicion,
                                        sge_pregunta_dependencia,
                                        sge_encuesta_definicion
								WHERE	sge_pregunta_dependencia_definicion.pregunta_dependencia = sge_pregunta_dependencia.pregunta_dependencia
                                AND     sge_pregunta_dependencia.encuesta_definicion = sge_encuesta_definicion.encuesta_definicion
                                AND     sge_encuesta_definicion.encuesta = $encuesta
							);
                            
				DELETE FROM sge_pregunta_dependencia
                WHERE       pregunta_dependencia IN
                            (
								SELECT	sge_pregunta_dependencia.pregunta_dependencia
								FROM	sge_pregunta_dependencia,
                                        sge_encuesta_definicion
								WHERE	sge_pregunta_dependencia.encuesta_definicion = sge_encuesta_definicion.encuesta_definicion
                                AND     sge_encuesta_definicion.encuesta = $encuesta
							);
                            
				DELETE FROM sge_bloque
				WHERE		bloque IN
							(
								SELECT	sge_encuesta_definicion.bloque
								FROM	sge_encuesta_definicion
								WHERE	sge_encuesta_definicion.encuesta = $encuesta
							);
							
				DELETE FROM sge_encuesta_definicion
				WHERE		sge_encuesta_definicion.encuesta = $encuesta;
				
				DELETE FROM sge_encuesta_atributo
				WHERE		sge_encuesta_atributo.encuesta = $encuesta;

				END;
				";
		
		kolla_db::ejecutar($sql);
	}
	
}
