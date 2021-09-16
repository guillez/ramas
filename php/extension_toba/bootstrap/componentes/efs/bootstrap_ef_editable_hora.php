<?php
namespace ext_bootstrap\componentes\efs;

use ext_bootstrap\componentes\interfaz\bootstrap_form;

class bootstrap_ef_editable_hora extends \toba_ef_editable_hora
{
	protected $clase_css ="form-control" ;

	function get_input()
	{
		$estado_hora = (! is_null($this->estado))? $this->estado : '';

		$tab = ' tabindex="'.$this->padre->get_tab_index().'"';
		$html = "<span class='ef-fecha-hora'>";
		$visibilidad = "style= 'visibility:hidden;'";
		if (! $this->es_solo_lectura()) {	//Hay que ver si es solo lectura por la cascada o que?
			$visibilidad = "style= 'visibility:visible;'";
		}
		$html .= bootstrap_form::text($this->id_form, $estado_hora, $this->es_solo_lectura(), 5,  5, $this->clase_css . '  ef-numero ', $this->input_extra. $tab);
		$html .= $this->get_html_iconos_utilerias();
		$html .= "</span>\n";
		return $html;
	}
}