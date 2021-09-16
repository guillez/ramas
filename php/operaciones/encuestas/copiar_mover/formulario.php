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
                if (this.ef('accion').get_estado() == 'C') {
                    this.ef('unidad_gestion_destino_copiar').mostrar();
                    this.ef('unidad_gestion_destino_mover').ocultar(true);
                    this.ef('unidad_gestion_destino_mover').resetear_estado();
                } else {
                    this.ef('unidad_gestion_destino_mover').mostrar();
                    this.ef('unidad_gestion_destino_copiar').ocultar(true);
                    this.ef('unidad_gestion_destino_copiar').resetear_estado();
                }
            } else {
                this.ef('unidad_gestion_destino_copiar').ocultar(true);
                this.ef('unidad_gestion_destino_mover').ocultar(true);
            }
		}
		";
	}

}

?>