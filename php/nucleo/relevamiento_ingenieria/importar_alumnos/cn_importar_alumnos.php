<?php
/*
 *	Controlador de negocio para importar alumnos.
 */
class cn_importar_alumnos extends cn_entidad
{
	protected $institucion;
	
	function get_institucion_local()
	{
		if (!isset($this->institucion)) {
			$this->institucion = toba::consulta_php('consultas_mgi')->get_institucion();
		}
		
		return $this->institucion;
	}
	
	function get_usuarios()
	{
		return toba::consulta_php('consultas_relevamiento_ingenierias')->get_usuarios_para_importar();
	}
	
	function actualizar_usuario($usuario, $estado_clave, $algoritmo, $es_alta)
	{
		try {
			//Se abre una transacción para operar sobre esquemas de kolla
			kolla::db()->abrir_transaccion();
            $usuario['clave'] = $estado_clave == 'plana' ? $this->encriptar_clave($usuario['clave'], $algoritmo) : $usuario['clave'];
            
			//Seteo datos encuestado
			$datos_encuestado = $this->preparar_encuestado($usuario);
			$this->set_encuestado($usuario, $datos_encuestado, $es_alta);
			
			//Seteo datos usuario y proyecto
			$datos_usuario 	= $this->preparar_usuario($usuario, $algoritmo);
			$datos_usuario_proyecto = $this->preparar_usuario_proyecto($usuario);
			$this->set_usuario($usuario, $datos_usuario, $datos_usuario_proyecto, $es_alta);
			
			//Se cierra la transacción
			kolla::db()->cerrar_transaccion();
		} catch (toba_error_db $e) {
			
			//Se aborta la transacción y se notifica el error
			toba::notificacion()->agregar('ERROR actualizando encuestado. '.$e->get_mensaje(), 'warning');
			toba::logger()->info('ERROR actualizando encuestado. '.$e->getMessage());
			kolla::db()->abortar_transaccion();
			return false;
		}
		
		return true;
	}
	
	function set_encuestado($usuario, $datos, $es_alta)
	{
		if ($es_alta) {
			abm::alta('sge_encuestado', $datos);
		} else {
			$where = array();
			$where['usuario'] = $usuario['usuario'];
			abm::modificacion('sge_encuestado', $datos, $where);
		}
	}
	
	function set_usuario($usuario, $datos_usuario, $datos_usuario_proyecto, $es_alta)
	{
		$schema_toba = toba::instancia()->get_db()->get_schema();
	    $schema_toba = isset($schema_toba) ? $schema_toba : 'public';
	    
		if ($es_alta) {
        	abm::alta('apex_usuario', $datos_usuario, $schema_toba);
        	abm::alta('apex_usuario_proyecto', $datos_usuario_proyecto, $schema_toba);
        } else {
        	$where = array();
			$where['usuario'] = $datos_usuario['usuario'];
        	unset($datos_usuario['usuario']);
        	abm::modificacion('apex_usuario', $datos_usuario, $where, $schema_toba);
        }
	}
	
	function preparar_encuestado($usuario)
	{
		$encuestado = array();
		$encuestado['usuario'] 			= $usuario['usuario'];
		$encuestado['clave']   			= $usuario['clave'];
		$encuestado['documento_pais']   = $usuario['pais_documento'];
		$encuestado['documento_tipo']   = $usuario['tipo_documento'];
		$encuestado['documento_numero']	= $usuario['numero_documento'];
		$encuestado['apellidos']   		= $usuario['apellidos'];
		$encuestado['nombres']   		= $usuario['nombres'];
		$encuestado['email']   			= $usuario['email'];
		$encuestado['sexo']   			= $usuario['genero'] == '1' ? 'F' : 'M';
		$encuestado['fecha_nacimiento'] = $usuario['fecha_nacimiento'];
		
		return $encuestado;
	}
	
	function preparar_usuario($user, $algoritmo)
	{
		$usuario = array();
		$usuario['usuario'] 		= $user['usuario'];
		$usuario['clave']   		= $user['clave'];
		$usuario['nombre'] 			= $user['apellidos'].' '.$user['nombres'];
		$usuario['autentificacion'] = $algoritmo;
		
		return $usuario;
	}
	
	function preparar_usuario_proyecto($user)
	{
		$usuario = array();
		$usuario['proyecto'] 		  = toba::proyecto()->get_id();
		$usuario['usuario_grupo_acc'] = 'encuesta';
		$usuario['usuario'] 		  = $user['usuario'];
		
		return $usuario;
	}
	
	function encriptar_clave($clave, $metodo)
	{   
		if ($metodo == 'sha256') {
			return encriptar_con_sal($clave, $metodo);
		} elseif ($metodo == 'md5') {
			return hash($metodo, $clave);
		}
		
		//Si el método no es ninguno de los anteriores lanzo una excepción
		throw new toba_error('Tipo de autentificación desconocido: ' . $metodo);
	}
	
	function actualizar_ingenieria_relevamiento($usuario, $datos)
	{
		$where = array();
		$where['usuario']     = $usuario['usuario'];
		$where['arau_titulo'] = $usuario['arau_titulo'];
		
		try {
			$schema_kolla = toba::db()->get_schema();
			abm::modificacion('int_ingenieria_relevamiento', $datos, $where, $schema_kolla);
		} catch (toba_error_db $e) {
			toba::notificacion()->agregar('ERROR actualizando resultados. '.$e->get_mensaje(), 'warning');
			toba::logger()->info('ERROR actualizando resultados. '.$e->getMessage());
		}
	}
	
}
?>