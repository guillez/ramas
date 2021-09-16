<?php

namespace ext_bootstrap\componentes\efs;

use ext_bootstrap\componentes\interfaz\bootstrap_form;

class bootstrap_ef_cbu extends \toba_ef_cbu{
	
	function get_input()
	{
		$tab = ' tabindex="'.$this->padre->get_tab_index().'" ';
		$tab .= $this->get_info_placeholder();
		$html = bootstrap_form::text($this->id_form, $this->estado,$this->es_solo_lectura(),22,29, $this->clase_css, $this->javascript.' '.$tab);
		$html .= $this->get_html_iconos_utilerias();
		return $html;
	}
	
}