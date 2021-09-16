<?php

require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_formulario.php';

class filtro_reportes extends bootstrap_formulario
{
	
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__archivadas__procesar = function(es_inicial)
		{
            if (!es_inicial) {
                var archivadas = this.ef('archivadas').get_estado();
                this.controlador.ajax('get_habilitaciones', archivadas, this, this.setear_habilitaciones);
                return false;
            }
		}
        
        {$this->objeto_js}.setear_habilitaciones = function(datos)
		{
            this.ef('habilitacion').set_opciones_rs(datos['respuesta']);
		}
        
		";
	}

}
?>