<?php
/**
 * @todo cambiar por namespace
 */
require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_formulario.php';

class form_resumen_de_encuestas extends bootstrap_formulario
{
	protected $encuesta;
	protected $form_datos=false;
	
	function ini()
	{
		$this->encuesta = $this->controlador()->s__encuesta;
		$this->form_datos = $this->controlador()->s__form_datos;
	}		
	
	//-----------------------------------------------------------------------------------
	//---- Extiendo generar_html para agregar el boton de exportar a formato excel
	//-----------------------------------------------------------------------------------	
	
	function generar_html()
	{
		//Genero la interface
		echo "\n\n<!-- ***************** Inicio EI FORMULARIO (	".	$this->_id[1] ." )	***********	-->\n\n";
		//Campo de sincroniacion con JS
		echo toba_form::hidden($this->_submit, '');
		echo toba_form::hidden($this->_submit.'_implicito', '');
		$ancho = '';
		if (isset($this->_info_formulario["ancho"])) {
			$ancho = convertir_a_medida_tabla($this->_info_formulario["ancho"]);
		}
		echo "<table class='{$this->_estilos}' $ancho>";
		
		//comienza agregado		
			echo "<tr><td>";		
			$img = toba_recurso::imagen_toba('exp_xls.gif', true);
       		echo "<a href='javascript: {$this->objeto_js}.exportar_excel()' title='Exporta el listado a formato Excel (.xls)'>$img</a>";
			echo "</td></tr>\n";
		//termina agregado		
		
		echo "<tr><td style='padding:0'>";		
		echo $this->get_html_barra_editor();	
		$this->generar_html_barra_sup(null, true,"ei-form-barra-sup");
		$this->generar_formulario();
		echo "</td></tr>\n";
		echo "</table>\n";
		$this->_flag_out = true;
	}

	//-----------------------------------------------------------------------------------
	//---- Extiendo vista_excel para setear el nombre del archivo excel
	//-----------------------------------------------------------------------------------
	
	function vista_excel(toba_vista_excel $salida) 
	{
		if (isset($this->encuesta)) {
			$nombre = 'resumen_encuesta_'.$this->encuesta.'.xls';
		} else {
		 	$nombre = 'resumen_encuesta.xls';
		}
		$salida->set_nombre_archivo($nombre);
		parent::vista_excel($salida);
	}	
	
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		{$this->objeto_js}.exportar = {$this->objeto_js}.exportar_excel;	
		{$this->objeto_js}.exportar_excel = function() {
				var nombre = this.ef('nombre').get_estado();
				if (nombre != '') {
					{$this->objeto_js}.exportar();
				}
		}
		";
	}
}

?>