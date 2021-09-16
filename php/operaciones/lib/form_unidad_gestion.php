<?php

require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_formulario.php';

class form_unidad_gestion extends bootstrap_formulario
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
            if (!es_inicial) {
                this.set_evento(new evento_ei('recargar', false, false));
            }
		}
		";
	}

	protected function get_etiqueta_ef($ef, $ancho_etiqueta=null)
    {
        return "<label class = 'control-label col-sm-2 opcional' style = 'padding-top: 6px;'>
                    <span><span class = 'glyphicon glyphicon-list-alt' style = 'padding: 0px 4px 0px 0px;'></span></span>
                    <b style = 'font-size: 13px;'>Unidad de Gestión:</b>
                </label>";
    }
    
    function get_estilo_ef()
    {
        return '';
    }

}
?>