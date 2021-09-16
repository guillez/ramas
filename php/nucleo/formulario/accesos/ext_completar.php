<?php

$acceso_externo = new acceso_externo();
$acceso_externo->procesar();

class acceso_externo //extends toba_ci
{
    private static $instancia;
    public $titulo_formulario; //la configuracion del controlador

    //parametros del hash
    private $config;
    private $habilitacion;
    private $form_externo;
    private $accion;
    private $codigo_recuperacion;

    //datos a recuperar
    private $hash_recuperacion;    
    private $codigo_externo;
    private $datos_habilitacion;
    private $formulario_habilitado;

    public function __construct()
    {
        self::$instancia = $this;
    }

    /**
     *Genera el script js para avisar a sistemas externos que se respondio una
     * encuesta.
     */
    public static function obtener_script_encuesta_terminada($respondido_formulario)
    {
        return self::$instancia->obtener_script_encuesta_terminada_($respondido_formulario);
    }

    /**
     *Genera el script js para avisar a sistemas externos la altura de la pagina (por si quieren ajustar)
     */
    public static function obtener_script_encuesta_cargada()
    {
        return self::$instancia->obtener_script_encuesta_cargada_();
    }

    private function obtener_script_encuesta_terminada_($respondido_formulario)
    {
        $password        = $this->datos_habilitacion['password_se'];
        $param           = url_encuestas::get_token_callback_js($this->habilitacion, $this->codigo_externo, $this->form_externo, $password);
        $resp_formulario = catalogo::consultar(dao_encuestas::instancia(), 'get_respondido_formulario', array($respondido_formulario));
        $fecha_terminado = $resp_formulario[0]['fecha_terminado'];

        kolla::logger()->debug($param);

        return "<script language='javascript' type='text/javascript'>
            window.parent.postMessage({h: '$this->habilitacion', t: '$param', fecha_terminado: '$fecha_terminado', name: 'encuesta_terminada' }, '*');
        </script>";
    }

    private function obtener_script_encuesta_cargada_()
    {
        return "<script language='javascript' type='text/javascript'>
                    var body = document.body,
                    html = document.documentElement,
                    height = Math.max( body.offsetHeight, html.offsetHeight );
                    console.log('encuesta cargada - alto: ' + height);
                    window.parent.postMessage({height: height, name: 'encuesta_cargada'}, '*');
                </script>";
    }

    function procesar()
    {
        try {
            kolla::logger()->debug('Comenzando');
            //VALIDACION DE PARAMETROS
            $this->habilitacion = kolla::memoria()->get_parametro(url_encuestas::param_habilitacion); //valido

            if ( !validador::es_valido($this->habilitacion, validador::TIPO_INT) ) {
                $this->throw_error('Parámetro incorrecto.');
            }
            $this->datos_habilitacion = $this->get_datos_habilitacion($this->habilitacion);
            $password = $this->datos_habilitacion['password_se'];
            $hash     = $this->decodificar_url($password);

            $hab = $hash['habilitacion'];
            if ( $hab != $this->habilitacion ) {
                $mje = 'ERROR EN EL ACCESO: no se pudo procesar correctamente el pedido.';
                $this->throw_error($mje);
            }
            // Código externo es obligatorio
            if ( $hash['codigo_externo'] == '' ) {
                $mje = 'ERROR EN EL ACCESO: Faltan datos mínimos.';
                $this->throw_error($mje);
            }

            // Visualización de respuestas
            if ( isset($hash['accion']) && $hash['accion'] == 'ver_respuestas' ) {
                $anonima = $this->datos_habilitacion['anonima'] == 'S';
                $gen_cod = $this->datos_habilitacion['generar_cod_recuperacion'] == 'S';
                // Sólo se pueden ver las respuestas si se cuenta con el código de recuperación
                if ( $anonima && $gen_cod && (empty($hash['codigo_recuperacion']) || empty($hash['hash_validacion'])) ) {
                    $mje = 'ERROR EN EL ACCESO: Código o hash de recuperación no válidos';
                    $this->throw_error($mje);
                } elseif ( $anonima && !$gen_cod ) {
                    $mje = 'ERROR EN EL ACCESO: No se puede visualizar una encuesta anónima que no genera código de recuperación.';
                    $this->throw_error($mje);
                }
            }

            $this->codigo_externo       = $hash['codigo_externo'];
            $this->form_externo         = $hash['formulario'];
            $this->accion               = $hash['accion'];
            $this->codigo_recuperacion  = $hash['codigo_recuperacion'];
            $this->hash_recuperacion    = $hash['hash_validacion'];

            kolla::logger()->debug('Url Decodificada');
            $this->cargar_formulario_habilitado($this->habilitacion, $this->form_externo);

            // Si no visualizo respuestas entonces continúo con los controles
            if ( $hash['accion'] != 'ver_respuestas' ) {
                $fecha_desde = $this->datos_habilitacion['fecha_desde'];
                $fecha_hasta = $this->datos_habilitacion['fecha_hasta'];
                $hoy = date("Y-m-d");

                if ( $fecha_desde > $hoy || $fecha_hasta < $hoy ) {
                    $this->throw_error('ERROR: El formulario requerido no se encuentra vigente.');
                }
            } else { 
                //si visualizo respuestas me resta verificar el hash de recuperación en caso de anónima
                $hashing = null;
                $id_respondido_formulario = null;
                if ( $anonima ) {
                    $resp_encuestado = $this->get_respondido_encuestado();
                    if ( !empty($resp_encuestado) && ($resp_encuestado['formulario_habilitado'] != $this->formulario_habilitado) )  {
                        //chequear que los datos de validación correspondan a la habilitación y form indicados
                        $this->throw_error('ERROR: No es posible validar los datos informados.');
                    }                    
                    if ( !empty($resp_encuestado) ) {
                        //obtener el hashing del form respondido que corresponde al código de recuperación
                        $id_respondido_formulario = $resp_encuestado['respondido_formulario'];
                        $hashing = anonimato_utils::hashing_de_id_formulario($id_respondido_formulario);
                    }
                    //validar que se corresponda el hash informado con el que se calcula para las respuestas
                    if (is_null($hashing) || ($this->hash_recuperacion != $hashing)) {
                        $this->throw_error('ERROR: La validación del código de recuperación no fue exitosa.');
                    }
                }
            } 
            
            $this->generar_respuesta();
            
        } catch (Exception $e) {
            //capturo todo
            kolla::logger()->error($e->getTraceAsString());
            include_once("nucleo/formulario/vista/error_vista.php");
            $a = new error_vista($e->getMessage());
            $a->generar_interface();
        }
    }

    private function throw_error($mje)
    {
        toba::notificacion()->agregar($mje, 'info');
        throw new toba_error_validacion($mje);
    }

    private function get_datos_habilitacion($id_hab)
    {
        $datos = catalogo::consultar(dao_encuestas::instancia(), 'get_datos_habilitacion', array($id_hab));
        if (count($datos) != 1) {
            $this->throw_error('ERROR EN EL ACCESO, no se encontraron encuestas habilitadas');
        }
        $datos_habilitacion = $datos[0];
        if ($datos_habilitacion['estado_sistema'] != 'A') {
            $this->throw_error('ERROR EN EL ACCESO, el sistema no esta habilitado');
        }
        return $datos_habilitacion;
    }

    private function decodificar_url($password)
    {
        $token = kolla::memoria()->get_parametro(url_encuestas::param_token);
        $hash = url_encuestas::decodificar_token_seguridad($token, $password);
        kolla::logger()->var_dump($hash);
        return $hash;
    }

    private function cargar_formulario_habilitado($habilitacion, $form_externo)
    {
        $es_legacy = $this->es_legacy($habilitacion);
        if ( $es_legacy ) {
            $metodo = 'get_formulario_habilitado_legacy';
        } else {
            $metodo = 'get_formulario_habilitado';
        }
        $res = catalogo::consultar(dao_encuestas::instancia(), $metodo, array($habilitacion, $form_externo));
        if (empty($res)) {
            $mje = 'ERROR EN EL ACCESO, no existe encuesta';
            $this->throw_error($mje);
        }
        $this->formulario_habilitado = $res[0]['formulario_habilitado'];
        $this->titulo_formulario = $res[0]['nombre'];
    }
	
    private function es_legacy ($habilitacion) 
    {
        $hab = quote($habilitacion);

        $sql = "SELECT  habilitacion
        FROM    sge_habilitacion sh
        WHERE   EXISTS (SELECT  formulario_habilitado
                        FROM    sge_formulario_habilitado sfh
                        WHERE   sfh.habilitacion = $hab AND
                                sfh.formulario_habilitado_externo IS NULL)";

        $res = kolla_db::consultar_fila($sql);

        return !empty($res); //si hay resultado es habilitacion legacy
    }

    private function generar_respuesta()
    {
        $resp_encuestado = $this->get_respondido_encuestado();

        if ( empty($resp_encuestado) ) {
            $id_respondido_formulario = null;
        } else {
            $id_respondido_formulario = $resp_encuestado['respondido_formulario'];
        }

        // Para anónimas se recupera el respondido_formulario con el código de recuperación
        $this->crear_configuracion_formulario($id_respondido_formulario);

        if ( $this->accion != 'ver_respuestas' && $this->ya_termino($resp_encuestado) ) {
            $comprobante = $this->new_comprobante($resp_encuestado['respondido_encuestado'], $this->titulo_formulario, $resp_encuestado['fecha']);
            if ( $this->config->get_generar_codigo_recuperacion() ) {
                $comprobante->set_datos_recuperacion($this->get_random_guardado(), null);
            }
            $comprobante->generar_interface();
            return;
        }

        kolla::logger()->debug('Control de respuesta repetida completado');

        include_once('nucleo/formulario/vista/builder_externos.php');
        $vista = new builder_externos();
        $this->config->set_vista_builder($vista);
        $controlador = new formulario_controlador();
        $controlador->set_configuracion($this->config);

        if ( $controlador->procesar_request() ) {
            $resp_encuestado = $this->get_respondido_encuestado(); //recupero la fecha de guardado
            $comprobante = $this->new_comprobante($resp_encuestado['respondido_encuestado'], $this->titulo_formulario, $resp_encuestado['fecha']);

            if ( $this->config->get_generar_codigo_recuperacion() ) {
                $comprobante->set_datos_recuperacion($controlador->get_random_guardado(), $controlador->get_hash());
            }

            $comprobante->generar_interface();
        }
    }

    /**
     * Si la encuesta es anónima recupera el dato de sge_respondido_formulario.
     * La validación del código de recuperación la hice al inicio del request.
     */
    private function  get_respondido_encuestado()
    {
        if ( $this->datos_habilitacion['anonima'] == 'S' &&
            $this->datos_habilitacion['generar_cod_recuperacion'] == 'S' &&
            $this->accion == 'ver_respuestas' ) {
            $codigo_recuperacion = toba::db()->quote($this->codigo_recuperacion);

            $sql = "SELECT  respondido_formulario,
                            formulario_habilitado
                    FROM    sge_respondido_formulario
                    WHERE   codigo_recuperacion = $codigo_recuperacion";
        } else {
            $sistema         = quote($this->datos_habilitacion['sistema']);
            $cod_ext         = quote($this->codigo_externo);
            $form_habilitado = quote($this->formulario_habilitado);

            $sql = "SELECT  respondido_encuestado,
                            respondido_formulario,
                            terminado,
                            ignorado,
                            to_char(fecha, '" . kolla_sql::formato_fecha_hora_visual_sin_segundos . "' ) AS fecha
                    FROM    sge_respondido_encuestado
                    WHERE       sistema = $sistema
                        AND codigo_externo = $cod_ext
                        AND formulario_habilitado = $form_habilitado";
        }
        
        return kolla_db::consultar_fila($sql);
    }

    private function crear_configuracion_formulario($id_respondido_formulario)
    {
        include_once('nucleo/formulario/formulario_controlador_config.php');

        $config = new formulario_controlador_config($this->formulario_habilitado, $id_respondido_formulario);
        $config->set_datos_habilitacion($this->datos_habilitacion);
        if ( $this->accion != 'ver_respuestas' ) {
            // Está configurado el protocolo?
            $protocolo = toba_parametros::get_redefinicion_parametro('kolla', 'protocolo_url_post_form_externo', false);

            // Sino fallback según lo que diga el server
            if ( !$protocolo ) {
                if ( strtolower(substr($_SERVER['SERVER_PROTOCOL'], 0, 5)) == 'https' ) {
                    $protocolo = 'https';
                } else {
                    $protocolo = 'http';
                }
            }

            $url = $protocolo . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; //esta dir.
            $config->set_url_post($url);
        }
        $config->set_codigo_externo($this->codigo_externo);
        $config->set_editable($this->accion != 'ver_respuestas');
        $this->config = $config;
    }

    private function new_comprobante($resp_encuestado, $titulo_form, $fecha)
    {
        include_once('nucleo/formulario/vista/comprobante_formulario.php');
        $comprobante = new comprobante_formulario($resp_encuestado, $titulo_form, $fecha, $this->config->get_url_post(), $this->formulario_habilitado);
        $comprobante->set_modo_externo();
        return $comprobante;
    }

    private function get_random_guardado()
    {
        $sistema         = quote($this->datos_habilitacion['sistema']);
        $cod_ext         = quote($this->codigo_externo);
        $form_habilitado = quote($this->formulario_habilitado);

        $sql = "SELECT  rf.codigo_recuperacion
        FROM    sge_respondido_encuestado re,
                sge_respondido_formulario rf
        WHERE   	re.respondido_formulario = rf.respondido_formulario
                AND re.sistema = $sistema
                AND re.codigo_externo = $cod_ext
                AND re.formulario_habilitado = $form_habilitado";

        $datos = kolla_db::consultar_fila($sql);

        if ( empty($datos) ) {
            return null;
        } else {
            return $datos['codigo_recuperacion'];
        }
    }

    /**
     * @param $resp_encuestado
     * @return bool
     */
    private function ya_termino($resp_encuestado)
    {
        return !empty($resp_encuestado) && ($resp_encuestado['ignorado'] == 'S' ||
                      $resp_encuestado['terminado'] == 'S' ||
                      $this->datos_habilitacion['anonima'] == 'S');
    }

}
?>