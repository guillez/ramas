<?php


class toba_mail_eficiente extends toba_mail
{
    protected $mail = null;
    protected $temporales = null;

    /**
     * Constructor de la clase
     * @param string $hacia  Direccion de email a la cual se enviará
     * @param string $asunto
     * @param string $cuerpo Contenido del email
     * @param string $desde Direccion de email desde la cual se envia (opcionalmente se obtiene desde los parametros)
     */
    function __construct($hacia, $asunto, $cuerpo, $desde=null)
    {
        $this->hacia = $hacia;
        $this->asunto = $asunto;
        $this->cuerpo = $cuerpo;
        $this->desde = $desde;
    }

    /**
     *  Configura para el envío masivo de mails. Se realizan las configuraciones que son constantes
     * para el loop de envíos que realice el cliente de la clase.
     *
     * La idea sería que el cliente de la clase siga los siguientes pasos:
     * - configurar_nuevo_envio_masivo()
     * - loop de envíos mediante: envio_eficiente($address, $body)
     * - limpiar_configuracion_envio_masivo()
     */
    function configurar_nuevo_envio_masivo()
    {
        require_once (dirname(__FILE__).'/../../../../../vendor/phpmailer/phpmailer/src/PHPMailer.php');

        //Se obtiene la configuración del SMTP
        $this->datos_configuracion = $this->get_datos_configuracion_smtp();
        if (! isset($this->desde)) {
            $this->desde = $this->datos_configuracion['from'];
        }

        //Construye y envia el mail
        $this->mail = new PHPMailer\PHPMailer\PHPMailer();
        $this->mail->IsSMTP();
        $this->mail->SMTPKeepAlive = true;
        if ($this->debug) {
            $this->mail->SMTPDebug = true;
        }
        $this->mail->Timeout  = $this->timeout;
        $host = trim($this->datos_configuracion['host']);
        if (isset($this->datos_configuracion['seguridad']) && trim($this->datos_configuracion['seguridad']) != '') {
            if ($this->datos_configuracion['seguridad'] == 'ssl') {
                if (! extension_loaded('openssl')) {
                    throw new toba_error('Para usar un SMTP con encriptación SSL es necesario activar la extensión "openssl" en php.ini');
                }
            }
            $this->mail->set('SMTPSecure', $this->datos_configuracion['seguridad']);
        }

        if (isset($this->datos_configuracion['puerto']) && trim($this->datos_configuracion['puerto']) != '') {
            $this->mail->set('Port', $this->datos_configuracion['puerto']);
        }
        $this->mail->Host = trim($host);
        if (isset($this->datos_configuracion['auth']) && $this->datos_configuracion['auth']) {
            $this->mail->SMTPAuth = true;
            $this->mail->Username = trim($this->datos_configuracion['usuario']);
            $this->mail->Password = trim($this->datos_configuracion['clave']);
        }
        $this->mail->From = $this->desde;
        if (isset($this->datos_configuracion['nombre_from']) && trim($this->datos_configuracion['nombre_from']) != '') {
            $this->desde_nombre = $this->datos_configuracion['nombre_from'];
        }
        if (isset($this->desde_nombre)){
            $this->mail->FromName = $this->desde_nombre;
        } else {
            $this->mail->FromName = $this->desde;
        }

        foreach($this->cc as $copia){
            $this->mail->AddCC($copia);
        }

        foreach($this->bcc as $copia){
            $this->mail->AddBCC($copia);
        }

        if (isset($this->reply_to)){
            $this->mail->AddReplyTo($this->reply_to);
        }

        if (isset($this->confirmacion)){
            $this->mail->ConfirmReadingTo = $this->confirmacion;
        }

        $this->mail->Subject  = $this->asunto;
        $this->mail->IsHTML($this->html);
        $this->temporales = array();
        $dir_temp = toba::proyecto()->get_path_temp();
        foreach (array_keys($this->adjuntos) as $id_adjunto) {
            $archivo = tempnam($dir_temp, 'adjunto');
            file_put_contents($archivo, $this->adjuntos[$id_adjunto]['archivo']);
            $this->temporales[] = $archivo;
            $tipo = $this->mail->_mime_types($this->adjuntos[$id_adjunto]['tipo']);
            $this->mail->AddAttachment($archivo, $this->adjuntos[$id_adjunto]['nombre'], 'base64', $tipo);
        }
    }

    function limpiar_configuracion_envio_masivo()
    {
        $this->mail = null;

        //Elimina los temporales creado para los attachments
        foreach ($this->temporales as $temp) {
            unlink($temp);
        }
    }

    function envio_eficiente($address, $body)
    {
        $this->mail->AddAddress($address);
        $this->mail->Body = $body;
        $exito = $this->mail->Send();
        toba::logger()->debug("Enviado mail con asunto {$this->asunto} a {$this->hacia}");
        $this->mail->ClearAddresses();

        if (!$exito) {
            throw new toba_error("Imposible enviar mail. Mensaje de error: {$this->mail->ErrorInfo}");
        }
    }

}