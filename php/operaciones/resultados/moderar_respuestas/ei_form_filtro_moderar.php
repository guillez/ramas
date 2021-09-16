<?php
/**
* @todo cambiar por namespace
*/
require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_formulario.php';

class ei_form_filtro_moderar extends bootstrap_formulario
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		$mensaje_control_fechas = $this->get_mensaje('control_fechas', array('Desde', 'mayor', 'Hasta'));
		
		echo "
		
		//---- Validacion general ----------------------------------
		
		{$this->objeto_js}.evt__validar_datos = function()
		{
			if (this.ef('fecha_desde').activo() && this.ef('fecha_hasta').activo()) {
				if (this.ef('fecha_desde').tiene_estado() && this.ef('fecha_hasta').tiene_estado()) {
					if (this.ef('fecha_desde').fecha() > this.ef('fecha_hasta').fecha()) {
						notificacion.agregar('$mensaje_control_fechas');
						return false;
					}
				}
			}
			return true;
		}
		
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__habilitacion__procesar = function(es_inicial)
		{
			var hab = this.ef('habilitacion').get_estado();
			this.controlador.ajax('es_anonima', hab, this, this.setear_anonima);
			return false;
		}
		
		{$this->objeto_js}.setear_anonima = function(datos)
		{
            if (datos['hab_anonima'] != 'NO_EXISTE') {
                if (datos['hab_anonima'] == 'S') {
                    this.ef('fecha_desde').resetear_estado();
                    //this.ef('fecha_desde').set_solo_lectura(true);
                    this.ef('fecha_hasta').resetear_estado();
                    //this.ef('fecha_hasta').set_solo_lectura(true);
                    
                    this.ef('fecha_desde').ocultar();
                    this.ef('fecha_hasta').ocultar();
                } else {
                    //this.ef('fecha_desde').set_solo_lectura(false);
                    //this.ef('fecha_hasta').set_solo_lectura(false);
                    
                    this.ef('fecha_desde').mostrar();
                    this.ef('fecha_hasta').mostrar();
                }
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