<?php

include(INST_DIR.'/lib/phpmailer/class.smtp.php');

class paso_inst_configuracion_kolla extends paso_instalar_configuracion
{
	function generar()
	{
		$archivo = dirname(__FILE__).'/../templates/configuracion_kolla.php';
		
		if (file_exists($archivo)) {
			include($archivo);
		} else {
			echo "<h3>Debe definir el template en el archivo $archivo</h3>";
		}
	}
	
	function get_datos_configuracion_defecto()
	{
		return array(
			'instalacion_id' => 'Cambiar Nombre',
			'url_prefijo' => inst::configuracion()->get('instalador', 'url_prefijo'),
			
			'usuario_nombre' => '',
			'usuario_id' => '',
			'usuario_clave' => '',
			'usuario_email' => '',
		
			'smtp_from' => '',
			'smtp_host' => '',
			'smtp_auth' => false,
			'smtp_usuario' => '',
			'smtp_clave' => '',
			'smtp_seguridad' => '',
			'smtp_puerto' => ''
		);
	}
	
	function crear_instalacion()
	{
		//-- Arma la url final de la instalacion
		if (trim($this->datos_configuracion['url_prefijo']) != '') {
			$prefijo = '/'.trim($this->datos_configuracion['url_prefijo']);
		} else {
			$prefijo = '';
		}
		
		$_SESSION['url_instalacion'] = $prefijo.inst::configuracion()->get('instalador', 'url_sufijo');
		$hay_smtp = false;
		$id_proyecto_ppal = inst::configuracion()->get('proyecto', 'id');		
		$path_final = str_replace('\\', '\\\\', $_SESSION['path_instalacion']);
		$this->path_instalacion = $path_final.'/instalacion';
		if (!is_dir($this->path_instalacion) && ! mkdir($this->path_instalacion, 0770)) {
			$this->set_error('sin_instalacion', "No fue posible crear la carpeta '$this->path_instalacion'");
			return;
		}

		$es_produccion =  (inst::configuracion()->es_instalacion_produccion()) ? 1: 0;		
		
		//-- SMTP.ini
		if (trim($this->datos_configuracion['smtp_host']) != '') {
			$smtp = new inst_ini($this->path_instalacion.'/smtp.ini');
			$datos_smtp = array();
			$datos_smtp['from'] = $this->datos_configuracion['smtp_from'];
			$datos_smtp['host'] = $this->datos_configuracion['smtp_host'];
			$datos_smtp['puerto'] = $this->datos_configuracion['smtp_puerto'];
			$datos_smtp['nombre_from'] = $this->datos_configuracion['usuario_nombre'];
			$datos_smtp['seguridad'] = $this->datos_configuracion['smtp_seguridad'];
			$con_auth = isset($this->datos_configuracion['smtp_auth']) && $this->datos_configuracion['smtp_auth'];
			$datos_smtp['auth'] = $con_auth ? 1 : 0;
			if ($con_auth) {
				$datos_smtp['usuario'] = $this->datos_configuracion['smtp_usuario'];
				$datos_smtp['clave'] = $this->datos_configuracion['smtp_clave'];
			}
			$smtp->agregar_entrada('instalacion', $datos_smtp);
			$smtp->guardar();	
			$hay_smtp = true;
		}
		
		//-- Instalacion.ini
		$instalacion = new inst_ini($this->path_instalacion.'/instalacion.ini');
		$instalacion->agregar_entrada('nombre', $this->datos_configuracion['instalacion_id']);
		$instalacion->agregar_entrada('clave_querystring', md5(uniqid(rand(), true)));	
		$instalacion->agregar_entrada('clave_db', md5(uniqid(rand(), true)));	
		$instalacion->agregar_entrada('url', inst::configuracion()->get_url_final_proyecto_extra('toba'));
		$instalacion->agregar_entrada('es_produccion', $es_produccion);
		if ($hay_smtp) {
			$instalacion->agregar_entrada('smtp', 'instalacion');
		}
		$instalacion->guardar();		
		
		//-- Instancia 
		$nombre_instancia = inst::configuracion()->get_nombre_instancia();
		$this->path_instancia = $this->path_instalacion . '/i__' . $nombre_instancia;
		if (! is_dir($this->path_instancia) && ! mkdir($this->path_instancia, 0770)) {
			$this->set_error('sin_instancia', "No fue posible crear la carpeta '$this->path_instancia'");
			return;
		}		
		$proyectos = inst::configuracion()->get_proyectos_final_instancia();
		if (! $es_produccion) {
			$proyectos = array_unique(array_merge($proyectos, toba_lib::get_proyectos_disponibles()));
		}
		$instancia = new inst_ini($this->path_instancia.'/instancia.ini');
		$instancia->agregar_entrada('base', inst::configuracion()->get_id_base_final_toba());
		$instancia->agregar_entrada('tipo', 'normal');		
		$instancia->agregar_entrada( 'proyectos', implode(', ', $proyectos));
		foreach ($proyectos as $id_proyecto) {
			$datos_proyecto = array('usar_perfiles_propios' => 0);
			if ($id_proyecto == $id_proyecto_ppal) {
				$datos_proyecto['url'] = inst::configuracion()->get_url_final_proyecto();
				$datos_proyecto['path'] = $path_final.'/aplicacion';
			} else {
				$datos_proyecto['url'] = inst::configuracion()->get_url_final_proyecto_extra($id_proyecto);
			}			
			$instancia->agregar_entrada($id_proyecto, $datos_proyecto);
		}
		$instancia->guardar();
		
		//-- Proyectos
		$path_global = $this->path_instancia.'/global';
		if (! is_dir($path_global) && ! mkdir($path_global, 0770)) {
			$this->set_error('sin_instancia', "No fue posible crear la carpeta '$path_global'");
			return;
		}		
		foreach ($proyectos as $id_proyecto) {
			$path_proyecto = $this->path_instancia.'/p__'.$id_proyecto;
			if (! is_dir($path_proyecto) && ! mkdir($path_proyecto, 0770)) {
				$this->set_error('sin_instancia', "No fue posible crear la carpeta '$path_proyecto'");
				return;
			}
			$path_logs = $path_proyecto.'/logs';
			if (! is_dir($path_logs) && ! mkdir($path_logs, 0770)) {
				$this->set_error('sin_instancia', "No fue posible crear la carpeta '$path_logs'");
				return;
			}			
		}
		if (! $this->tiene_errores()) {
			$this->generar_conf_apache();
		}
		if (! $this->tiene_errores()) {
			$_SESSION['datos_configuracion'] = $this->datos_configuracion; 
		}
	}
	
	function probar_smtp()
	{
		$smtp = new SMTP();
		$hello = 'Prueba';
		$host = $this->datos_configuracion['smtp_host'];
		if ($this->datos_configuracion['smtp_seguridad'] == 'ssl') {
			if (! extension_loaded('openssl')) {
				$this->set_error('no_ssl', 'Para usar encriptación SSL es necesario activar la extensión "openssl" en el php.ini');
				return;				
			} else {
				$host = 'ssl://'.$host;
			}
		}
		$smtp->Connect($host, $this->datos_configuracion['smtp_puerto']);
		if (empty($smtp->error)) {
			$smtp->Hello($hello);
		}		
		if (empty($smtp->error)) {
			if ($this->datos_configuracion['smtp_seguridad'] == 'tls') {
				$smtp->StartTLS();
				$smtp->Hello($hello);
			}		
		}
		if (empty($smtp->error)) {
			if (isset($this->datos_configuracion['smtp_auth']) && $this->datos_configuracion['smtp_auth']) {
				$smtp->Authenticate($this->datos_configuracion['smtp_usuario'], $this->datos_configuracion['smtp_clave']);
			}
		}
		if (empty($smtp->error)) {
			$smtp->Mail($this->datos_configuracion['smtp_ok']);
		}
		if (! empty($smtp->error)) {
			$mensaje = '<ul>';
			if (isset($smtp->error['smtp_code'])) {
				$mensaje .= "<li>Código SMTP: {$smtp->error['smtp_code']}</li>";
			}
			if (isset($smtp->error['error'])) {
				$mensaje .= "<li>Error: {$smtp->error['error']}</li>";
			}
			if (isset($smtp->error['smtp_msg'])) {
				$mensaje .= "<li>Mensaje SMTP: {$smtp->error['smtp_msg']}</li>";
			}
			$mensaje .= '</ul>';				
			$this->set_error('error_smtp', $mensaje);
		} else {
			$smtp->Close();
			$this->datos_configuracion['smtp_ok'] = 1;
		}
	}
	
}
?>