<?php 
require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_formulario.php';

class form_localidades extends bootstrap_formulario
{

	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		$input = toba::memoria()->get_parametro('input');
		$input_desc = 'desc_' . $input;
		echo "
		{$this->objeto_js}.evt__guardar = function()
		{
			var valor = this.ef('localidad').valor();
			var campo = this.ef('localidad').input();
			var id = campo.selectedIndex;
			var opcion = campo.options[id].text;
			if(opener.document.formulario.$input) {
				opener.document.formulario.$input.value = valor;
				opener.document.formulario.$input_desc.value = opcion;
				window.close();
			}else{
				alert('Error!');
			}
		}
		
		{$this->objeto_js}.evt__cancelar = function()
		{
			window.close();
		}
		";
	}
}

?>