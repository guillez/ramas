<?php

class int_completar
{
	public $datos_habilitacion;
	public $config;
    
	protected $habilitacion;
	protected $formulario_habilitado;
	protected $encuestado;
	protected $es_guest;
	protected $es_anonima;
	protected $generar_cod_recuperacion;
	protected $usuario;
	protected $cuadro;
    protected $con_header = true;

    const cuadro_responder_por_encuestado = 45000011;
    
	function __construct($cuadro = null, $con_header = true)
	{
		$this->cuadro = $cuadro;
        $this->con_header = $con_header;
	}

	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function procesar()
	{
		kolla::logger()->debug('Comenzando');
		$this->cargar_hab_form_y_usuario();
        
        //Si no hay habilitación indicada, volver a la pantalla de selección
		if (!isset($this->habilitacion) || !isset($this->formulario_habilitado)) {
			toba::vinculador()->navegar_a(null, '200000026', null);
			return;
		}

		//De lo contrario, evaluar el estado de la encuesta y el encuestado, para 
		//determinar si hay que mostrar ticket o levantar el formulario para responder
		$this->datos_habilitacion = $this->cargar_datos_habilitacion($this->habilitacion);

		//Determinar el estado de la encuesta seleccionada para el encuestado
		$this->encuestado = toba::consulta_php('consultas_usuarios')->get_codigo_encuestado($this->get_usuario());
		$this->es_guest = toba::consulta_php('consultas_usuarios')->es_guest_actual();
		$this->es_anonima = $this->datos_habilitacion['anonima'] == 'S';

		$this->generar_respuesta();
	}

	protected function cargar_hab_form_y_usuario()
	{
        if ($this->con_header) {
            $fila = toba::memoria()->get_parametro('fila');
            
            if (isset($fila)) {
                toba::memoria()->set_dato_operacion('fila_segura', $fila);
            } else {
                $fila = toba::memoria()->get_dato_operacion('fila_segura');
            }
            
            $seleccion = toba_ei_cuadro::recuperar_clave_fila($this->cuadro, $fila);
        } else {
            $seleccion = toba::memoria()->get_dato_operacion('clave_fila');
        }

		$this->habilitacion = $seleccion['habilitacion'];
		$this->formulario_habilitado = $seleccion['formulario'];

		if (isset($seleccion['usuario_encuestado'])) {
			$this->usuario = $seleccion['usuario_encuestado']; // Se da solo en el caso de "Responder por encuestado"
		} else {
			$this->usuario = null;
		}
	}

	protected function cargar_datos_habilitacion($hab)
	{
		$datos_habilitacion = catalogo::consultar(dao_encuestas::instancia(), 'get_datos_habilitacion', array($hab));

		if (empty($datos_habilitacion)) {
			$this->throw_error('ERROR EN EL ACCESO, no se encontraron encuestas habilitadas');
		} else {
			return $datos_habilitacion[0];
		}
	}

	private function throw_error($mje)
	{
		toba::notificacion()->agregar($mje, 'info');
		throw new toba_error_validacion($mje);
	}

	function get_usuario()
	{
		if (isset($this->usuario)) {
			return $this->usuario;
		} else {
			return toba::usuario()->get_id();
		}
	}
    
    function get_respondido_por()
    {
        if (isset($this->usuario)) { // Sólo se setea si se responde por encuestado
            return toba::consulta_php('consultas_usuarios')->get_codigo_encuestado(toba::usuario()->get_id());
        } else {
            return null;
        }
    }

	private function generar_respuesta()
	{
		$resp_encuestado = $this->get_respondido_formulario($this->encuestado);
		$this->config = $this->crear_configuracion($resp_encuestado['respondido_formulario']);
		$controlador = new formulario_controlador();
		$controlador->set_configuracion($this->config);
		
		//Fecha y hora dependiendo de si la habilitación es anónima o no
		$datos_form_habilitado = toba::consulta_php('consultas_formularios')->get_formulario_habilitado($this->formulario_habilitado);
		$es_habilitacion_anonima = toba::consulta_php('consultas_habilitaciones')->es_habilitacion_anonima($datos_form_habilitado['habilitacion']);
		$fecha = $es_habilitacion_anonima == 'S' ? $resp_encuestado['fecha'] : $resp_encuestado['fecha_hora'];
		
        //Datos para evaluar el envío del mail, y sus adjuntos
        $datos_adjunto = array();
        $datos_adjunto['fecha'] = $fecha;
        $datos_adjunto['es_anonima'] = $this->es_anonima;
        $datos_adjunto['responder_por_encuestado'] = $this->cuadro == self::cuadro_responder_por_encuestado;
        
        $controlador->set_datos_adjunto($datos_adjunto);
                
		//Si ya se respondió se muestra nuevamente - el hash no se regenera y el código se obtiene si no es anónima
		if ($resp_encuestado['terminado'] == 'S') {
			$comprobante = $this->new_comprobante($resp_encuestado['respondido_formulario'], $controlador->get_titulo_formulario(), $fecha);
			$cod_rec = $this->get_random_existente();
			$comprobante->set_datos_recuperacion($cod_rec, null);
			$comprobante->set_plantilla_css($this->config->get_plantilla_css());
			$comprobante->generar_interface();
			return;
		}
        
		include_once('nucleo/formulario/vista/builder_internos.php');
		$vista = new builder_internos();
        $vista->set_mostrar_header($this->con_header);
		$this->config->set_vista_builder($vista);
        
        // Se muestra por primera vez
		if ($controlador->procesar_request()) {
			$resp_formulario = $this->config->get_respondido_formulario();
			$_SESSION[$this->get_usuario() . $this->formulario_habilitado . 'codigo'] = $resp_formulario;
			$comprobante = $this->new_comprobante($resp_formulario, $controlador->get_titulo_formulario(), $fecha);
			$comprobante->set_datos_recuperacion($controlador->get_random_guardado(), $controlador->get_hash());
			$comprobante->set_plantilla_css($this->config->get_plantilla_css());
            
            if ($this->cuadro == self::cuadro_responder_por_encuestado) {
                if ($controlador->get_envio_ok()) {
                    $mensaje  = 'Se envió un mail automático al encuestado con el Comprobante de encuesta respondida';
                    $mensaje .= $this->es_anonima ? '.' : ' y la encuesta respondida.';
                    $comprobante->set_notificacion_mail_gestor($mensaje, true);
                } else {
                    $comprobante->set_notificacion_mail_gestor('No se pudo enviar el mail automático al encuestado, por favor revise la configuración del servidor de mail.', false);
                }
            }
            
			$comprobante->generar_interface();
			return;
		}
	}

	private function get_respondido_formulario($encuestado)
	{
     	$datos = array();
     	
        if (!$this->es_guest) {
          	$encuestado = kolla_db::quote($encuestado);
			$form_habilitado = kolla_db::quote($this->formulario_habilitado);

			$sql = "SELECT	respondido_formulario,
							terminado,
			                to_char(fecha, '".kolla_sql::formato_fecha_visual."')					AS fecha,
							to_char(fecha, '".kolla_sql::formato_fecha_hora_visual_sin_segundos."') AS fecha_hora
		            FROM	sge_respondido_encuestado
		            WHERE	encuestado = $encuestado
					AND		formulario_habilitado = $form_habilitado
		        	";

			$datos = kolla_db::consultar_fila($sql);
        }
		
		if (empty($datos)) {
			 $datos = array('respondido_formulario' => null,
            				'terminado' 			=> 'N',
            				'fecha' 				=> kolla_fecha::get_hoy(true),
			 				'fecha_hora' 			=> kolla_fecha::get_hoy_hora(true));
		} else {
			if ($this->es_anonima) {
				$datos['terminado'] = 'S';
			}
		}
		
		if (isset($_SESSION[$this->get_usuario() . $this->formulario_habilitado . 'codigo']) && is_null($datos['respondido_formulario'])) {
     		$datos['respondido_formulario'] = $_SESSION[$this->get_usuario() . $this->formulario_habilitado.'codigo'];
     		$datos['terminado'] = 'S';
     	}
        
		return $datos;
	}
	
	/**
	 * @param $respondido_formulario
	 * @return formulario_controlador_config
	 */
	protected function crear_configuracion($respondido_formulario)
	{
		$url = toba::vinculador()->get_url();
		include_once('nucleo/formulario/formulario_controlador_config.php');
		$config = new formulario_controlador_config($this->formulario_habilitado, $respondido_formulario);
		$config->set_datos_habilitacion($this->datos_habilitacion);
		$config->set_url_post($url);
		$config->set_encuestado($this->encuestado);
        $config->set_respondido_por($this->get_respondido_por());
		$config->set_editable(true);
		$config->set_guest($this->es_guest);
		return $config;
	}

	private function new_comprobante($resp_formulario, $titulo, $fecha)
	{
		include_once('nucleo/formulario/vista/comprobante_formulario.php');
		$comprobante = new comprobante_formulario($resp_formulario, $titulo, $fecha, $this->config->get_url_post());
		
        if ($this->es_guest) {
			$comprobante->set_modo_interno_guest();
		} else {
			$comprobante->set_modo_interno($this->get_usuario());
		}
        
		$comprobante->agregar_accion_enviar();
		$comprobante->agregar_accion_imprimir_respuestas($this->formulario_habilitado);
		return $comprobante;
	}

	private function get_random_existente()
	{
		if ($this->es_anonima || $this->es_guest) {
			return null;
		}
		
		$encuestado = kolla_db::quote($this->encuestado);
		
		$sql = "SELECT	sge_respondido_formulario.codigo_recuperacion
				FROM	sge_respondido_encuestado,
						sge_respondido_formulario
				WHERE	sge_respondido_encuestado.respondido_formulario = sge_respondido_formulario.respondido_formulario
				AND 	sge_respondido_encuestado.encuestado = $encuestado";
		
		$datos = kolla_db::consultar_fila($sql);
		return $datos['codigo_recuperacion'];
	}
}

?>