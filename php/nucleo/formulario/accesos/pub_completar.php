<?php

class pub_completar
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

    function __construct()
    {
        $usuario = toba::consulta_php('consultas_usuarios')->get_datos_usuario_privado();
        $this->usuario = $usuario['usuario'];
        $this->es_guest = true;
    }

    //-----------------------------------------------------------------------------------
    //---- Configuraciones --------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function procesar()
    {
        kolla::logger()->debug('Procesar');
        $this->parametros_memoria();

        if (is_null($this->habilitacion) || is_null($this->formulario_habilitado)) {
            //toba::vinculador()->navegar_a(null, '200000004', null);
            toba::vinculador()->navegar_a(null, '12000094', null);
            return;
        }

        //Validar existencia y acceso
        $this->validar_y_cargar_formulario_habilitado($this->habilitacion, $this->formulario_habilitado);

        //Determinar el estado de la encuesta seleccionada para el encuestado
        $this->encuestado = toba::consulta_php('consultas_usuarios')->get_codigo_encuestado($this->get_usuario());
        $this->es_guest = true;
        $this->es_anonima = $this->datos_habilitacion['anonima'] == 'S';
        $this->es_publica = $this->datos_habilitacion['publica'] == 'S';

		if (!$this->es_publica) {
            $this->throw_error('ERROR EN EL ACCESO, no es una encuesta pública');
        }

        $this->generar_respuesta();
    }

    protected function validar_y_cargar_formulario_habilitado($hab,$form_hab)
    {
        //se valida existencia de habilitacion y formulario habilitado y la vigencia
        $datos_habilitacion =toba::consulta_php('consultas_habilitaciones')->get_datos_formulario_habilitado_publico($hab,$form_hab);

        if (empty($datos_habilitacion)) {
            $this->throw_error('ERROR EN EL ACCESO, no es una encuesta pública habilitada');
        } else {
            $this->datos_habilitacion = $datos_habilitacion[0];
        }
    }


    protected function parametros_memoria()
    {
        $h = toba::memoria()->get_parametro('h');
        $f = toba::memoria()->get_parametro('f');

        if (isset($h)) {
            toba::memoria()->set_dato_operacion('h', $h);
        } else {
            $h = toba::memoria()->get_dato_operacion('h');
        }

        if (isset($f)) {
            toba::memoria()->set_dato_operacion('f', $f);
        } else {
            $f = toba::memoria()->get_dato_operacion('f');
        }

        $this->habilitacion = $h;
        $this->formulario_habilitado = $f;
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


    private function generar_respuesta()
    {
        //si se está buscando volver a responder se debe borrar el dato de sesión y redirigirse al inicio de la
        //encuesta pública
        if (isset($_POST['reiniciar'])) {
            $_SESSION[$this->get_usuario() . $this->formulario_habilitado . 'codigo'] = null;
            toba::vinculador()->navegar_a(null, '40000174', array('h' => $this->habilitacion, 'f' => $this->formulario_habilitado));
        }

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
        $datos_adjunto['responder_por_encuestado'] = false;

        $controlador->set_datos_adjunto($datos_adjunto);

        //Si ya se respondió se muestra nuevamente - el hash no se regenera y el código se obtiene si no es anónima
        if ($resp_encuestado['terminado'] == 'S') {
            $comprobante = $this->new_comprobante($resp_encuestado['respondido_formulario'], $controlador->get_titulo_formulario(), $fecha);
            $cod_rec = null;
            $comprobante->set_datos_recuperacion($cod_rec, null);
            $comprobante->set_plantilla_css($this->config->get_plantilla_css());
            $comprobante->generar_interface();
            $this->links_cierre();
            return;
        }

        include_once('nucleo/formulario/vista/builder_internos.php');
        $vista = new builder_internos();
        $this->config->set_vista_builder($vista);

        // Se muestra por primera vez
        if ($controlador->procesar_request()) {
            $resp_formulario = $this->config->get_respondido_formulario();
            $_SESSION[$this->get_usuario() . $this->formulario_habilitado . 'codigo'] = $resp_formulario;
            $comprobante = $this->new_comprobante($resp_formulario, $controlador->get_titulo_formulario(), $fecha);
            $comprobante->set_datos_recuperacion($controlador->get_random_guardado(), $controlador->get_hash());
            $comprobante->set_plantilla_css($this->config->get_plantilla_css());
            $comprobante->generar_interface();
            $this->links_cierre();
            return;
        }
    }

    private function get_respondido_formulario($encuestado)
    {
        $datos = array('respondido_formulario' => null,
            'terminado' 			=> 'N',
            'fecha' 				=> kolla_fecha::get_hoy(true),
            'fecha_hora' 			=> kolla_fecha::get_hoy_hora(true));

        if ($this->es_anonima) {
            $datos['terminado'] = 'S';
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
        $config->set_respondido_por(null);
        $config->set_editable(true);
        $config->set_guest(true);
        return $config;
    }

    private function new_comprobante($resp_formulario, $titulo, $fecha)
    {
        include_once('nucleo/formulario/vista/comprobante_formulario.php');
        $comprobante = new comprobante_formulario($resp_formulario, $titulo, $fecha, $this->config->get_url_post());

        $comprobante->set_modo_interno_guest();
        $comprobante->agregar_accion_enviar();
        $comprobante->agregar_accion_imprimir_respuestas($this->formulario_habilitado);

        return $comprobante;
    }


    private function links_cierre()
    {
        //$urlkolla = toba::vinculador()->get_url(null, '200000004', null);
        $urlkolla = toba::vinculador()->get_url(null, '12000094', null);

        $url = toba::vinculador()->get_url();

        $form = "
                <div class='container'>
                    <div class='row-fluid'>
                        <div class='span12'>
                            <form action='". $url ."' method='post'>
                                <a href='".$urlkolla."' class='btn btn-default btn-sm'>Ir a SIU-Kolla</a>
                                <button class='btn btn-default' value='reiniciar' name='reiniciar' type='submit'>Contestar nuevamente la encuesta</button>
                        </div>
                    </div>
                </div>
    	        ";

        echo $form;
    }

}

?>
