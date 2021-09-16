<?php


class form_resultados extends bootstrap_formulario
{
    function extender_objeto_js()
    {
        $id_texto_fijo = "cont_ef_" . $this->get_id_form() . 'resultados';

        echo "	            
            //Fix visual a la ayuda
            $('#{$id_texto_fijo}').removeClass(\"col-md-5\").addClass(\"col-md-12\").css(\"margin-top\", \"7px\");
		";
    }

}