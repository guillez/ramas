<?php
use ext_bootstrap\componentes\bootstrap_ci;

class ci_nav_envio_emails extends bootstrap_ci
{
    protected $s__form_encuesta;
	protected $s__form_mail;
    protected $s__path1;
    protected $s__path2;
    protected $s__path3;
	protected $s__enviados = array();
    protected $s__usuarios = array();
    protected $s__usuarios_asociados = array();
    protected $s__envios_anteriores  = array();

    function conf__seleccion(toba_ei_pantalla $pantalla)
	{
		try {
			toba::instalacion()->get_datos_smtp();
		} catch (toba_error $e) {
			$pantalla->eliminar_evento('cambiar_tab__siguiente');
			toba::notificacion()->error('Necesita predeterminar una conexión SMTP en Configuración > Configuración de Mails.');
		}
	}
    
    function conf__mail(toba_ei_pantalla $pantalla)
	{
        $pantalla->evento('cambiar_tab__siguiente')->set_etiqueta('&Enviar mails');
        $pantalla->evento('cambiar_tab__siguiente')->set_imagen('glyphicon-envelope', 'proyecto');
    }
    
    function conf__resultados(toba_ei_pantalla $pantalla)
    {
        $this->enviar_mails();
        $pantalla->eliminar_evento('cambiar_tab__anterior');
    }
    
    function conf__resultados_envios(toba_ei_cuadro $cuadro)
    {
        $cuadro->set_datos($this->s__enviados);
    }
    
    //---- Eventos ----------------------------------------------------------------------

	function enviar_mails()
	{
	    // Para medir el tiempo que emplea este script
        //$start = microtime(true);
        //********************************************

		if (isset($this->s__form_mail)) {
			$asunto			= $this->s__form_mail['asunto'];
			$cuerpo			= $this->s__form_mail['contenido'];
			$nombre			= $this->s__form_mail['nombre'];
            $fecha_envio    = date('Y-m-d');
			$hora_envio		= date('H:i:s');

            $mail = array(
                            'nombre'		=>	$nombre,
                            'asunto'		=>	$asunto,
                            'contenido'		=>	$cuerpo,
                            'fecha_envio'   =>	$fecha_envio,
                            'hora_envio'	=>	$hora_envio
                         );
            
            $this->tabla('mails')->set($mail);
            
			// Controlo si tengo que enviar pie de mail
			if ($this->s__form_mail['nota_al_pie'] == "S") {
			    $separador_con_caracteres_idoneos = "<p>--------------------</p>";
			    $cuerpo = $cuerpo . $separador_con_caracteres_idoneos . $this->s__form_mail['nota_al_pie_contenido'];
            }
			
			$server		= $_SERVER['SERVER_NAME'];
			$link		= kolla_url::get_protocolo().$server.toba::vinculador()->get_url(null, '2');
			$link_pass	= kolla_url::get_protocolo().$server.toba::vinculador()->get_url(null, '45000010');
            $hubo_error = false;
			
            if (isset($this->s__form_encuesta['habilitacion'])) {
                if (empty($this->s__form_encuesta['formulario_habilitado'])) {
                    $formularios_habilitados = toba::consulta_php('consultas_formularios')->get_formularios_habilitados_habilitacion($this->s__form_encuesta['habilitacion']);
                    $formularios_habilitados = kolla_arreglos::aplanar_matriz_sin_nulos($formularios_habilitados, 'formulario_habilitado');
                } else {
                    $formularios_habilitados = $this->s__form_encuesta['formulario_habilitado'];
                }
            } else {
                $formularios_habilitados = array();
            }

			// Se crea la clase mailer y se configura el mail
            $mail = new toba_mail_eficiente("", $asunto, "");
            $mail->set_configuracion_smtp($this->s__form_mail['from']);
            $mail->set_html(true);

            if (!is_null($this->s__path1)) {
                $mail->agregar_adjunto(basename($this->s__path1), $this->s__path1, 'base64');
            }

            if (!is_null($this->s__path2)) {
                $mail->agregar_adjunto(basename($this->s__path2), $this->s__path2, 'base64');
            }

            if (!is_null($this->s__path3)) {
                $mail->agregar_adjunto(basename($this->s__path3), $this->s__path3, 'base64');
            }

            $mail->configurar_nuevo_envio_masivo();

            foreach ($this->get_usuarios_asociados() as $usuario) :
                if (!array_key_exists($usuario['encuestado'], $this->s__enviados)) {
                    if (stripos($cuerpo, '[clave_usuario]') !== false) {
                        $clave = $this->regenerar_clave_encuestado($usuario['usuario']);
                    } else {
                        $clave = '';
                    }

                    //Armar el contenido del mail
                    $parametros = $this->get_parametros();
                    $parametros = explode(', ', $parametros);
                    $contenido  = str_replace($parametros, array($link, $link_pass, $usuario['usuario'], $clave, $usuario['nombre'], $usuario['doc_tipo'], $usuario['doc_numero']), $cuerpo);
                    $log		= 'Enviado con éxito';
                    $hubo_error = false;

                    try {
                        $mail->envio_eficiente($usuario['email'], $contenido);
                        $usuario['estado'] = $log;
                    } catch (Exception $e) {
                        $log = $e->getMessage();
                        $usuario['estado'] = $log;
                        toba::logger()->error($log);
                        $hubo_error = true;
                    }

                    $codigohash = $this->generar_hash($usuario['encuestado'], $this->s__form_encuesta['habilitacion']);
                    $log_envio = array('encuestado'	=> $usuario['encuestado'], 'mensaje' => $log, 'hash' => $codigohash);
                    $this->tabla('logs_envios')->nueva_fila($log_envio);

                    if (!empty($formularios_habilitados)) {
                        foreach ($formularios_habilitados as $formulario_habilitado) {
                            $mail_fh = array('formulario_habilitado' => $formulario_habilitado, 'encuestado' => $usuario['encuestado']);
                            $this->tabla('mail_formulario_habilitado')->nueva_fila($mail_fh);
                        }
                    }

                    $this->s__enviados[$usuario['encuestado']] = $usuario;
                }
            endforeach;

			$this->relacion()->sincronizar();
			
			if ($hubo_error) {
				toba::notificacion()->agregar('Se produjeron errores al realizar el envío.', 'warning');
			} else {
				toba::notificacion()->agregar('Se enviaron los mails solicitados.', 'info');
			}

            if (!is_null($this->s__path1)) {
                unlink($this->s__path1);
            }

            if (!is_null($this->s__path2)) {
                unlink($this->s__path2);
            }

            if (!is_null($this->s__path3)) {
                unlink($this->s__path3);
            }

            $mail->limpiar_configuracion_envio_masivo();
		}

        // Para medir el tiempo que emplea este script
        //$end = (microtime(true) - $start);
        //echo ">> >> >> Elapsed time: $end";
        //********************************************
	}
    
    function evt__volver()
	{
        $this->cancelar();
        $this->set_pantalla('seleccion');
	}
    
    //---- Varios -----------------------------------------------------------------------
	
    function cancelar()
    {
        unset($this->s__form_encuesta);
        unset($this->s__form_mail);
        unset($this->s__enviados);
        unset($this->s__usuarios);
        unset($this->s__usuarios_asociados);
        unset($this->s__envios_anteriores);
        $this->relacion()->resetear();
    }
    
	private function relacion() 
	{
		return $this->dependencia('datos');
	}	

	private function tabla($id) 
	{
		return $this->relacion()->tabla($id);
	}		
	
	protected function generar_hash($encuestado, $habilitacion)
	{
        $habilitacion = is_null($habilitacion) ? '' : $habilitacion;
		$cadena       = $encuestado.$habilitacion.sha1(uniqid(mt_rand(), true));
		$hash         = encriptar_con_sal($cadena, 'sha1', 1);
        return $hash;
	}

	protected function regenerar_clave_encuestado($usuario)
	{
		$clave_plana = kolla_usuario::kolla_generar_clave_aleatoria(8);
        toba::notificacion()->mostrar("Esta clave se generó_:".$clave_plana);

		toba_usuario::set_clave_usuario($clave_plana, $usuario);
		return $clave_plana;
	}
    
    //----------------------------------------------------------------------------------
    //---- PANTALLA SELECCION ----------------------------------------------------------
    //----------------------------------------------------------------------------------
    
    //---- Configuraciones --------------------------------------------------------------
	
	function evt__seleccion__salida()
	{
        if (empty($this->s__usuarios_asociados)) {
            throw new toba_error('Debe seleccionar al menos un usuario.');
        }
	}
    
    //---- form_usuarios ---------------------------------------------------------------

	function conf__form_usuarios(toba_ei_formulario $form)
	{
		if (isset($this->s__form_encuesta)) {
			$form->set_datos($this->s__form_encuesta);
		}
        
        $form->set_modo_descripcion(false);
        $form->set_descripcion('Recuerde que puede realizar la búsqueda por <u>Apellidos</u>, <u>Nombres</u> o <u>Usuario</u>, sin indicar Unidad de Gestión y Habilitación.');
	}

	function evt__form_usuarios__filtrar($datos)
	{
        $this->s__form_encuesta = $datos;
	}
    
    function evt__form_usuarios__cancelar()
	{
        unset($this->s__form_encuesta);
	}
    
    //---- cuadro -----------------------------------------------------------------------
	
	function conf__cuadro(cuadro_seleccion_multiple $cuadro)
	{
        if (!empty($this->s__form_encuesta)) {
            //Filtro de usuarios
            $unidad_gestion = $this->s__form_encuesta['unidad_gestion'];
            $habilitacion   = $this->s__form_encuesta['habilitacion'];
            $terminada      = $this->s__form_encuesta['terminada'];
            $formulario     = $this->s__form_encuesta['formulario_habilitado'];
            $apellidos      = $this->s__form_encuesta['apellidos'];
            $nombres        = $this->s__form_encuesta['nombres'];
            $usuario        = $this->s__form_encuesta['usuario'];
            
            //Filtro de usuarios asociados
            if (empty($this->s__usuarios_asociados)) {
                $filtro = null;
            } else {
                $filtro = 'sge_encuestado.encuestado NOT IN ('.implode(',', kolla_db::quote(kolla_arreglos::aplanar_matriz_sin_nulos($this->s__usuarios_asociados, 'encuestado'))).')';
            }
            
            if (!isset($terminada)) {
                $cuadro->eliminar_columnas(array('terminada'));
            }
            
            //Se obtienen los usuarios filtrados y sin los asociados
            $this->s__usuarios = toba::consulta_php('consultas_usuarios')->get_encuestados_x_formulario($formulario, $habilitacion, $unidad_gestion, $terminada, $apellidos, $nombres, $usuario, $filtro);
            $cuadro->set_datos($this->s__usuarios);
        }
	}

	function evt__cuadro__seleccion_multiple($datos)
	{
        $this->usuarios_seleccionados = $datos;
	}

	function evt__cuadro__agregar_todos()
	{
        if (isset($this->s__usuarios)) {
            $this->s__usuarios_asociados = array_merge($this->s__usuarios_asociados, $this->s__usuarios);
        }
	}

	function evt__cuadro__agregar_marcados()
	{
        if (isset($this->usuarios_seleccionados)) {
            $this->s__usuarios_asociados = array_merge($this->s__usuarios_asociados, $this->usuarios_seleccionados);
        }
	}

	//---- cuadro_asociados -------------------------------------------------------------
	
	function conf__cuadro_asociados(cuadro_seleccion_multiple $cuadro)
	{
        if (!empty($this->s__usuarios_asociados)) {
            $usuarios = $this->get_usuarios_asociados();
            $cuadro->set_datos($usuarios);
        }
	}

	function evt__cuadro_asociados__seleccion_multiple($datos)
	{
        $this->usuarios_asociados_seleccionados = $datos;
	}

	function evt__cuadro_asociados__quitar_marcados()
	{
        if (isset($this->usuarios_asociados_seleccionados)) {
            $usuarios_asociados_aplanado = kolla_arreglos::aplanar_matriz_sin_nulos($this->s__usuarios_asociados, 'encuestado');
            foreach ($this->usuarios_asociados_seleccionados as $usuario) {
                $indice = array_search($usuario['encuestado'] , $usuarios_asociados_aplanado);
                unset($this->s__usuarios_asociados[$indice]);
            }
        }
	}
    
    function get_usuarios_asociados()
    {
        $filtro = 'sge_encuestado.encuestado IN ('.implode(',', kolla_db::quote(kolla_arreglos::aplanar_matriz_sin_nulos($this->s__usuarios_asociados, 'encuestado'))).')';
        return toba::consulta_php('consultas_usuarios')->get_encuestados_x_formulario(array(), null, null, null, null, null, null, $filtro);
    }
    
    //----------------------------------------------------------------------------------
    //---- PANTALLA ENVÍOS -------------------------------------------------------------
    //----------------------------------------------------------------------------------
    
    //---- form_envios_anteriores -------------------------------------------------------
	
	function conf__form_envios_anteriores(toba_ei_formulario $form)
	{
        if (!empty($this->s__envios_anteriores)) {
            $form->set_datos($this->s__envios_anteriores);
        }
	}

	function evt__form_envios_anteriores__modificacion($datos)
	{
        $this->s__envios_anteriores = $datos;
        $param = array();
        
        if (isset($this->s__envios_anteriores['mail'])) {
            $param = array('mail' => $datos['mail']);
        } elseif (isset($this->s__envios_anteriores['mail_sin_habilitacion'])) {
            $param = array('mail' => $datos['mail_sin_habilitacion']);
        }
        
        if (!empty($param)) {
            $envio = current(toba::consulta_php('consultas_mgn')->get_envios_mail($param));
			$envio['parametros'] = $this->get_parametros();
            $this->s__form_mail = $envio;
		} elseif (isset($this->s__form_mail)) {
            unset($this->s__form_mail);
        }
	}
    
    //----------------------------------------------------------------------------------
    //---- PANTALLA MAIL ---------------------------------------------------------------
    //----------------------------------------------------------------------------------
    
    //---- form_mails -------------------------------------------------------------------

	function conf__form_mail(toba_ei_formulario $form)
	{
		if (isset($this->s__form_mail)) {
			$datos = $this->s__form_mail;
		} else {
            $datos               = array();
            $datos['contenido']  = $this->get_contenido();
            $datos['parametros'] = $this->get_parametros();
		}
        
		$form->set_datos($datos);
	}

	function evt__form_mail__modificacion($datos)
	{
        $this->s__path1 = null;
        $this->s__path2 = null;
        $this->s__path3 = null;
		$this->s__form_mail = $datos;

        
        if (isset($datos['archivo1'])) {
            $this->s__path1 = toba::proyecto()->get_path_temp().'/'.$datos['archivo1']['name'];
            //Mover el archivo1 subido al servidor del directorio temporal PHP a uno propio.
            move_uploaded_file($datos['archivo1']['tmp_name'], $this->s__path1);
        }
        
        if (isset($datos['archivo2'])) {
            $this->s__path2 = toba::proyecto()->get_path_temp().'/'.$datos['archivo2']['name'];
            //Mover el archivo2 subido al servidor del directorio temporal PHP a uno propio.
            move_uploaded_file($datos['archivo2']['tmp_name'], $this->s__path2);
        }
        
        if (isset($datos['archivo3'])) {
            $this->s__path3 = toba::proyecto()->get_path_temp().'/'.$datos['archivo3']['name'];
            //Mover el archivo3 subido al servidor del directorio temporal PHP a uno propio.
            move_uploaded_file($datos['archivo3']['tmp_name'], $this->s__path3);
        }
	}
    
    //-- Auxiliares
	
	function get_parametros()
	{
        return "[[link]], [[link_reestablecer]], [[usuario]], [[clave_usuario]], [[nombre]], [[tipo_doc]], [[nro_doc]]";
	}
	
	function get_contenido()
	{
		return "
			Para responder la encuesta, visite el siguiente enlace e identifiquese utilizando los datos proporcionados. <br><br>
			<b>Enlace</b>: [[link]]<br>
			<b>Usuario:</b> [[usuario]] <br><br>
			Si olvid&oacute; su contrase&ntilde;a puede reestablecerla utilizando el siguiente enlace [[link_reestablecer]]
			";
	}
    
    function get_remitentes()
	{
		$path_ini_smtp = toba::nucleo()->toba_instalacion_dir().'/smtp.ini';
		if (!file_exists($path_ini_smtp)) {
			throw new toba_error("No existe el archivo '$path_ini_smtp'");
		}
        
		$ini = new toba_ini($path_ini_smtp);
		$entradas = $ini->get_entradas();
		$remitentes = array();
		
        foreach ($entradas as $clave => $entrada)
		{
			if (isset($entrada['from']) && isset($entrada['nombre_from'])) {
				$remitentes[] = array('from' => $clave, 'nombre_from' => $entrada['nombre_from'].' <'.$entrada['from'].'>');
			}
		}
		
		return $remitentes;
	}
    
    //-- Respuesta ajax a la habilitación
    
    function ajax__es_anonima($habilitacion, toba_ajax_respuesta $respuesta)
	{
		$hab_anonima = toba::consulta_php('consultas_habilitaciones')->es_habilitacion_anonima($habilitacion);
		$estructura = array('hab_anonima' => $hab_anonima);
		$respuesta->set($estructura);
	}
    
}
?>
