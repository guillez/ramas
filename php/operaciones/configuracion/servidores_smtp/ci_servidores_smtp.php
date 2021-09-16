<?php
use ext_bootstrap\componentes\bootstrap_ci;
use PHPMailer\PHPMailer\PHPMailer\PHPMailerException;


class ci_servidores_smtp extends bootstrap_ci
{
    /**
     * @var toba_ini
     */
    protected $s__ini_smtp;
    /**
     * @var toba_ini
     */
    protected $s__ini_inst;
    protected $s__seleccion;
    protected $s__datos;
    
    function ini__operacion() 
    {
        $path_ini_smtp        = toba::nucleo()->toba_instalacion_dir().'/smtp.ini';
        $path_ini_instalacion = toba::nucleo()->toba_instalacion_dir().'/instalacion.ini';
		$this->s__ini_smtp = new toba_ini($path_ini_smtp);
        $this->s__ini_inst = new toba_ini($path_ini_instalacion);
    }
    
	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__agregar()
	{
        $this->set_pantalla('pant_edicion');
	}

	function evt__cancelar()
	{
        unset($this->s__datos);
        unset($this->s__seleccion);
        $this->set_pantalla('pant_seleccion');
	}

	function evt__eliminar()
	{
        if ( $this->s__ini_inst->existe_entrada('smtp') && $this->s__ini_inst->get('smtp') == $this->s__seleccion['nombre_conf']) {
            $this->s__ini_inst->eliminar_entrada('smtp');
            $this->s__ini_inst->guardar();
        }
        $this->s__ini_smtp->eliminar_entrada($this->s__seleccion['nombre_conf']);
        $this->s__ini_smtp->guardar();
        $this->evt__cancelar();
	}

	function evt__guardar($volver=true)
	{
        $datos          = $this->s__datos;
        $nombre_conf    = $datos['nombre_conf'];
        $predeterminada = $datos['predeterminada'];
        // Datos que no van en el ini
        unset($datos['nombre_conf']);
        unset($datos['predeterminada']);

        if ( isset($this->s__seleccion) ) {
            $this->s__ini_smtp->set_datos_entrada($this->s__seleccion['nombre_conf'], $datos);
        } else {
            if ( $this->s__ini_smtp->existe_entrada($nombre_conf) ) {
                throw new toba_error('Ya existe una entrada con el nombre de configuración elegido.');
            } else {
                $this->s__seleccion['nombre_conf'] = $nombre_conf;
                $this->s__ini_smtp->agregar_entrada($nombre_conf, $datos);   
            }
        }
        
        // Predetermino la conexión
        $entradas = $this->s__ini_smtp->get_entradas();
        if ( $predeterminada || count($entradas) == 1 ) {
            $this->predeterminar_conexion($nombre_conf);
        }

        $this->s__ini_smtp->guardar();
        $this->s__ini_inst->guardar();
        
        if ( $volver ) {
            $this->evt__cancelar();
        }
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
        $entradas = $this->s__ini_smtp->get_entradas();
        foreach ($entradas as $key => $entrada) {
            if ( $this->s__ini_inst->existe_entrada('smtp') && $key == $this->s__ini_inst->get('smtp') ) {
                $entradas[$key]['predeterminada'] = 1;
            } else {
                $entradas[$key]['predeterminada'] = 0;
            }
            if ( !isset($entrada['seguridad']) || empty($entrada['seguridad']) ) {
                $entradas[$key]['seguridad'] = 'Ninguna';
            } else {
                $entradas[$key]['seguridad'] = strtoupper($entrada['seguridad']);
            }
            $entradas[$key]['nombre_conf'] = $key;
        }
        return $entradas;
	}

	function evt__cuadro__seleccion($seleccion)
	{
        $this->s__seleccion = $seleccion;
        $this->set_pantalla('pant_edicion');
	}

	//-----------------------------------------------------------------------------------
	//---- form -------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form(toba_ei_formulario $form)
	{
        if ( isset($this->s__datos) ) {
            $form->set_datos($this->s__datos);
            unset($this->s__datos);
        } elseif ( isset($this->s__seleccion) ) {
            $form->set_solo_lectura(array('nombre_conf'));
            $datos = $this->s__ini_smtp->get_datos_entrada($this->s__seleccion['nombre_conf']);
            $datos['nombre_conf'] = $this->s__seleccion['nombre_conf'];
            if ( $this->s__ini_inst->existe_entrada('smtp') ) {
                $smtp = $this->s__ini_inst->get('smtp');
                $datos['predeterminada'] = $smtp == $this->s__seleccion['nombre_conf'] ? 1 : 0;
            } else {
                $datos['predeterminada'] = 1;
            }
            $form->set_datos($datos);
        }
	}

	function evt__form__modificacion($datos)
	{
        $this->s__datos = $datos;
	}
    
    function evt__form__test_conexion($datos)
	{
        $this->s__datos = $datos;
        $this->evt__guardar(false);
        $mail = new toba_mail($datos['from'], 'Kolla: prueba de conexión', 'Este es un mail de prueba enviado desde el Módulo de Gestión de Encuestas SIU-Kolla.', $datos['from']);
        $mail->set_configuracion_smtp($datos['nombre_conf']);
        try {
            $mail->enviar();
            toba::notificacion()->info('La prueba de conexión fue satisfactoria.');
        } catch (phpmailerException $e)  {
            toba::notificacion()->agregar($e->getMessage());
            throw new toba_error('La prueba de conexión no tuvo éxito.');
        }
	}

	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__pant_edicion(toba_ei_pantalla $pantalla)
	{
        if ( !isset($this->s__seleccion) ) {
            $pantalla->eliminar_evento('eliminar');
        }
	}
    
    function predeterminar_conexion($nombre_conf)
    {
        if ( $this->s__ini_inst->existe_entrada('smtp') ) {
            $this->s__ini_inst->set_datos_entrada('smtp', $nombre_conf);   
        } else {
            $this->s__ini_inst->agregar_entrada('smtp', $nombre_conf);
        }
    }
    
    function get_seguridad_combo()
    {
        return array(
            array('seguridad' => 'ssl', 'descr' => 'SSL'),
            array('seguridad' => 'tls', 'descr' => 'TLS'),
        );
    }

}
?>