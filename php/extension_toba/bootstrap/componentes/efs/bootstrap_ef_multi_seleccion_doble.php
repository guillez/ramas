<?php
namespace ext_bootstrap\componentes\efs;

use ext_bootstrap\componentes\interfaz\bootstrap_form;

class bootstrap_ef_multi_seleccion_doble extends \toba_ef_multi_seleccion_doble
{
	function get_input()
	{
		$tab = $this->padre->get_tab_index();
		$extra = " tabindex='$tab'";
		if (isset($this->ancho)) {
			$extra .= " style='width: {$this->ancho}'";
		}
		$html = '';
		if (!$this->es_solo_lectura() && $this->mostrar_utilidades)	{
			$html .= "
			<div class='ef-multi-sel-todos' id='{$this->id_form}_utilerias'>
			<a href=\"javascript:{$this->objeto_js()}.seleccionar_todo(true)\">Todos</a> /
			<a href=\"javascript:{$this->objeto_js()}.seleccionar_todo(false)\">Ninguno</a>
			</div>
			";
		}
		$tamanio = isset($this->tamanio) ? $this->tamanio: count($this->opciones);
		$estado = $this->get_estado_para_input();
		$izq = array();
		$der = array();
		foreach ($this->opciones as $clave => $valor) {
			if (in_array($clave, $estado)) {
				$der[$clave] = $valor;
			} else {
				$izq[$clave] = $valor;
			}
		}
		$etiq_izq = "Disponibles";
		$etiq_der = "Seleccionados";
		$ef_js = $this->objeto_js();
		$img_der = \toba_recurso::imagen_toba('nucleo/paginacion/no_siguiente.gif', false);
		$boton_der = "<img src='$img_der' id='{$this->id_form}_img_izq' onclick=\"$ef_js.pasar_a_derecha()\" class='ef-multi-doble-boton'>";
		$img_izq = \toba_recurso::imagen_toba('nucleo/paginacion/no_anterior.gif', false);
		$boton_izq = "<img src='$img_izq' id='{$this->id_form}_img_der' onclick=\"$ef_js.pasar_a_izquierda()\" class='ef-multi-doble-boton'>";

		$disabled = ($this->es_solo_lectura()) ? "disabled" : "";
		$html .= "<table class='{$this->clase_css}'>";
		$html .= "<tr><td>$etiq_izq</td><td></td><td>$etiq_der</td></tr>";
		$html .= "<tr><td>";

		$html .= \toba_form::multi_select($this->id_form."_izq", array(), $izq, $tamanio, 'ef-combo', "$extra $disabled ondblclick=\"$ef_js.pasar_a_derecha();\" onchange=\"$ef_js.refrescar_iconos('izq');\"");
		$html .= "</td><td>$boton_der<br /><br />$boton_izq</td><td>";
		$html .= bootstrap_form::multi_select($this->id_form, array(), $der, $tamanio, 'ef-combo', "$extra $disabled ondblclick=\"$ef_js.pasar_a_izquierda();\" onchange=\"$ef_js.refrescar_iconos('der');\"");
		$html .= $this->get_html_iconos_utilerias();
		$html .= "</td></tr>";
		$html .= "</table>";
		return $html;
	}
}