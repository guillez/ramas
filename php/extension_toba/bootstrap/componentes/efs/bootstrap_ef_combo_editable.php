<?php
namespace ext_bootstrap\componentes\efs;

use ext_bootstrap\componentes\interfaz\bootstrap_form;

class bootstrap_ef_combo_editable extends \toba_ef_combo_editable
{
	protected  $clase_css = "form-control";
	
	function get_input()
	{
		//-- Si tiene un estado, ponerlo como ?nica opci?n
		$this->opciones = array();
		if (isset($this->descripcion_estado)) {
			$this->opciones[$this->estado] = $this->descripcion_estado;
		}
		$html = "";
	
		//El estado que puede contener muchos datos debe ir en un unico string
		$estado = $this->get_estado_para_input();
		if ($this->es_solo_lectura()) {
			$html .= bootstrap_form::select("",$estado, $this->opciones, $this->clase_css, "disabled");
			$html .= bootstrap_form::hidden($this->id_form, $estado);
			return $html;
		} else {
			$tab = $this->padre->get_tab_index();
			$extra = " tabindex='$tab'";
			$js = '';
	
			if ($this->cuando_cambia_valor != '') {
				$js = "onchange=\"{$this->get_cuando_cambia_valor()}\"";
			}
	
			$html .= bootstrap_form::select($this->id_form, $estado ,$this->opciones, $this->clase_css, $js . $this->input_extra.$extra, $this->categorias);
		}
		$html .= $this->get_html_iconos_utilerias();
		return $html;
	}
	
}