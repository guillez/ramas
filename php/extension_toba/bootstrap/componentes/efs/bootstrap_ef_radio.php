<?php
namespace ext_bootstrap\componentes\efs;

use ext_bootstrap\componentes\interfaz\bootstrap_form;

class bootstrap_ef_radio extends \toba_ef_radio
{
	protected $clase_css ="radio-inline" ;

	function get_input()
	{
		$estado = $this->get_estado_para_input();
		$html = '';
		if ($this->es_solo_lectura()) {
			$html .= bootstrap_form::hidden($this->id_form, $estado);
		}
		$callback = "onchange=\"{$this->get_cuando_cambia_valor()}\"";
		//--- Se guarda el callback en el <div> asi puede ser recuperada en caso de que se borren las opciones
		$html .= "<div id='opciones_{$this->id_form}' $callback>\n";
		if (!is_array($this->opciones)) {
			$datos = array();
		} else {
			$datos = $this->opciones;
		}
		$i=0;
		$tab_index = "tabindex='".$this->padre->get_tab_index()."'";
		foreach ($datos as $clave => $valor) {
			
			$id = $this->id_form . $i;
			$html .= "<label class='{$this->clase_css}' for='$id'>";
			$es_actual = (strval($estado) == strval($clave));
			if (! $this->es_solo_lectura()) {
				$sel = ($es_actual) ? "checked" : "";
				if (! $this->permitir_html) {
					$clave = texto_plano($clave);
				}
				$html .= "<input type='radio' id='$id' name='{$this->id_form}' value='$clave' $sel $callback $tab_index />";
				$tab_index = '';
			} else {
				//--- Caso solo lectura
				$img = ($es_actual) ? 'efradio_on.gif' : 'efradio_off.gif';
				$html .= \toba_recurso::imagen_toba('nucleo/'.$img,true,16,16);
			}
			if (! $this->permitir_html) {
				$valor = texto_plano($valor);
			}
			$html .= "$valor</label>\n";
			$i++;
			
		}
		
		$html .= "</div>\n";
		$html .= $this->get_html_iconos_utilerias();
		return $html;
	}
}