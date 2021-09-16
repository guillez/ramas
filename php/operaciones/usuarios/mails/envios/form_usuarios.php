<?php

require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_formulario.php';

class form_usuarios extends bootstrap_formulario
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		$msj_filtrado = 'No se indicó ningún grupo, se buscarán usuarios de todos los grupos de la habilitación.';
		        
		echo "
		        
		//---- Validacion general ----------------------------------
		
		{$this->objeto_js}.evt__validar_datos = function()
		{
            /*  Si se selecciona una habilitación pero no se indica ningún grupo,
                entonces se advierte al usuario que se buscarán todos los usuarios
                pertenecientes a la habilitación. */

            var confirma = true;

            if (this.ef('habilitacion').tiene_estado() && !this.ef('formulario_habilitado').tiene_estado()) {
                confirma = confirm('$msj_filtrado');
            } 

            return confirma;
		}
		        
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__unidad_gestion__procesar = function(es_inicial)
		{
            if (this.ef('unidad_gestion').tiene_estado()) {
                this.ef('habilitacion').mostrar();
                this.ef('habilitacion').set_obligatorio(true);
                
                if (!this.ef('habilitacion').tiene_estado()) {
                    this.ef('formulario_habilitado').ocultar();
                    this.ef('formulario_habilitado').resetear_estado();
                    this.ef('terminada').ocultar();
                    this.ef('terminada').resetear_estado();
                }
            } else {
                this.ef('formulario_habilitado').ocultar();
                this.ef('formulario_habilitado').resetear_estado();
                this.ef('terminada').ocultar();
                this.ef('terminada').resetear_estado();
                this.ef('habilitacion').ocultar();
                this.ef('habilitacion').resetear_estado();
                this.ef('habilitacion').set_obligatorio(false);
                
                if (es_inicial) {
                    var campo = this.ef('unidad_gestion').input();
                    if (campo.options.length == 2) {
                        this.ef('unidad_gestion').set_estado(campo.options[1].value);
                    }
                }
            }
		}
		
		{$this->objeto_js}.evt__habilitacion__procesar = function(es_inicial)
		{
            if (this.ef('habilitacion').tiene_estado()) {
                this.ef('formulario_habilitado').mostrar();
                var hab = this.ef('habilitacion').get_estado();
                this.controlador.ajax('es_anonima', hab, this, this.mostrar_ocultar_campo);
                return false;
            } else {
                this.ef('formulario_habilitado').ocultar();
                this.ef('formulario_habilitado').resetear_estado();
                this.ef('terminada').ocultar();
                this.ef('terminada').resetear_estado();
            }
		}
		
        {$this->objeto_js}.mostrar_ocultar_campo = function(datos)
		{
			if (datos['hab_anonima'] == 'N') {
				this.ef('terminada').mostrar();
                if (!this.ef('terminada').tiene_estado()) {
                     this.ef('terminada').set_estado('S');
                }
			} else {
                this.ef('terminada').ocultar();
                this.ef('terminada').resetear_estado();
            }
		}
        
		";
	}

}
?>