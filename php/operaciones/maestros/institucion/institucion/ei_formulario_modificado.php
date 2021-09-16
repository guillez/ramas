<?php


class ei_formulario_modificado extends bootstrap_formulario
{
    protected function generar_layout()
    {
        if (!isset($this->_info_formulario['template']) || trim($this->_info_formulario['template']) == '') {
            foreach ($this->_lista_ef_post as $ef) {
                if ($ef == 'dummy')
                {
                    $salida = $this->generar_html_ef_aviso($ef);
                    echo $salida;
                } else
                {
                    $this->generar_html_ef($ef);
                }
            }
        } else {
            $this->generar_layout_template();

        }
    }

    protected function generar_html_ef_aviso($ef)
    {
        $salida = '';

        $clase = $this->get_estilo_ef().' col-md-12';
        $id_ef = $this->_elemento_formulario[$ef]->get_id_form();

        // Para evitar que quede cacheada la imagen del comprobante
        $random = mt_rand(0, hexdec('7fffffff'));

        // Se verifica si se subió un logo personalizado o utilizo el default
        $image_file = toba::proyecto()->get_path() . '/www/img/custom_pdf_image.png';
        $image_html_source = \toba_recurso::imagen_proyecto("custom_pdf_image.png", false) . "&dummy=" . $random;
        if (!file_exists($image_file)) {
            $image_file = toba::proyecto()->get_path() . '/www/img/logo_univ.jpg';
            $image_html_source = \toba_recurso::imagen_proyecto("logo_univ.jpg", false) . "&dummy=" . $random;
        }

        // Se genera la salida html
        $salida .= "<div id='cont_$id_ef' class='$clase'>";
        $salida .=      "<div class='col-sm-2'></div>"; // Para que este alineado con el resto de los inputs del form
        $salida .=      "<div class='alert alert-info col-md-5' role='alert'>"; // Aviso informativo
        $salida .=          "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
        $salida .=          "<strong>Importante:</strong> verificar que la imagen seleccionada se visualiza correctamente en la generación del comprobante y en los encabezados de las encuestas descargadas en pdf.";
        $salida .=      "</div>";
        $salida .= "</div>";
        $salida .= "<div>";
        $salida .=      "<div class='col-sm-2'></div>"; // Para que este alineado con el resto de los inputs del form
        $salida .=      "<div class='col-md-5'>"; // La imagen que se encuentra en servidor
        $salida .=          "<img src=".$image_html_source." class='img-responsive' alt='Logo de la institución'>";
        $salida .=      "</div>";
        $salida .= "</div>";

        // Solo para que no haya problemas con toba...
        $salida .= $this->get_input_ef($ef);

        return $salida;
    }
}