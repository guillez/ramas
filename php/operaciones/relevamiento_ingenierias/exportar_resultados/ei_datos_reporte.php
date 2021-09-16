<?php
class ei_datos_reporte extends toba_ei_formulario
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__titulo_araucano__procesar = function(es_inicial)
		{
			var instit 	= this.ef('institucion_araucano').get_estado();
			var titulo 	= this.ef('titulo_araucano').get_estado();
			var archivo = this.ef('archivo').get_estado();
			var date 	= new Date();
			var anio 	= date.getFullYear();
			var mes 	= date.getMonth() + 1;
			
			if (mes < 10) {
				mes = '0' + mes;
			}
			
			if (titulo != 'nopar') {
				this.ef('archivo').set_estado('ing-' + instit + '-' + titulo + '-' + anio + mes + 'kol.txt');
			} else {
				this.ef('archivo').set_estado('ing-' + instit + '-NA-' + anio + mes + 'kol.txt');
			}
		}
		";
	}
	
}	
?>