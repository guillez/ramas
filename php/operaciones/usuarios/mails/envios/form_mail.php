<?php
require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_formulario.php';

class form_mail extends bootstrap_formulario
{
	
	function generar_layout()
	{
		echo "
			<div>
				<div class='col-md-12'>
						";$this->generar_html_ef('nombre'); echo "
				</div>
				<div class='col-md-12'>
						";$this->generar_html_ef('from'); echo "
				</div>
				<div class='col-md-12'>
						";$this->generar_html_ef('asunto'); echo "
				</div>          
				<div class='col-md-12'>
						";$this->generar_html_ef('parametros'); echo "
				</div>
				<div class='col-md-12'>
						";$this->generar_html_ef('contenido'); echo "
				</div>
				<div class='col-md-12'>
						";$this->generar_html_ef('nota_al_pie'); echo "
				</div>
				<div class='col-md-12'>
						";$this->generar_html_ef('nota_al_pie_contenido'); echo "
				</div>
				<div class='col-md-12'>
						<b>Adjuntar archivos</b>
				</div>
				<div class='col-md-4'>
						";$this->generar_html_ef('archivo1', 0); echo "
				</div>
				<div class='col-md-4'>
						";$this->generar_html_ef('archivo2', 0); echo "
				</div>
				<div class='col-md-4'>
						";$this->generar_html_ef('archivo3', 0); echo "
				</div>
			</div>
			<p> </p>
		";
	}

	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__nota_al_pie__procesar = function(es_inicial)
		{
		    if (this.ef('nota_al_pie').get_estado() == \"S\" ) {
		        this.ef('nota_al_pie_contenido').mostrar();
		    } else {
		        this.ef('nota_al_pie_contenido').ocultar();
		    }
		} 
		";
	}

}
?>