<?php

require_once(toba::proyecto()->get_path().'/php/nucleo/formulario/vista/respuesta_diferida.php');
require_once(toba::proyecto()->get_path().'/php/3ros/tcpdf/tcpdf.php');
include_once('nucleo/formulario/vista_builder.php');

/**
 * Esta vista muestra los formularios en pdf
 */

class builder_pdf implements vista_builder
{
    protected $pdf;
    protected $html_pdf;
    protected $diferido;
    protected $inicio_cadena_invisible = '[';
    protected $fin_cadena_invisible    = ']';
    protected $es_adjunto = false;
    protected $path;
    protected $cantidad_maxima_opciones_respuesta_impresas;
    protected $completar_impreso = true;


    public function crear_encabezado_formulario($nombre_form, $texto_preliminar=null, $url_action_post=null, $puede_guardar=null)
    {
        //Se crean los objetos correspondiente al pdf y a las respuestas diferidas en la encuesta
        $this->pdf = new custom_tcpdf('Portrait', PDF_UNIT, PDF_PAGE_FORMAT, false, 'ISO-8859-1');
        $this->diferido = new respuesta_diferida();

        //Se setean las propiedades del pdf
        $this->pdf->AddPage();
        $this->pdf->SetFontSize(10);
        $this->pdf->setFooterFont(array('helvetica', '', 8));

        $this->pdf->SetFont('helvetica');
        $this->pdf->SetMargins(10, 15, 10);
        $this->pdf->setHeaderMargin(10);
        $this->pdf->SetFooterMargin(10);
        $this->pdf->SetAutoPageBreak(true, 35);

        //Path de las imagenes para los checkboxes y radio buttons
        $img_checkbox = '<img src="file:///'.toba::proyecto()->get_path().'/www/img/check_off.gif" height="11"/>';
        $img_radio 	  = '<img src="file:///'.toba::proyecto()->get_path().'/www/img/radio_off.gif" height="11"/>';

        //Esto es para que no se pegue con la imagen del header
        $this->html_pdf = '<br><br><br><br><br>';

        $this->html_pdf .= '<table cellpadding="2">';

        if ($texto_preliminar) {
            $this->html_pdf .= '<tr>
									<td align="left">'.$texto_preliminar.'</td>
								</tr>';
        }

        if ($this->mostrar_texto($nombre_form)) {
            $this->html_pdf .= '
				<tr>
					<th align="center" style="font-size:15pt; background-color:white; color:#555555;"><b>'.$nombre_form.'</b></th>
				</tr>';
        }

        $this->html_pdf .= '
				<tr>
					<td align="left">
						<ul style="font-size:8pt;">
							<li>Ante la presencia de una respuesta con los símbolos '.$img_radio.'seleccione una y solo una opción.</li>
							<li>Ante la presencia de una respuesta con los símbolos '.$img_checkbox.'podrá seleccionar más de una opción.</li>
						</ul>
					</td>
				</tr>
			</table>
			<br/>
			<br/>';

        $this->setear_limite_opciones_impresas();
    }

    public function crear_cierre_formulario()
    {
        $this->pdf->writeHTML($this->html_pdf, true, false, false, false, 'C');
        $this->pdf->Ln(6);
        $this->pdf->writeHTML($this->diferido->get_listado_respuestas(), 0, false, true);

        /*
         * En caso de que la instancia builder haya sido creada con el objeto de
         * ser un archivo adjunto a un mail, entonces la salida se guarda dentro
         * de la carpeta temporal del proyecto, para luego ser agregada al mismo.
         */

        if ($this->es_adjunto) {
            $this->pdf->Output($this->path, 'F');
        } else {
            $this->pdf->Output('Definicion de Encuesta.pdf', 'D');
        }
    }

    public function crear_encabezado_bloque($id, $nombre)
    {
        $this->html_pdf .= '<table cellpadding="6" align="left">';

        if ($this->mostrar_texto($nombre)) {
            $this->html_pdf .= '<tr>
									<td align="left" style="background-color:#EEEEEE; border-left: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;"><b>'.$nombre.'</b></td>
								</tr>';
        } else { // Si es bloque sin titulo muestro lo mismo pero sin texto.
            $this->html_pdf .= '<tr>
									<td align="left" style="border-bottom: 1px solid #EEEEEE;"></td>
								</tr>';
        }
    }

    public function crear_cierre_bloque()
    {
        $this->html_pdf .= '</table>
                            <br/>
                            <br/>';
    }

    public function crear_encabezado_pregunta($id,$for_id, $obligatoria, $texto,$ayuda, $error)
    {
        $this->html_pdf .= '<tr nobr="true">
                                <td style="border-top: 1px solid #EEEEEE; border-bottom: 1px solid #EEEEEE; border-left: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;">'.$texto .'</td> 
                            </tr>';
    }

    public function crear_cierre_pregunta()
    {
    }

    public function crear_componente_label($id, $texto)
    {
        $this->html_pdf .= '<tr>
								<td style="border-left: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;" colspan="3"><b><i>'.$texto.'</i></b></td>
							</tr>';
    }

    public function crear_componente_subtitulo($id, $texto)
    {
        $this->html_pdf .= '<tr>
							    <td height="40px" width="100%" style="text-align: center; border-left: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;" >
							        <br/>
							        <h3>'.$texto.'</h3>
							    </td>
							</tr>';
    }

    public function crear_componente_titulo($id, $texto)
    {
        $this->html_pdf .= '<tr>
							    <td height="30px" width="100%" style="text-align: center; border-left: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;">
							        <br/>
							        <h1>'.$texto.'</h1>
							    </td>
							</tr>';
    }

    public function crear_componente_texto_enriquecido($id, $texto)
    {
        $this->html_pdf .= '<tr>
							    <td width="100%" style="border-bottom: 1px solid #EEEEEE; border-left: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;" >'.$texto.'</td>
							</tr>';
    }

    public function setear_limite_opciones_impresas()
    {
        $sql = "SELECT  valor 
                FROM    kolla.sge_parametro_configuracion 
                WHERE   parametro = 'limite_opciones_respuesta_impresas'
                AND     seccion = 'RESPUESTAS'; ";
        
        $res = kolla::db()->consultar_fila($sql);
        $this->cantidad_maxima_opciones_respuesta_impresas = $res['valor'];
    }

    public function crear_respuesta(kolla_comp_encuesta $componente, $componenteid, $opciones_respuesta, $obligatoria, $id_pregunta, $imprimir_respuestas_completas)
    {
        $this->html_pdf .= '<tr>';
        
        if (empty($opciones_respuesta)) {
            $this->html_pdf .= '<td style="border-bottom: 1px solid #EEEEEE; border-rigth: 1px solid #EEEEEE; border-left: 1px solid #EEEEEE;"></td>';
        } else {
            $valor_diferido = null;
            //diferir o eliminar respuestas solo corresponde si no es una pregunta libre
            if (!toba::consulta_php('consultas_encuestas')->es_pregunta_libre($id_pregunta)) {
                //si califica para diferir, preparar las respuestas diferidas y el contador
                //forzar no mostrar respuestas completas para que se muestre lo preparado al final
                if ($this->diferido->diferir_respuesta($opciones_respuesta)) {
                    $this->diferido->escribir_diferido($id_pregunta, $opciones_respuesta);
                    $valor_diferido = $this->diferido->get_contador_diferidas();
                    $imprimir_respuestas_completas = false;
                }
                //si hay que eliminarlas por cantidad
                if (count($opciones_respuesta) > $this->cantidad_maxima_opciones_respuesta_impresas) {
                    //entonces forzar no mostrar respuestas completas y mostrar contador de mensaje (0)
                    $valor_diferido = 0;
                    $imprimir_respuestas_completas = false;
                    //activar mensaje de respuestas eliminadas
                    $this->diferido->mostrar_mensaje_eliminadas("Las opciones de respuesta de esta pregunta superan el límite establecido de ".$this->cantidad_maxima_opciones_respuesta_impresas);
                }
            }
            //luego pedir el pdf al componente
            $componente->set_valor_diferido($valor_diferido);
            $this->html_pdf .= $componente->get_pdf($opciones_respuesta, $imprimir_respuestas_completas, $valor_diferido, $this->completar_impreso);
        }
        
        $this->html_pdf .= '</tr>';
    }

    public function crear_encabezado_encuesta($id, $nombre, $bloques, $texto_preliminar=null)
    {
        if (!$texto_preliminar && !$this->mostrar_texto($nombre)) {
            return;
        }

        $this->html_pdf .= '<table cellpadding="2" width="100%">';

        if ($texto_preliminar) {
            $this->html_pdf .= '<tr>
									<td align="left">'.$texto_preliminar.'</td>
								</tr>
								<br/>';
        }

        if ($this->mostrar_texto($nombre)) {
            $this->html_pdf .= '<tr>
									<td style="background-color:gray; color:white;"><b>'.$nombre.'</b></td>
								</tr>';
        }

        $this->html_pdf .= '</table>
                            <br/> <br/>';
    }

    public function crear_cierre_encuesta()
    {
    }

    public function set_editable($boolean)
    {
    }

    public function set_paginacion($paginar, $lista_pags)
    {
    }

    public function crear_elemento($elemento_desc, $elemento_img=null)
    {
        if ($this->mostrar_texto($elemento_desc)) {
            $this->html_pdf .= '<table border="1" cellpadding="2" align="center" width="100%">
									<tr>
										<td style="background-color:#CBCBCB; color:#333333;"><b>'.trim($elemento_desc).'</b></td>
									</tr>
								</table>';
        }
    }

    public function crear_error_label($str)
    {
    }

    public function crear_mensaje($mensaje)
    {
    }

    public function mostrar_texto($texto)
    {
        $texto = trim($texto);
        $primer_caracter = substr($texto, 0, 1);
        $ultimo_caracter = substr($texto, -1);

        return !($primer_caracter == $this->inicio_cadena_invisible && $ultimo_caracter == $this->fin_cadena_invisible) && $texto;
    }

    public function set_es_adjunto($boolean)
    {
        $this->es_adjunto = $boolean;
    }

    public function set_path($path)
    {
        $this->path = $path;
    }

    public function crear_barra_progreso()
    {
        // La barra de progreso no tiene sentido en el pdf
    }

    /**
     * Se setea el atributo correspondiente a completar la encuesta de manera impresa.
     * Si no se completa de manera impresa se optimizan ciertos campos para que ocupen
     * menos espacio en la hoja (por ejemplo el texto libre de área).
     * @param $value valor booleano correspondiente a si se completa o no de manera impresa
     */
    public function set_completar_impreso($value)
    {
        $this->completar_impreso = $value;
    }
    
}
?>