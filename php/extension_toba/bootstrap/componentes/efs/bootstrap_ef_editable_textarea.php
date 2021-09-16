<?php
namespace ext_bootstrap\componentes\efs;

use ext_bootstrap\componentes\interfaz\bootstrap_form;

class bootstrap_ef_editable_textarea extends \toba_ef_editable_textarea
{
	protected $clase ="form-control" ;

	/**
	 * @todo No se que hace la propiedad 'resaltar', revisarlo!!
	 */
	function get_input()
	{
		
		if (!isset($this->estado)) {
			$this->estado = '';
		}
		$html = "";
		
		if($this->es_solo_lectura()){
			$this->input_extra .= " readonly ";
		}
		
		$this->input_extra .= $this->get_info_placeholder();
			
		if($this->resaltar){
			
			$javascript = " onclick='javascript: document.getElementById('{$this->id_form}').select()'";
			$html .= bootstrap_form::button($this->id_form . "_res", "Seleccionar", $javascript );
		}
		
		if ($this->maximo) {
			
			$this->input_extra .= "maxlength='{$this->maximo}'";
		}
		
		
		
		$this->input_extra .= ' tabindex="'.$this->padre->get_tab_index().'"';
		
		$html .= bootstrap_form::textarea( $this->id_form, $this->estado, $this->lineas, $this->tamano, $this->clase, $this->wrap, $this->javascript.' '.$this->input_extra);
		
		$html .= $this->get_html_iconos_utilerias();
		return $html;
	}
	
}