<?php
namespace ext_bootstrap\componentes\efs;

use ext_bootstrap\componentes\interfaz\bootstrap_form;

class bootstrap_ef_editable_numero extends \toba_ef_editable_numero
{
	protected $clase_css ="form-control" ;

	function get_input()
	{
		$tab = ' tabindex="'.$this->padre->get_tab_index().'"';
		$input = bootstrap_form::text($this->id_form, $this->estado,$this->es_solo_lectura(),$this->maximo,$this->tamano, $this->clase_css, $this->javascript.' '.$this->input_extra.$tab);
		if (isset($this->unidad)) {
			$input = "<span class='ef-editable-unidad'>".$input .' '.$this->unidad.'</span>';
		}
		$input .= $this->get_html_iconos_utilerias();
		return $input;
	}
}