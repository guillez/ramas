<?php
/**
 * @todo cambiar por namespace de alguna manera
 */
require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_formulario.php';

class form_dependencia extends bootstrap_formulario
{
    
    function extender_objeto_js()
	{
        echo "
        {$this->objeto_js}.evt__condicion__procesar = function(es_inicial)
		{
            if ( this.ef('valor_hasta') ) {
                var estado = this.ef('condicion').get_estado();
                if ( estado == 'entre' ) {
                    this.ef('valor_hasta').mostrar();
                } else {
                    this.ef('valor_hasta').ocultar(true);
                }
            }
        }
        ";
    }
}
