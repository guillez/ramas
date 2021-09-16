<?php
require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_formulario.php';


class form_envios_anteriores extends bootstrap_formulario
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
            
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__unidad_gestion__procesar = function(es_inicial)
		{
            if (this.ef('unidad_gestion').tiene_estado()) {
                this.ef('mail_sin_habilitacion').desactivar();
                this.ef('mail_sin_habilitacion').resetear_estado()
            } else {
                this.ef('mail_sin_habilitacion').activar();
            }
		}
		";
	}

}
?>