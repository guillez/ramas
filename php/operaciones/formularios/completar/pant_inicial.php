<?php
class pant_inicial extends toba_ei_pantalla
{
    /*function generar_layout()
    {
        require_once toba::proyecto()->get_path_php().'/nucleo/formulario/accesos/int_completar.php';
        $acceso = new int_completar('38000899', false);
        $acceso->procesar();
    }*/
    
    function extender_objeto_js()
    {
        /*
         * Se deben sacar todo el javascript correspondiente a la encuesta, que
         * momentaneamente se saca en el lugar equivocado y por ende no funciona
         */
    }
}
?>