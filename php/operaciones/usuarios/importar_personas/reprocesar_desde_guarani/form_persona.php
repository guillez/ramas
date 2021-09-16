<?php
/**
 * @todo cambiar por namespace de alguna manera
 */
require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_formulario.php';


class form_persona extends bootstrap_formulario
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		parent::extender_objeto_js();
		$mensaje_fecha_nac = $this->get_mensaje('fecha_formato_erroneo');
		$mensaje_sexo	   = $this->get_mensaje('sexo_erroneo');
		$mensaje_email     = $this->get_mensaje('email_erroneo');
		
		echo "
		
		//---- Validacion de EFs -----------------------------------
		
		{$this->objeto_js}.evt__sexo__validar = function()
		{
			if (this.ef('sexo').tiene_estado()) {
				if (this.ef('sexo').get_estado().toLowerCase() != 'f' && this.ef('sexo').get_estado().toLowerCase() != 'm') {
					this.ef('sexo').set_error('$mensaje_sexo');
					return false;
				}
			}
			
			return true;
		}
		
		{$this->objeto_js}.evt__fecha_nacimiento__validar = function()
		{
			if (this.ef('fecha_nacimiento').tiene_estado()) {
				if (!this.valida_fecha()) {
					this.ef('fecha_nacimiento').set_error('$mensaje_fecha_nac');
					return false;
				}
			}
			
			return true;
		}
		
		{$this->objeto_js}.evt__email__validar = function()
		{
			if (this.ef('email').tiene_estado()) {
				if (!this.valida_email()) {
					this.ef('email').set_error('$mensaje_email');
					return false;
				}
			}
			
			return true;
		}
		
		{$this->objeto_js}.valida_fecha = function()
		{
			var fecha = this.ef('fecha_nacimiento').get_estado();
			
			if (!/^\d{2}\/\d{2}\/\d{4}$/.test(fecha)) {
				return false;
			}
			
			var fecha_arr = fecha.split('/');
			var dia  	  = fecha_arr[0];
			var mes  	  = fecha_arr[1];
			var anio 	  = fecha_arr[2];
			var date 	  = new Date(anio, mes - 1, dia);
			
			return !date || date.getFullYear() == anio && date.getMonth() == mes - 1 && date.getDate() == dia;
		}
		
		{$this->objeto_js}.valida_email = function()
		{
			return /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/i.test(this.ef('email').get_estado());
		}

		";
	}

}
?>