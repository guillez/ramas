<?php
/**
 * @todo cambiar por namespace de alguna manera
 */
require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_formulario.php';

class form_usuarios extends bootstrap_formulario
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__grupo_existente__procesar = function(es_inicial)
		{
            if ( this.ef('grupo_existente').get_estado() == 1 ) {
                this.ef('grupo').mostrar();
                this.ef('grupo_nombre').ocultar(true);
                this.ef('grupo_descripcion').ocultar(true);
            } else {
                this.ef('grupo').ocultar(true);
                this.ef('grupo_nombre').mostrar();
                this.ef('grupo_descripcion').mostrar();
            }
		}
		";
	}

}

?>