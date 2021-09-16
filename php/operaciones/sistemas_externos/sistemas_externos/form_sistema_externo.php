<?php
/**
 * @todo cambiar por namespace de alguna manera
 */
require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_formulario.php';

class form_sistema_externo extends bootstrap_formulario
{

	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{		
		echo "
			//---- Procesamiento de EFs -------------------------------- 
			
			{$this->objeto_js}.evt__nombre__procesar = function(es_inicial)
			{
				var conexion = this.ef('nombre').get_estado();
				var usuario  = this.ef('usuario').get_estado();
                
				if ( conexion != '' ) {
					conexion = conexion.replace(/ /g, '_');
					usuario  = 'ue_' + conexion.toLowerCase();
					this.ef('usuario').set_estado(usuario);
				}
			}
		";
	}
}

?>