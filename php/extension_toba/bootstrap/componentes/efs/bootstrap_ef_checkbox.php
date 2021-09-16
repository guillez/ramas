<?php
namespace ext_bootstrap\componentes\efs;


use ext_bootstrap\componentes\interfaz\bootstrap_form;

class bootstrap_ef_checkbox extends \toba_ef_checkbox
{
	function get_input()
	{
		//Esto es para eliminar un notice en php 5.0.4
		if (!isset($this->estado)) {
			$this->estado = null;
		}
		if ($this->es_solo_lectura()) {
			$html = bootstrap_form::hidden($this->id_form, $this->seleccionado() ? $this->valor : $this->valor_no_seteado);
			if ($this->seleccionado()) {
				$html .= \toba_recurso::imagen_toba('nucleo/efcheck_on.gif',true,16,16);
			} else {
				$html .= \toba_recurso::imagen_toba('nucleo/efcheck_off.gif',true,16,16);
			}
		} else {
			$js = '';
			if ($this->cuando_cambia_valor != '') {
				$js = "onchange=\"{$this->get_cuando_cambia_valor()}\"";
			}
			$tab = $this->padre->get_tab_index();
			$extra = " tabindex='$tab'";
			$html = bootstrap_form::checkbox($this->id_form, $this->estado, $this->valor, $this->clase_css, $extra.' '.$js);
		}
		$html .= $this->get_html_iconos_utilerias();
		return $html;
	}

}