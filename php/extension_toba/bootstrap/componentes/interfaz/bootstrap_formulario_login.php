<?php
require_once 'bootstrap_formulario.php';

class bootstrap_formulario_login extends bootstrap_formulario
{
	protected $clase_formulario = "panel panel-default login-form";
	
	protected function get_html_ef($ef, $ancho_etiqueta=null, $con_etiqueta=true)
	{
		$salida = '';
		if (! in_array($ef, $this->_lista_ef_post)) {
			//Si el ef no se encuentra en la lista posibles, es probable que se alla quitado con una restriccion o una desactivacion manual
			return;
		}
		$clase = 'form-group col-md-12';
		$estilo_nodo = "";
		$id_ef = $this->_elemento_formulario[$ef]->get_id_form();
	
		if (! $this->_elemento_formulario[$ef]->esta_expandido()) {
			$clase .= ' ei-form-fila-oculta';
			$estilo_nodo = "display:none";
		}
		if (isset($this->_info_formulario['resaltar_efs_con_estado'])
				&& $this->_info_formulario['resaltar_efs_con_estado'] && $this->_elemento_formulario[$ef]->seleccionado()) {
					$clase .= ' ei-form-fila-filtrada';
		}
		
		$es_fieldset = ($this->_elemento_formulario[$ef] instanceof toba_ef_fieldset);
		if (! $es_fieldset) {							//Si es fieldset no puedo sacar el <div> porque el navegador cierra visualmente inmediatamente el ef.
			$salida .= "<div class='$clase' style='$estilo_nodo' id='nodo_$id_ef'>\n";
		}
		if ($this->_elemento_formulario[$ef]->tiene_etiqueta() && $con_etiqueta) {
			$salida .= $this->get_etiqueta_ef($ef, $ancho_etiqueta);
				
			$salida .= "<div id='cont_$id_ef' >\n";
			$salida .= $this->get_input_ef($ef);
			$salida .= "</div>";
			if (isset($this->_info_formulario['expandir_descripcion']) && $this->_info_formulario['expandir_descripcion']) {
				$salida .= '<span class="ei-form-fila-desc">'.$this->_elemento_formulario[$ef]->get_descripcion().'</span>';
			}

		} else {
			$salida .= $this->get_input_ef($ef);
		}
		if (! $es_fieldset) {
			$salida .= "</div>\n";
		}
		return $salida;
	}
	
	protected function get_etiqueta_ef($ef, $ancho_etiqueta=null)
	{
		$estilo = '';
		$marca ='';
	
		if ($this->_elemento_formulario[$ef]->es_obligatorio()) {
			$marca .= '(*)';
		} else {
			$estilo .= ' opcional';
		}
	
		$desc='';
		if (!isset($this->_info_formulario['expandir_descripcion']) || ! $this->_info_formulario['expandir_descripcion']) {
			$desc = $this->_elemento_formulario[$ef]->get_descripcion();
			if ($desc !=""){
				$desc = toba_parser_ayuda::parsear($desc);
				$desc = "<span class='glyphicon glyphicon-pushpin' data-toggle='tooltip' data-placement='top' title='$desc'></span>";
			}
		}
		$id_ef = $this->_elemento_formulario[$ef]->get_id_form();
		$editor = $this->generar_vinculo_editor($ef);
		$etiqueta = $this->_elemento_formulario[$ef]->get_etiqueta();
	
	
		return "<label class='$estilo' for='$id_ef' >$editor $desc $etiqueta $marca </label>\n";
	}
	
}