<?php

/**
 * @todo cambiar por namespace
 */
require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_formulario.php';

class ei_filtro_exportar extends bootstrap_formulario
{
	function extender_objeto_js()
	{
		echo "
		        //---- Procesamiento de EFs --------------------------------
		        
		        {$this->objeto_js}.evt__reporte_tipo__procesar = function(es_inicial)
		        {
		            this.ef('codigos').set_solo_lectura(true);
		
		            if (this.ef('reporte_tipo').tiene_estado()) {
		                
		                valor = this.ef('reporte_tipo').get_estado();
		                
		                if (valor == 1) {
		                    this.ef('codigos').set_solo_lectura(false);
                            this.ef('formulario_habilitado').set_solo_lectura(false);
		                    return;
		                }
		                
		                if (valor == 3) {
		                    this.ef('codigos').set_solo_lectura(false);
		                    return;
		                }
                        		
		                this.ef('codigos').set_solo_lectura(true);
		            }
		        }
                
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