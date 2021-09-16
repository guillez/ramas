<?php


class ei_formulario_modificado_para_imagen_perfil extends bootstrap_formulario
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
        // Para evitar que quede cacheada la imagen del comprobante
        $random = mt_rand(0, hexdec('7fffffff'));
        $image_html_source = "<img src=\"". \toba_recurso::imagen_proyecto("../bt-assets/img/silueta.png", false) . "&dummy=" . $random . "\"class='img-responsive img-circle operacion_imagen_perfil_usuario' alt='Imagen de perfil'>";

        // Se verifica si se subió una imagen de pefil o utilizo el default
        $datos_usuario   = toba::consulta_php('consultas_usuarios')->get_datos_encuestado_x_usuario_sin_documento(\toba::usuario()->get_id());

        if (isset ($datos_usuario['imagen_perfil_nombre']))
        {
            $usuario = \toba::usuario()->get_id();
            $sql = "SELECT  encode(sge_encuestado.imagen_perfil_bytes,'base64')
                FROM    sge_encuestado
                WHERE   sge_encuestado.usuario = '{$usuario}'";
            $output = kolla_db::consultar_fila($sql);
            $imgData = $output['encode'];
            $image_html_source = "<img src= \"data:image/png;base64," . $imgData . "\" class='img-responsive img-circle operacion_imagen_perfil_usuario' alt='Imagen de perfil'>";
        }

        // Se genera la salida html
        $salida  = "<div>";
        $salida .=      "<div class='col-sm-2'></div>"; // Para que este alineado con el resto de los inputs del form
        $salida .=      "<div class='col-md-5 margen-imagen-perfil'>"; // La imagen que se encuentra en servidor
        $salida .=          $image_html_source;
        $salida .=      "</div>";
        $salida .= "</div>";

        // Solo para que no haya problemas con toba...
        $salida .= $this->get_input_ef($ef);

        return $salida;
    }
}

?>