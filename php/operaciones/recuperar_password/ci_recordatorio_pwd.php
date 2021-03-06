<?php
use ext_bootstrap\componentes\bootstrap_ci;

class ci_recordatorio_pwd extends bootstrap_ci
{
	protected $usuario;
	protected $randr;
	protected $s__email;
	
	function ini()
	{
		//Preguntar en toba::memoria si vienen los parametros
		if (! isset($this->usuario)) {
			$this->usuario = toba::memoria()->get_parametro('usuario');
			$this->randr = toba::memoria()->get_parametro('randr');        //Esto hara las veces de unique para la renovacion
		}

		//Esto es por si el chango trata de entrar al item directamente
		$item = toba::memoria()->get_item_solicitado();
		$tms = toba_manejador_sesiones::instancia();
		if ($item[0] == 'toba_editor' && !$tms->existe_usuario_activo()) {
			throw new toba_error_ini_sesion('No se puede correr este item fuera del editor');
		}
	}

	//-----------------------------------------------------------------------------------
	//---- formulario -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_usuario(toba_ei_formulario $form)
	{
		$form->set_modo_descripcion(false);
		$form->set_descripcion('Se enviar? un mail a su casilla de correo electr?nico con instrucciones de c?mo restablecer su contrase?a.');
	}

	function evt__form_usuario__modificacion($datos)
	{
		if ( $datos['captcha'] ) {
			//Miro que vengan los datos que necesito
			if ( !isset($datos['usuario']) ) {
				throw new toba_error_autenticacion('No se suministro un usuario v?lido');
			}

			//Si el usuario existe, entonces disparo el envio de mail 
			if ( !$this->verificar_usuario_activo($datos['usuario']) ) {
				throw new toba_error_autenticacion('No se suministro un usuario v?lido');
			}
			
			$this->usuario = $datos['usuario'];
			$this->s__email = $this->recuperar_direccion_mail_usuario($this->usuario);	
		} else {
			throw new toba_error_autenticacion('El c?digo de verificaci?n ingresado no es v?lido');
		}
	}
	
	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__recordame()
	{
        $grupos = toba::instancia()->get_grupos_acceso($this->usuario, 'kolla');
        if ( in_array('externo', $grupos) || in_array('guest', $grupos) ) {
            toba::notificacion()->agregar('Para recuperar su clave contactese con un administrador del sistema.', 'info');   
        } else {
            $this->enviar_mail_aviso_cambio();
        	toba::notificacion()->agregar('Se ha enviado un mail a la cuenta especificada, por favor verifiquela', 'info');   
        }
	}
	
	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__pant_inicial(toba_ei_pantalla $pantalla)
	{
		//Si viene con el random seteado es que esta confirmando el cambio de contrase?a
		if (isset($this->randr) && ! is_null($this->randr)) {
			$pantalla->eliminar_dep('form_usuario');
			$pantalla->eliminar_evento('recordame');
			$this->disparar_confirmacion_cambio();
			$pantalla->set_descripcion('La nueva contrase?a fue enviada a su cuenta de mail.');
		}
	}
	
	//----------------------------------------------------------------------------------------
	//-------- Procesamiento del pedido ------------------------------------------
	//----------------------------------------------------------------------------------------
	
	/**
	 * Verifico que el usuario existe a traves de la API de toba_usuario
	 */
	function verificar_usuario_activo($usuario)
	{
		try {
			toba::instancia()->get_info_usuario($usuario);        //Tengo que verificar que el negro existe
		} catch (toba_error_db $e) {                        //Ni true ni false... revienta... el mono no existe
			toba::logger()->error('Se intento modificar la clave del usuario:' . $usuario);
			return false;
		}
		return true;
	}
	
	/**
	 * Recupera la direccion de mail de usuario
	 * @param string $usuario
	 * @return string 
	 */
	function recuperar_direccion_mail_usuario($usuario)
	{
		try {
			$datos = toba::instancia()->get_info_usuario($usuario);        //Tengo que verificar que el negro existe
			return $datos['email'];
		} catch (toba_error $e) {                        
			toba::logger()->error('Se intento modificar la clave del usuario:' . $usuario);
			return null;
		}
	}
		
	/**
	 * 
	 * Aca envio un primer mail con un link para confirmar el cambio, si no lo usa... fue
	 */
	function enviar_mail_aviso_cambio()
	{
		//Genero un pseudorandom unico... 
		$tmp_rand = $this->get_random_temporal();
		$link = $this->generar_link_confirmacion($this->usuario, $tmp_rand);    //Genero el link para el mail

		//Se envia el mail a la direccion especificada por el usuario.
		$asunto = 'Solicitud de cambio de contrase?a';
		$cuerpo_mail = '<p>Este mail fue enviado a esta cuenta porque se <strong>solicito un cambio de contrase?a</strong>.</p>'
		. '<p>Si usted solicito dicho cambio haga click en el siguiente enlace:</p>'
        . '<a href=' . $link . '>' . $link . '</a>'
		. '<p>El mismo ser? v?lido unicamente por 24hs.</p>';

		//Guardo el random asociado al usuario y envio el mail
		toba::instancia()->get_db()->abrir_transaccion();
		
		try {
			$this->guardar_datos_solicitud_cambio($tmp_rand, $this->s__email);
			$mail = new toba_mail($this->s__email, $asunto, $cuerpo_mail);
			$mail->set_html(true);
			$mail->enviar();
			toba::instancia()->get_db()->cerrar_transaccion();
		} catch (toba_error $e) {
			toba::instancia()->get_db()->abortar_transaccion();
			toba::logger()->debug('Proceso de envio de random a cuenta: '. $e->getMessage());
			throw new toba_error('Se produjo un error en el proceso de cambio, contactese con un administrador del sistema.');
		}
	}

	/**
	 * Deberia generar un random.. quien sabe que tan bueno o malo sea
	 */
	function get_random_temporal()
	{
		$uuid = uniqid(rand(), true);
		$rnd = sha1(microtime() . $uuid . rand());
		return $rnd;
	}

	/**
	 * Obtiene una url con los parametros necesarios para que se haga la confirmacion
	 */
	function generar_link_confirmacion($usuario, $random)
	{
		$path = toba::proyecto()->get_www();
		$opciones = array('prefijo' => $_SERVER['SERVER_NAME'] . $path['url']);
		$parametros = array('usuario' => $usuario, 'randr' => $random);
        $url = kolla_url::get_protocolo() . toba::vinculador()->get_url(null, null, $parametros, $opciones);
		return $url;
	}

    /**
	 * Impacta en la base para cambiar la contrase?a del usuario
	 */
	function disparar_confirmacion_cambio()
	{
		//Aca tengo que generar una clave temporal y enviarsela para que confirme el cambio e ingrese con ella.
		//$clave_tmp = toba_usuario::generar_clave_aleatoria('10');
        $clave_tmp = kolla_usuario::kolla_generar_clave_aleatoria(8);

		//Recupero mail del usuario junto con el hash de confirmacion
		$datos_rs = $this->recuperar_datos_solicitud_cambio($this->usuario, $this->randr);
		
		if (empty($datos_rs)) {
			toba::logger()->debug('Proceso de cambio de contrase?a en base: El usuario o el random no coinciden' );
			toba::logger()->var_dump(array('rnd' => $this->randr));
			throw new toba_error('Se produjo un error en el proceso de cambio, contactese con un administrador del sistema.');            
		} else {
			$datos_orig = current($datos_rs);
		}
		
		//Armo el mail nuevo
		$asunto = 'Nueva contrase?a';
		$cuerpo_mail = '<p>Se ha recibido su confirmaci?n exitosamente, su contrase?a fue cambiada a:</p>' .
		$clave_tmp . '<p>Por favor en cuanto pueda cambiela a una contrase?a m?s segura.</p> <p>Gracias.</p> ';

		//Cambio la clave del flaco, envio el nuevo mail y bloqueo el random
		toba::instancia()->get_db()->abrir_transaccion();
		try {
			//Recupero los dias de validez de la clave, si existe
			$dias = toba::proyecto()->get_parametro('dias_validez_clave', null, false);
			
			//Seteo la clave para el usuario
			toba_usuario::reemplazar_clave_vencida($clave_tmp, $datos_orig['id_usuario'], $dias);
			
			//Enviar nuevo mail con la clave temporaria
			$mail = new toba_mail($datos_orig['email'], $asunto, $cuerpo_mail);
			$mail->set_html(true);
			$mail->enviar();

			//Bloqueo el pedido para que no pueda ser reutilizado
			$this->bloquear_random_utilizado($this->usuario, $this->randr);
			toba::instancia()->get_db()->cerrar_transaccion();
		} catch (toba_error $e) {
			toba::instancia()->get_db()->abortar_transaccion();
			toba::logger()->debug('Proceso de cambio de contrase?a en base: ' . $e->getMessage());
			throw new toba_error('Se produjo un error en el proceso de cambio, contactese con un administrador del sistema.');
		}
	}

	//-----------------------------------------------------------------------------------
	//                                        METODOS PARA SQLs
	//-----------------------------------------------------------------------------------
	
	function guardar_datos_solicitud_cambio($random, $mail)
	{
		$sql = 'UPDATE apex_usuario_pwd_reset SET bloqueado = 1 WHERE usuario = :usuario;';
		$up_sql = toba::instancia()->get_db()->sentencia_preparar($sql);
		$rs = toba::instancia()->get_db()->sentencia_ejecutar($up_sql, array('usuario'=>$this->usuario));

		$sql = 'INSERT INTO apex_usuario_pwd_reset (usuario, random, email) VALUES (:usuario, :random, :mail);';
		$in_sql = toba::instancia()->get_db()->sentencia_preparar($sql);
		$rs = toba::instancia()->get_db()->sentencia_ejecutar($in_sql, array('usuario'=>$this->usuario, 'random' => $random, 'mail' => $mail));
	}
	
	function recuperar_datos_solicitud_cambio($usuario, $random)
	{
		$sql = "
			SELECT
				usuario as id_usuario,
				email
			FROM
				apex_usuario_pwd_reset
			WHERE
				usuario = :usuario AND
				random = :random AND
				age(now() , validez)  < interval '1 day' AND
				bloqueado = 0
		";
        
		$id = toba::instancia()->get_db()->sentencia_preparar($sql);
		$rs = toba::instancia()->get_db()->sentencia_consultar($id, array('usuario' => $usuario, 'random' => $random));
		
		return $rs;
	}

	function bloquear_random_utilizado($usuario, $random)
	{
		$sql = '
			UPDATE 
				apex_usuario_pwd_reset  
			SET 
				bloqueado = 1
			WHERE
				usuario = :usuario AND
				random = :random
		';
		//toba::instancia()->get_db()->set_modo_debug(true, true);
		$id = toba::instancia()->get_db()->sentencia_preparar($sql);
		$rs = toba::instancia()->get_db()->sentencia_ejecutar($id, array('usuario'=>$usuario, 'random' => $random));
	}

}
?>