<?php
/**
* @todo cambiar por namespace de alguna manera
*/
require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_formulario.php';


class formulario extends bootstrap_formulario
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__accion__procesar = function(es_inicial)
		{
            if (this.ef('accion').tiene_estado()) {
                if (this.ef('accion').get_estado() == 'E') {
                    this.ef('encuesta').mostrar();
                    this.ef('archivo').ocultar(true);
                    this.ef('archivo').resetear_estado();
                } else {
                    this.ef('archivo').mostrar();
                    this.ef('encuesta').ocultar(true);
                    this.ef('encuesta').resetear_estado();
                }
            } else {
                this.ef('encuesta').ocultar(true);
                this.ef('archivo').ocultar(true);
            }
		}
		";
	}
}

?>