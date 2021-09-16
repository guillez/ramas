<?php

class formulario_controlador
{
    /**
     * @var formulario_controlador_config
     */
	protected $config;
	protected $codigo_recuperacion;
    protected $datos_adjunto = array();
    protected $envio_ok;
	
	function set_configuracion(formulario_controlador_config $c)
    {
		$this->config = $c;
	}
	
	function get_configuracion()
	{
		return $this->config;
	}
	
    function get_envio_ok()
	{
		return $this->envio_ok;
	}
    
	function set_modo_completar_formulario_interno($id_habilitacion, $url_post, $id_usuario, $form_habilitado)
    {
		$this->set_modo_completar($id_habilitacion, $url_post, $id_usuario);
		$this->es_externo = false;
		$this->obtener_planilla($form_habilitado);
	}
	
    function set_datos_adjunto($datos_adjunto)
    {
        $this->datos_adjunto = $datos_adjunto;
    }    
    
	/**
	 * Informacion sobre el formulario procesado
	 * @return type
	 */
	function get_titulo_formulario()
	{
		$datos = $this->config->get_formulario()->get_datos();
		return $datos[0]['formulario'];
	}
	
	/**
	 * Informacion sobre el formulario procesado. Solo valido si proceso guardo recien
	 * @return type
	 */
	function get_random_guardado()
	{
		return $this->codigo_recuperacion;
	}
	
	/**
	 * Informacion sobre el formulario procesado. Valido si esta cargado.
	 * @return type
	 */
	function get_hash()
    {
		return  $this->generar_hash($this->config->get_formulario());
	}
	
	/**
	 * @return boolean true si se guardo la encuesta
	 */
	function procesar_request()
    {
		// Guardar y/o terminar si es necesario.
		if (!$this->config->procesa_post()) {
			$this->accion_index();
		} elseif (isset($_POST['guardar'])) {
			$this->accion_guardar();
		} elseif (isset($_POST['terminar'])) {
			return $this->accion_terminar();
		} else {
			$this->accion_index(); //default
		}
		return false;
	}
	
	protected function accion_index()
    {
		$form = $this->config->get_formulario();
		$vista = $this->config->get_vista();
		$imprimir_respuestas_completas = $this->config->get_imprimir_respuestas_completas();
        $mostrar_aviso_guardado = $this->config->get_aviso_guardado();
        $vista->set_formulario($form);
		$vista->set_imprimir_respuestas_completas($imprimir_respuestas_completas);
		$vista->set_mostrar_aviso_guardado($mostrar_aviso_guardado);
        //$vista->set_mostrar_progreso($this->config->get_mostrar_progreso());
		$vista->generar_interface();
        $this->config->set_aviso_guardado(false);
	}
	
	protected function accion_terminar()
	{
		$form = $this->config->get_formulario();
		$form->procesar_post();
		
		if ($form->validar() === true) {
            kolla::logger()->info('Datos de formulario validados.');
			if ($this->guardar_bd(true)) {
                if (!empty($this->datos_adjunto) && ($this->datos_adjunto['responder_por_encuestado'])) {
                    $this->enviar_mail();
                }
                kolla::logger()->info('Encuesta terminada.');
				return true;
			}
		} else {
            kolla::logger()->error('Accion index');
			$this->accion_index();
		}
		
		return false;
    }

	protected function accion_guardar()
    {
		$form = $this->config->get_formulario();
		$form->procesar_post();
                if ($form->validar() === true) {
                    $this->config->set_aviso_guardado(true);
			if ($this->guardar_bd(false)) {
				kolla::logger()->debug('validacion exitosa');
			}
		}
		$this->accion_index();
	}

	//------------------------------------------------------------------------------
	//-- Procesos de validaciones
	//------------------------------------------------------------------------------
	
    function guardar_bd($terminar)
    {
		$proxy = new batcher_respuestas();
	 	$this->codigo_recuperacion = $this->generar_random();
	 	$fh = $this->config->get_formulario_habilitado();
		$rf = $this->config->get_respondido_formulario();

		try {
			$digest = anonimato_utils::$default_digest;
			$proxy->begin();
			$proxy->guardar_respondido_formulario($rf, $fh, $this->codigo_recuperacion, $digest);
			$proxy->set_terminar($terminar);
			$proxy->set_anonima($this->config->get_anonima());
			$proxy->set_codigo_externo($this->config->get_codigo_externo());
			$proxy->set_encuestado($this->config->get_encuestado());
            $proxy->set_respondido_por($this->config->get_respondido_por());
			$proxy->set_sistema($this->config->get_sistema());
			$this->config->get_formulario()->guardar_respuestas($proxy);
            $proxy->commit();
            
            /*
             * Durante lo que dure la sesión se van guardando las encuestas que
             * haya respondido un usuario guest.
             */
            if (toba::consulta_php('consultas_usuarios')->es_guest_actual()) {
                $lista_encuestas   = toba::memoria()->get_dato('lista_encuestas');
                $lista_encuestas[] = $fh;
                toba::memoria()->set_dato('lista_encuestas', $lista_encuestas);
            }
            
			$this->config->set_respondido_formulario($proxy->get_respondido_formulario());
		} catch (Exception $e) {
			kolla::logger()->debug($e->getTraceAsString());
			throw new toba_error('ERROR grabando la encuesta: '.$e->getMessage());
		}
		return true;
	}
	
    function enviar_mail()
    {
        //Componentes del email
        $datos_adjunto = $this->datos_adjunto;
        $datos   = toba::consulta_php('consultas_usuarios')->get_datos_encuestado($this->config->get_encuestado());
        $asunto  = 'Aviso de encuesta respondida';
		$cuerpo  = 'Se informa que se registró una encuesta a nombre del usuario registrado con el mail '.$datos['email'];
        $cuerpo .= ': <br><br> En un archivo adjunto se encuentra el Comprobante de encuesta respondida';
        $cuerpo .= $datos_adjunto['es_anonima'] ? '.' : ' y la encuesta respondida.';
        $cuerpo .= '<br><br> Este aviso es enviado automáticamente desde el Módulo de Gestión de Encuestas SIU-Kolla. Por favor no responda a este mail.';
        
        //Creación del email
        $mail = new toba_mail($datos['email'], $asunto, $cuerpo);
        $mail->set_html(true);
        
        //Creación del comprobante
        $path_comprobante = toba::proyecto()->get_path_temp().'/Comprobante';
        $comprobante      = new comprobante_formulario($this->config->get_respondido_formulario(), $this->get_titulo_formulario(), getdate(), $this->config->get_url_post());

        if ($datos['guest'] == 'S') {
            $comprobante->set_modo_interno_guest();
        } else {
            $comprobante->set_modo_interno($datos['usuario']);
        }

        //Se crea el pdf para el comprobante
        $comprobante->set_path($path_comprobante);
        $comprobante->set_datos_recuperacion($this->codigo_recuperacion, $this->get_hash());
        $comprobante->crear_comprobante_adjunto(array_merge($datos_adjunto, $datos));
        
        //Se adjunta el comprobante
        $mail->agregar_adjunto('Comprobante', $path_comprobante, 'base64', 'pdf');
        unlink($path_comprobante);
        
        //Si la habilitación no es anónima, también adjunto la encuesta respondida
        if (!$datos_adjunto['es_anonima']) {
            
            //Creación de la encuesta
            $path_encuesta   = toba::proyecto()->get_path_temp().'/Encuesta';
            $respondido_form = toba::consulta_php('consultas_formularios')->get_respondido_formulario($this->config->get_encuestado(), $this->config->get_formulario_habilitado());
            
            //Builder para el pdf y formulario controlador
            //$builder = new builder_pdf();
            $builder = new builder_pdf();
            $config  = new formulario_controlador_config($this->config->get_formulario_habilitado(), $respondido_form['respondido_formulario']);
            
            //Se crea el pdf de la encuesta en el path indicado
            $builder->set_es_adjunto(true);
            $builder->set_path($path_encuesta);
            $config->set_vista_builder($builder);
            $this->set_configuracion($config);
            $this->accion_index();
            
            //Se adjunta la encuesta
            $mail->agregar_adjunto('Encuesta', $path_encuesta, 'base64', 'pdf');
            unlink($path_encuesta);
        }
        
		try {
			$mail->enviar();
            $this->envio_ok = true;
		} catch (toba_error $e) {
            toba::logger()->error($e->getMessage());
            $this->envio_ok = false;
		}
    }
    
	private function generar_random()
    {
		if ($this->config->get_generar_codigo_recuperacion()) {
			return anonimato_utils::generar_random();
		}
		
		return null;
	}
	
	private function generar_hash($form)
    {
		$digest = anonimato_utils::$default_digest;
		return anonimato_utils::hashing_de_obj_form($form, $digest);
	}
	
}
?>