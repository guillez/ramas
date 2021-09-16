<?php

require_once(toba::proyecto()->get_path().'/php/3ros/tcpdf/tcpdf.php');

class comprobante_formulario
{
	//se usa como identificador del comprobante, para poder guardar cosas en sesion
	protected $respondido_formulario;
	protected $titulo_formulario;
	protected $fecha;
	protected $hashing;
	protected $codigo_recuperacion;
	protected $usuario;
	protected $tipo_usuario; //'interno', 'externo', usuarios internos o externos
	protected $url;
	protected $enviar_mail;
	protected $mensaje;
	protected $envio_ok;
	protected $formulario_habilitado;
    protected $plantilla_css;
    protected $path;

	
	const TIPO_ACCION_ENVIAR = 1;
	const TIPO_ACCION_IMPRIMIR = 2;
	
	public function __construct($resp_formulario, $titulo, $fecha, $url, $form_hab=null)
	{
		$this->respondido_formulario = $resp_formulario;
		$this->titulo_formulario = $titulo;
		$this->fecha = $fecha;
		$this->url = $url;
		$this->formulario_habilitado = $form_hab;
	}

	/**
	 * Que parametros se van a necesitar? esto activa el boton (externos no lo va a activar) y le pasa params si los necesita
	 */
	public function agregar_accion_enviar($enviar_mail = true)
	{
		$this->enviar_mail = $enviar_mail;
	}
	
	function agregar_accion_imprimir_respuestas($formulario_habilitado)
	{
		$this->formulario_habilitado = $formulario_habilitado;
	}

	/**
	 * Guarda o levanta de sesion si los valores son nulos. Esto es para que el hash y código duren algunos requests
	 * porque si son anonimas no se pueden regenerar (y no se podria por ej mostrarlos y luego imprimirlos porque son 2 requests)
	 * @param $codigo
	 * @param $hashing
	 */
	public function set_datos_recuperacion($codigo, $hashing)
	{
		if ($codigo != null) {
			$_SESSION[$this->usuario.$this->respondido_formulario.'codigo'] = $codigo;
			$this->codigo_recuperacion = $codigo;
		} else {
			$this->codigo_recuperacion = isset($_SESSION[$this->usuario.$this->respondido_formulario.'codigo']) ? $_SESSION[$this->usuario.$this->respondido_formulario.'codigo'] : null;
		}

		if ($hashing != null) {
			$_SESSION[$this->usuario.$this->respondido_formulario.'hash'] = $hashing;
			$this->hashing = $hashing;
		} else {
			$this->hashing = isset($_SESSION[$this->usuario.$this->respondido_formulario.'hash']) ? $_SESSION[$this->usuario.$this->respondido_formulario.'hash'] : null;
		}

		if ($this->hashing == null) {
			$this->hashing  = '- El código verificador ya fue generado -';
		}
	}
    
    public function set_plantilla_css($archivo)
    {
        $this->plantilla_css = $archivo;
    }
	
	public function set_modo_interno($usuario)
	{
		$this->tipo_usuario = 'interno';
		$this->usuario = $usuario;
	}
	
	public function set_modo_interno_guest()
	{
		$this->tipo_usuario = 'guest';
	}
	
	public function set_modo_externo()
	{
		$this->tipo_usuario = 'externo';
	}
	
    public function set_path($path)
	{
		$this->path = $path;
	}
    
	public function generar_interface ()
	{
		// Guardar y/o terminar si es necesario.
		if (isset($_POST['comprobante_imprimir'])) {
			$this->accion_imprimir();
		} else if (isset($_POST['comprobante_enviar']) ) {
			$this->accion_enviar();
		} else if (isset($_POST['respuestas_imprimir']) ) {
			$this->accion_imprimir_respuestas();
		} else {
			$this->accion_mostrar_ticket(); //default
		}
	}

	private function accion_enviar()
	{
	  	$this->accion(self::TIPO_ACCION_ENVIAR);
	}
	
	private function accion_imprimir()
	{
		$this->accion(self::TIPO_ACCION_IMPRIMIR);
	}

	private function accion_imprimir_respuestas()
	{
		include_once('nucleo/formulario/formulario_controlador_config.php');
		include_once('nucleo/formulario/vista/builder_pdf.php');
		
		$builder = new builder_pdf();
        $builder->set_completar_impreso(false);
		$config  = new formulario_controlador_config($this->formulario_habilitado, $this->respondido_formulario);
		$formulario_controlador = new formulario_controlador();
		
		$config->set_vista_builder($builder);
		$formulario_controlador->set_configuracion($config);
		$formulario_controlador->procesar_request();
	}
	
	private function set_notificacion($mensaje, $envio_ok)
	{
		$this->mensaje = $mensaje;
		$this->envio_ok = $envio_ok;
	}
	
	private function accion_mostrar_ticket()
	{
		if ($this->tipo_usuario == 'externo') {
			$this->mostrar_ticket_externos();
		} else if ($this->tipo_usuario == 'interno') {
			$this->ticket_internos();
		} else if ($this->tipo_usuario == 'guest') {
			$this->ticket_internos_guest();
		} else {
			throw  new ErrorException("El tipo de comprobante - {$this->tipo_usuario} - no existe");
		}
	}
	
    private function accion($tipo_accion)
    {
        //Creación pdf y seteos
        $pdf = new TCPDF('Portrait', PDF_UNIT, PDF_PAGE_FORMAT, false, 'ISO-8859-1');
        $pdf->AddPage();
        $pdf->SetFontSize(10);
        $pdf->SetFont('helvetica');
        $pdf->SetMargins(10, 15, 10);
        $pdf->SetAutoPageBreak(true, 20);

        //Creación html comprobante
        $datos = toba::consulta_php('consultas_usuarios')->get_encuestado_por_usuario($this->usuario);
        $html = $this->get_html_comprobante($datos, $this->fecha);
        
        //Escribo el html en el pdf
        $pdf->writeHTML($html, true, false, true, false, 'C');
		
        //Dependiendo del tipo de accion a realizar lo descarga o lo envia por mail
        if ($tipo_accion == self::TIPO_ACCION_IMPRIMIR) {
			
            //Envía al browser y fuerza la descarga del archivo
            $pdf->Output('Comprobante.pdf', 'D');
        } elseif ($tipo_accion == self::TIPO_ACCION_ENVIAR) {
			
            //Guardo el archivo en una carpeta temporal
            $ubicacion = toba::proyecto()->get_path_temp().'/Comprobante';
            $pdf->Output($ubicacion, 'F');

            //Datos para el mail
            $direccion = $datos['email'];
            $asunto = 'Comprobante de encuesta respondida';
            $cuerpo = 'Este es un mail enviado desde el Módulo de Gestión de Encuestas SIU-Kolla. En un archivo adjunto se encuentra el Comprobante de encuesta respondida.';

            //Creo el mail y agrego adjunto
            $mail = new toba_mail($direccion, $asunto, $cuerpo);
            $mail->agregar_adjunto('Comprobante', $ubicacion, 'base64', 'pdf');
			
            try {
                $mail->enviar();
                $this->set_notificacion('El mail se envió correctamente.', true);
            } catch (toba_error $e)  {
                $this->set_notificacion('El mail no se pudo enviar.', false);
            }
	        
            unlink($ubicacion);
            $this->accion_mostrar_ticket();
        }
    }
	
    private function mostrar_ticket_externos()
    {
        include('header.php');
        
        $datos_form_habilitado = toba::consulta_php('consultas_formularios')->get_formulario_habilitado($this->formulario_habilitado);
        $datos_habilitacion    = toba::consulta_php('consultas_habilitaciones')->get_datos_habilitacion($datos_form_habilitado['habilitacion']);
        $mje = null;
        
        if ($datos_habilitacion[0]['descarga_pdf'] == 'S') {
            if (isset($this->codigo_recuperacion)) {
                $mje  = 'Gracias por completar la encuesta. ';
                $mje .= 'Por favor descargá y guardá el comprobante generado. Los códigos allí incluídos se generaron por única vez y serán requeridos si solicitas consultar las respuestas.';
            } else {
                $mje = "La encuesta '{$this->titulo_formulario}' ya ha sido respondida.";
            }
        } else {
            $mje  = 'Tus respuestas se registraron correctamente.';
        }
        
        ?>
        <div class='container'>
            <div class='row-fluid'>
                <div class='span12'>
                    <?php if (isset($mje)) { ?>
                    <div class="alert alert-success" style="margin-top: 20px;">
                        <p style="text-align: center;"><?php echo $mje; ?></p>
                    </div>
                    <?php
                        }
                        if ($datos_habilitacion[0]['descarga_pdf'] == 'S') {
                            if (isset($this->codigo_recuperacion)) { ?>
                                <div class="well well-small" >
                                    <fieldset>
                                        <legend style="text-align: center;" class="small muted">Comprobante de encuesta respondida</legend>
                                        <p><b>Encuesta:</b> <?php echo $this->titulo_formulario; ?></p>
                                        <p><b>Fecha:</b> <?php echo $this->fecha; ?></p>
                                        <p><b>Código de recuperación:</b> <?php echo $this->codigo_recuperacion; ?></p>
                                        <p><b>Código de verificación:</b> <?php echo $this->hashing; ?></p>
                                    </fieldset>
                                </div>
                        <?php
                            }
                            $this->generar_botones();
                        }
                        else {
                            //La no descarga de pdf en ticket de usuario externos se interpreta por el momento como
                            //relevamiento de becas y significa que tampoco se muestran los datos de la encuesta, fecha
                            //ni codigos para recuperacion
                        }
                    ?>
                </div>
            </div>
        </div>
        <?php
            $scripts = acceso_externo::obtener_script_encuesta_terminada($this->respondido_formulario);
            $scripts .= acceso_externo::obtener_script_encuesta_cargada();
            echo "</body>$scripts </html>";
    }
	
    public function set_notificacion_mail_gestor($mensaje, $envio_ok)
	{
		$this->set_notificacion($mensaje, $envio_ok);
	}
    
    //---- ticket -----------------------------------------------------------------------

    private function ticket_internos()
    {
        $datos = toba::consulta_php('consultas_usuarios')->get_encuestado_por_usuario($this->usuario);
        $datos_form_habilitado   = toba::consulta_php('consultas_formularios')->get_formulario_habilitado($this->formulario_habilitado);
        $datos_habilitacion      = toba::consulta_php('consultas_habilitaciones')->get_datos_habilitacion($datos_form_habilitado['habilitacion']);
        $genera_cod_recuperacion = $datos_habilitacion[0]['generar_cod_recuperacion'];

        if (!empty($datos)) {
            $encuestado = $datos['apellidos'].', '.$datos['nombres'];
            $tipo_doc = $datos['tipo_doc'];
            $nro_doc = $datos['documento_numero'];
        } else {
            $encuestado = '';
            $tipo_doc   = '';
            $nro_doc    = '';
        }
        
		if (($genera_cod_recuperacion == 'S') && isset($this->codigo_recuperacion)) {
            $alto_img = 6;
            if ($datos_habilitacion[0]['descarga_pdf'] == 'S') {
                $mje_descarga = 'Gracias por completar la encuesta. Por favor descargá '
                        . 'y guardá el comprobante generado. Los códigos allí '
                        . 'incluídos se generaron por única vez y serán requeridos '
                        . 'si solicitas consultar las respuestas.';
            }
        } else {
            $alto_img = 4;
            if ($datos_habilitacion[0]['descarga_pdf'] == 'S') {
                $mje_descarga = 'Gracias por completar la encuesta. Puede descargar el comprobante generado para conservarlo.';
            }
        }
        
        $mje_envio_mail = '';
        include('nucleo/formulario/vista/header.php');
        
        if (isset($this->mensaje)) {
            $class = $this->envio_ok ? 'alert alert-success' : 'alert alert-error';
            $mje_envio_mail = "	<div class='$class'>
                                    <button type='button' class='close' data-dismiss='alert'>&times;</button>
                                    {$this->mensaje}
                                </div>";
        }
		
        $style = $datos_habilitacion[0]['descarga_pdf'] == 'N' ? "style='border-bottom: 0px'" : '';

        $image_file = '';
        if (file_exists(toba::proyecto()->get_path() . '/www/img/custom_pdf_image.png')) {
            $image_file = 'custom_pdf_image.png';
        } else {
            $image_file = 'logo_univ.jpg';
        }
                
        echo "
          <div class='container-fluid'>
          
                <div class='panel'>
                
                    <div class='col-md-12'>
                    
                        $mje_envio_mail
                        
                        <div class='page-header' $style>

                            <h3 style='text-align: center'>Comprobante de encuesta respondida</h3>

                        </div>";
        
        if ($datos_habilitacion[0]['descarga_pdf'] == 'S') {
            echo "      <div class='alert alert-success' style='margin-top: 20px;'>
                            <p style='text-align: center;'>{$mje_descarga}
                        </div>";
        }

        // Para evitar que quede cacheada la imagen del comprobante
        $random = mt_rand(0, hexdec('7fffffff'));
        
        echo "     <div class='table-responsive'>     
                        <table class='table  table-bordered table-striped'>
                        
                            <thead>
                                <tr>
                                    <th colspan='3'>{$this->titulo_formulario}</th>
                                </tr>
                            </thead>
                            
                            <tr>
                                <td>Apellido y nombres</td>
                                <td>$encuestado</td>
                                <td rowspan='$alto_img' style='vertical-align: middle;'>
                                    <img src=" . toba_recurso::imagen_proyecto($image_file, false) . "&dummy=" . $random . " alt='logo institucional' class='img-responsive'>
                                </td>
                            </tr>

                            <tr>
                                <td>Tipo de documento</td>
                                <td>$tipo_doc</td>
                            </tr>

                            <tr>
                                <td>Número de documento</td>
                                <td>$nro_doc</td>
                            </tr>
                            
                            <tr>
                                <td>Fecha</td>
                                <td>{$this->fecha}</td>
                            </tr>
                            ";
                            
                            if (($genera_cod_recuperacion == 'S') && isset($this->codigo_recuperacion)) {
                                echo "
                                    <tr>
                                        <td>Código de recuperación</td>
                                        <td colspan='1'>{$this->codigo_recuperacion}</td>
                                    </tr>

                                    <tr>
                                        <td>Código de verificación</td>
                                        <td colspan='1'>{$this->hashing}</td>
                                    </tr>";
                            }

                echo  '	    </table> 
	                    </div>';
                
                if ($datos_habilitacion[0]['descarga_pdf'] == 'S') {
                    $this->generar_botones();
                }
                
	        	echo '	
                    </div>
				
                </div>
          
            </div>
        ';
		
        echo "<script type='text/javascript' src='//code.jquery.com/jquery-1.9.1.min.js'></script>
              <script type='text/javascript' src='js/bootstrap.min.js'></script>";						
        echo '</body>';
    }

    private function ticket_internos_guest()
    {
        include('nucleo/formulario/vista/header.php');
		
        $datos_form_habilitado   = toba::consulta_php('consultas_formularios')->get_formulario_habilitado($this->formulario_habilitado);
        $datos_habilitacion      = toba::consulta_php('consultas_habilitaciones')->get_datos_habilitacion($datos_form_habilitado['habilitacion']);
        $genera_cod_recuperacion = $datos_habilitacion[0]['generar_cod_recuperacion'];
                            
        if (($genera_cod_recuperacion == 'S') && isset($this->codigo_recuperacion)) {
            $alto_img = 3;
        } else {
            $alto_img = 1;
        }

        $image_file = '';
        if (file_exists(toba::proyecto()->get_path() . '/www/img/custom_pdf_image.png')) {
            $image_file = 'custom_pdf_image.png';
        } else {
            $image_file = 'logo_univ.jpg';
        }

        // Para evitar que quede cacheada la imagen del comprobante
        $random = mt_rand(0, hexdec('7fffffff'));
        
        echo "
            <br></br>
        
            <div class='container'>
          
                <div class='row-fluid'>
                
                    <div class='span12'>
                    
                        <div class='alert alert-success' style='margin-top: 20px;'>
                            <p style='text-align: center;'>Gracias por contestar la encuesta. Por favor descargá y guardá el comprobante generado.</p>
                        </div>
                        
                        <table class='table table-bordered table-striped'>
                        
                            <thead>
                                <tr>
                                    <th colspan='3'>{$this->titulo_formulario}</th>
                                </tr>
                            </thead>
                            
                            <tr>
                                <td>Fecha</td>
                                <td>{$this->fecha}</td>
                                <td rowspan='$alto_img' width='40%' style='vertical-align: middle;'>
                                    <img src=" . toba_recurso::imagen_proyecto($image_file, false) . "&dummy=" . $random . " alt='logo institucional' class='img-responsive'>
                                </td>
                            </tr>";
                            
                            if (($genera_cod_recuperacion == 'S') && isset($this->codigo_recuperacion)) {
                                echo "
                                    <tr>
                                        <td>Código de recuperación</td>
                                        <td colspan='1'>{$this->codigo_recuperacion}</td>
                                    </tr>

                                    <tr>
                                        <td>Código de verificación</td>
                                        <td colspan='1'>{$this->hashing}</td>
                                    </tr>";
                            }

                echo  '	</table>';

                if ($datos_habilitacion[0]['descarga_pdf'] == 'S') {
                    $this->generar_botones();
                }
                
                echo '
                    </div>
                    
                </div>
                
            </div>
            
        </body>
        ';
    }

	private function generar_botones()
	{
        if ($this->tipo_usuario == 'interno') {
            $this->url = toba::vinculador()->get_url();
        }
    ?>
	<p>
		<form action="<?php echo $this->url; ?>" method="post"> 
			<button class='btn btn-primary' value='comprobante_imprimir' name='comprobante_imprimir' type='submit'><i class="icon-print"></i> Comprobante</button>
			<?php
			toba_manejador_sesiones::enviar_csrf_hidden();
			if (isset($this->formulario_habilitado)) {
				$datos_form_habilitado   = toba::consulta_php('consultas_formularios')->get_formulario_habilitado($this->formulario_habilitado);
				$es_habilitacion_anonima = toba::consulta_php('consultas_habilitaciones')->es_habilitacion_anonima($datos_form_habilitado['habilitacion']);
				
				if ($es_habilitacion_anonima == 'N') { ?>
					<button class='btn btn-primary' value='respuestas_imprimir' name='respuestas_imprimir' type='submit'><i class="icon-print"></i> Respuestas</button>
				<?php }
			}
			
			//Para los usuarios de tipo administrador o encuestado se permite enviar el comprobante por mail
			if ($this->enviar_mail && ($this->tipo_usuario == 'interno')) { ?>
				<button class='btn btn-primary' value='comprobante_enviar' name='comprobante_enviar' type='submit'><i class="icon-envelope"></i> Enviar</button>
			<?php } ?>
		</form>
	</p>
    <?php
    }
	
    //---- mail -------------------------------------------------------------------------
    
    public function crear_comprobante_adjunto($datos_adjunto)
    {
        //Creación pdf y seteos
        $pdf = new TCPDF('Portrait', PDF_UNIT, PDF_PAGE_FORMAT, false, 'ISO-8859-1');
        $pdf->AddPage();
        $pdf->SetFontSize(10);
        $pdf->SetFont('helvetica');
        $pdf->SetMargins(10, 15, 10);
        $pdf->SetAutoPageBreak(true, 20);

        //Creación html comprobante
        $html = $this->get_html_comprobante($datos_adjunto, $datos_adjunto['fecha']);
        
        //Escribo el html en el pdf y guardo la salida en un archivo
		$pdf->writeHTML($html, true, false, true, false, 'C');
        $pdf->Output($this->path, 'F');
    }
    
    function get_html_comprobante($datos, $fecha)
    {
        //Datos para el html
        $title = 'Comprobante de encuesta respondida';
        $image_file = '';
        if (file_exists(toba::proyecto()->get_path() . '/www/img/custom_pdf_image.png')) {
            $image_file = '/www/img/custom_pdf_image.png';
        } else {
            $image_file = '/www/img/logo_univ.jpg';
        }
        $logo = '<img src="file:///'.toba::proyecto()->get_path() . $image_file . '" style="margin:auto;"/>';
        $rowspan = 4;
        
        if (!empty($datos) && isset($datos['apellidos'])) {
            $encuestado = $datos['apellidos'].', '.$datos['nombres'];
            $tipo_doc = $datos['tipo_doc'];
            $nro_doc = $datos['documento_numero'];
        } else {
            $encuestado = '';
            $tipo_doc   = '';
            $nro_doc    = '';
        }
		
        if (isset($this->codigo_recuperacion)) {
            $rowspan = $rowspan + 2;
        }
		
        //Html a configurar
        $html = '
			<p style="font-size:17pt;"><b>'.$title.'</b></p>
			
            <table border="1" cellpadding="8" align="left">
                
                <thead>
                    <tr>
                        <th colspan="3" style="font-size:11pt;"><b>'.$this->titulo_formulario.'</b></th>
                    </tr>
                </thead>
            ';
		
        if ($this->tipo_usuario == 'interno') {
            $td_logo = '<td rowspan="'.$rowspan.'">'.$logo.'</td>';
            $html .= '
                    <tr>
                        <td>Apellido y nombres</td>
                        <td>'.$encuestado.'</td>'.
                        $td_logo.'
                    </tr>
					
                    <tr>
                        <td>Tipo de documento</td>
                        <td>'.$tipo_doc.'</td>
                    </tr>
	                
                    <tr>
                        <td>Número de documento</td>
                        <td>'.$nro_doc.'</td>
                    </tr>
					
                    <tr>
                        <td>Fecha</td>
                        <td>'.$fecha.'</td>
                    </tr>
                ';
        } else {
            $rowspan -= 3;
            $td_logo = '<td rowspan="'.$rowspan.'">'.$logo.'</td>';
            $html .= '
                    <tr>
                        <td>Fecha</td>
                        <td>'.$fecha.'</td>
                        '.$td_logo.'
                    </tr>';
        }
		
        if (isset($this->codigo_recuperacion)) {
            $html .= '
                    <tr>
                        <td>Código de recuperación</td>
                        <td>'.$this->codigo_recuperacion.'</td>
                    </tr>
				
                    <tr>
                        <td>Código de verificación</td>
                        <td> '.$this->hashing.'</td>
                    </tr>
                ';
        }
		
        return $html.'</table>';
    }
    
}
?>
