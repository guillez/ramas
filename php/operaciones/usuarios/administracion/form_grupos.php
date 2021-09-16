<?php
/**
 * @todo cambiar por namespace de alguna manera
 */
require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_formulario.php';

class form_grupos extends bootstrap_formulario
{
	function extender_objeto_js()
	{
		echo "
        
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__unidad_gestion__procesar = function(es_inicial)
		{
            if (!es_inicial) {
                this.set_evento(new evento_ei('recargar', false, false ));
            }
		}
        
		";
	}

}
?>