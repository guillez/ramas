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
		parent::extender_objeto_js();
		$hoy = kolla_fecha::get_hoy_js();
		$mensaje_control_fechas = $this->get_mensaje('control_fechas', array('Desde', 'mayor', 'Hasta'));
		$mensaje_fecha_hasta = $this->get_mensaje('fecha_hasta_erronea');
		$mensaje_desde_hasta = $this->get_mensaje('fecha_desde_erronea');
		        
		echo "
		
		var fecha_hasta_modificada = false;
		var fecha_desde_modificada = false;
		var valor_inicial_hasta;
		var valor_inicial_desde;
		
		//---- Validacion general ----------------------------------
		
		{$this->objeto_js}.evt__validar_datos = function()
		{
		    if (this.ef('fecha_desde').tiene_estado() && this.ef('fecha_hasta').tiene_estado()) {
				if (this.ef('fecha_desde').fecha() > this.ef('fecha_hasta').fecha()) {
					notificacion.agregar('$mensaje_control_fechas');
					return false;		
				}
			}
            
			return true;
		}
		
		//---- Validacion de EFs -----------------------------------
		
		{$this->objeto_js}.evt__fecha_desde__validar = function()
		{
			if (this.ef('fecha_desde').tiene_estado() && fecha_desde_modificada) {
				if (this.ef('fecha_desde').fecha() < new $hoy) {
					this.ef('fecha_desde').set_error('$mensaje_desde_hasta');
					return false;
				}
			}
            
			return true;
		}
		
		{$this->objeto_js}.evt__fecha_hasta__validar = function()
		{
			if (this.ef('fecha_hasta').tiene_estado() && fecha_hasta_modificada) {
				if (this.ef('fecha_hasta').fecha() < new $hoy) {
					this.ef('fecha_hasta').set_error('$mensaje_fecha_hasta');
					return false;
				}
			}
            
			return true;
		}
		
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__fecha_hasta__procesar = function(es_inicial)
		{
			if (es_inicial) {
				valor_inicial_hasta = this.ef('fecha_hasta').fecha();
				if (valor_inicial_hasta != null) {
					valor_inicial_hasta = valor_inicial_hasta.getTime();
				}
			} else {
				if (this.ef('fecha_hasta').fecha().getTime() != valor_inicial_hasta) {
					fecha_hasta_modificada = true;
				}
			}
		}
		
		{$this->objeto_js}.evt__fecha_desde__procesar = function(es_inicial)
		{
			if (es_inicial) {
				valor_inicial_desde = this.ef('fecha_desde').fecha();
				if (valor_inicial_desde != null) {
					valor_inicial_desde = valor_inicial_desde.getTime();
				}
			} else {
				if (this.ef('fecha_desde').fecha().getTime() != valor_inicial_desde) {
					fecha_desde_modificada = true;
				}
			}
		}
		
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__publica__procesar = function(es_inicial)
		{
            if (this.ef('publica').chequeado()) {
                this.ef('anonima').resetear_estado();
                this.ef('anonima').desactivar();
            } else {
                this.ef('anonima').activar();
            }
		}
		";
	}

}
?>