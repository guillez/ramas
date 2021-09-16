<?php

class bootstrap_cuadro_habilitaciones extends bootstrap_cuadro
{
    function instanciar_manejador_tipo_salida($tipo)
    {
        //Si existe seteo explicito de parte del usuario para el tipo de salida
        if (isset($this->_manejador_tipo_salida[$tipo])) {
            $clase =  $this->_manejador_tipo_salida[$tipo];
        } else {
            //Verifico que sea uno de los tipos estandar o disparo excepcion
            switch($tipo) {
                case 'html':
                    $clase = 'ext_bootstrap\\componentes\\interfaz\\bootstrap_ei_cuadro_salida_' . $this->_tipo_salida . '_habilitaciones';
                    break;
                case 'impresion_html':
                case 'pdf':
                case 'excel':
                case 'xml':
                    $clase = 'ext_bootstrap\\componentes\\interfaz\\bootstrap_ei_cuadro_salida_' . $this->_tipo_salida;
                    break;
                default:
                    throw new \toba_error_def('El tipo de salida solicitado carece de una clase que lo soporte');
            }
        }
        if (isset($clase)) {
            $this->_salida = new $clase($this);
        }
    }

}