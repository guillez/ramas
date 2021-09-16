<?php
namespace ext_bootstrap\componentes\efs;

use ext_bootstrap\componentes\interfaz\bootstrap_form;

class bootstrap_ef_upload extends \toba_ef_upload
{
	function get_input()
	{
		$tab = $this->padre->get_tab_index();
		$extra = " tabindex='$tab'";
		$estado = $this->get_estado_input();
		//--- Se puede cargar con el nombre del archivo o el arreglo que php brinda
		//--- al hacer el upload
		if (is_array($estado)) {
			$nombre_archivo = isset($estado['name']) ? $estado['name'] : current($estado);
		} else {
			$nombre_archivo = $estado;
		}
		//-- Si hay un archivo lo deja marcado en sesion para la etapa siguiente
		if (isset($nombre_archivo) && trim($nombre_archivo) != '') {
			if (! $this->permitir_html) {
				$nombre_archivo = texto_plano($nombre_archivo);
			}
			\toba::memoria()->set_dato_sincronizado($this->id_form."_cargado", true);
		}
		$salida = "";
		if (! $this->es_solo_lectura()) {
			if (isset($nombre_archivo) && $nombre_archivo != '') {
				$salida .= bootstrap_form::archivo($this->id_form, null, $this->clase_css, "style='display:none'");
				$salida .= "<div id='{$this->id_form}_desicion' class='ef-upload-desc'>". $nombre_archivo . "</div>";
				$salida .= bootstrap_form::checkbox("{$this->id_form}_check", null, 1, 'ef-checkbox', "$extra onclick=\"{$this->objeto_js()}.set_editable()\"");
				$salida .= "<label for='{$this->id_form}_check'>Cambiar el Archivo</label>";
			} else {
				$salida = bootstrap_form::archivo($this->id_form, null, $this->clase_css, $extra);
				$salida .= bootstrap_form::checkbox("{$this->id_form}_check", 1, 1, 'ef-checkbox', "style='display:none'");
			}
		} else { // En modo sólo lectura
			if (isset($nombre_archivo) && $nombre_archivo != '') {
				$salida = "<div class='ef-upload-desc'>". $nombre_archivo ."</div>";
			} else {
				$salida = bootstrap_form::archivo($this->id_form, null, $this->clase_css, "disabled='disabled'");
			}
		}
		$salida .= $this->get_html_iconos_utilerias();
		return $salida;
	}
}