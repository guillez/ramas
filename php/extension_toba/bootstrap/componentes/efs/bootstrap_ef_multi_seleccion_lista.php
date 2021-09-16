<?php
namespace ext_bootstrap\componentes\efs;

use ext_bootstrap\componentes\interfaz\bootstrap_form;

class bootstrap_ef_multi_seleccion_lista extends \toba_ef_multi_seleccion_lista
{

	function get_input()
	{
		$estado = $this->get_estado_para_input();
		$html = "";
		if (!$this->es_solo_lectura() && $this->mostrar_utilidades)	{
			$html .= "
			<div class='ef-multi-sel-todos' id='{$this->id_form}_utilerias'>
			<a href=\"javascript:{$this->objeto_js()}.seleccionar_todo(true)\">Todos</a> /
			<a href=\"javascript:{$this->objeto_js()}.seleccionar_todo(false)\">Ninguno</a></div>
			";
		}
		$tamanio = isset($this->tamanio) ? $this->tamanio: count($this->opciones);
		$tab = $this->padre->get_tab_index();
		$extra = " tabindex='$tab'";
		$extra .= ($this->es_solo_lectura()) ? "disabled" : "";
		if (isset($this->ancho)) {
			$extra .= " style='width: {$this->ancho}'";
		}
		$html .= bootstrap_form::multi_select($this->id_form, $estado, $this->opciones, $tamanio, $this->clase_css, $extra);
		$html .= $this->get_html_iconos_utilerias();
		return $html;
	}
}