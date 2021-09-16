<?php
namespace  ext_bootstrap\componentes\efs;

use ext_bootstrap\componentes\interfaz\bootstrap_form;

class bootstrap_ef_cuit extends \toba_ef_cuit
{
	function get_input()
	{
		if( !isset($this->estado)) {
			$this->estado="";
		}
	
		$tab = ' tabindex="'.$this->padre->get_tab_index().'"';
		$html = "<div class='{$this->clase_css}'>";
		$html .= bootstrap_form::text($this->id_form . "_1", substr($this->estado,0,2),$this->es_solo_lectura(), 2, 2, 'ef-input', $this->javascript.$this->input_extra.$tab);
		$html .= ' - ';
		$html .= bootstrap_form::text($this->id_form . "_2", substr($this->estado,2,8),$this->es_solo_lectura(), 8, 8, 'ef-input', $this->javascript.$this->input_extra.$tab);
		$html .= ' - ';
		$html .= bootstrap_form::text($this->id_form . "_3", substr($this->estado,10,1),$this->es_solo_lectura(), 1, 1, 'ef-input', $this->javascript.$this->input_extra.$tab);
		$html .= $this->get_html_iconos_utilerias();
		$html .= '</div>';
		return $html;
	}
}