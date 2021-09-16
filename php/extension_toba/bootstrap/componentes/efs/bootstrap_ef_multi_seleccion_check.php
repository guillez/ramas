<?php
namespace ext_bootstrap\componentes\efs;

class bootstrap_ef_multi_seleccion_check extends \toba_ef_multi_seleccion_check
{
	function get_input()
	{
		$estado = $this->get_estado_para_input();
		$html = "";
		$i = 0;
		$tab = $this->padre->get_tab_index();
		$input_extra = " tabindex='$tab'";

		if ($this->mostrar_utilidades && !$this->es_solo_lectura())	{
			$html .= "
			<div id='{$this->id_form}_utilerias' class='ef-multi-sel-todos'>
			<a href=\"javascript:{$this->objeto_js()}.seleccionar_todo(true)\">Todos</a> /
			<a href=\"javascript:{$this->objeto_js()}.seleccionar_todo(false)\">Ninguno</a></div>
			";
		}
		$html .= "<div id='{$this->id_form}_opciones' class='{$this->clase_css}'>\n";
		foreach ($this->opciones as $clave => $descripcion) {
			$id = $this->id_form.$i;
			$html .= "<div class='checkbox'>\n";
			$html .= "	<label class='ef-multi-check' for='$id'>";
			$ok = in_array($clave, $estado);
			if (! $this->permitir_html) {
				$clave = texto_plano($clave);
			}
			$checkeado =  $ok ? "checked" : "";
			$solo_lectura = $this->es_solo_lectura()?'disabled':'';
			$html .= "<input name='{$this->id_form}[]' id='$id' type='checkbox' value='$clave' $checkeado class='ef-checkbox' $input_extra $solo_lectura>";
			$input_extra = '';
			if ($this->es_solo_lectura()) {
				if ($ok) {
					$html .= "<input name='{$this->id_form}[]' id='$id' type='hidden' value='$clave'>";
				}
			} 
			if (! $this->permitir_html) {
				$descripcion = texto_plano($descripcion);
			}
			$html .= "	$descripcion</label>\n";
			$html .= "</div>";
			$i++;
		}
		$sobran = $i % $this->cantidad_columnas;
		if ($sobran > 0) {
			$html .= str_repeat("\t<td></td>\n", $sobran);
			$html .= "</tr>\n";
		}
		$html .= "</div>\n";
		$html .= $this->get_html_iconos_utilerias();
		return $html;
	}
}