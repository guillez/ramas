<?php
namespace ext_bootstrap\componentes\efs;

use ext_bootstrap\componentes\interfaz\bootstrap_form;

class bootstrap_ef_popup extends \toba_ef_popup
{
	protected $clase_css = 'form-control';
	function get_input()
	{
		$js = '';
		$html = '';
		$tab = $this->padre->get_tab_index();
		$extra = " tabindex='$tab'";
		if(!isset($this->estado)) $this->estado="";
		if (!isset($this->descripcion_estado) || $this->descripcion_estado == '') {
			$this->descripcion_estado = $this->get_descripcion_valor();
		}
	
		$estado = (is_array($this->estado)) ? implode(apex_qs_separador, $this->estado) : $this->estado;
		$html .= "<div class='input-group' >";
		if ($this->cuando_cambia_valor != '') {
			$js = "onchange=\"{$this->get_cuando_cambia_valor()}\"";
		}
	
		$extra .= $this->get_estilo_visualizacion_pixeles();
		$extra .= $this->get_info_placeholder();
		if ($this->editable) {
			$disabled = ($this->es_solo_lectura()) ? "disabled" : "";
			$html .= bootstrap_form::hidden($this->id_form."_desc", $estado);
			$html .= bootstrap_form::text($this->id_form, $this->descripcion_estado, false, "", $this->tamano, $this->clase_css, $extra.' '.$disabled.' '.$js);
			$extra = '';
		} else {
			$html .= bootstrap_form::hidden($this->id_form, $estado, $js);
			$html .= bootstrap_form::text($this->id_form."_desc", $this->descripcion_estado, false, "", $this->tamano, $this->clase_css, " $extra disabled ");
		}
		if (isset($this->id_vinculo)) {
			
			$display = ($this->es_solo_lectura()) ? "visibility:hidden" : "";
			$html .= "<span class='input-group-addon'>";
			$html .= "<a id='{$this->id_form}_vinculo' style='$display' $extra";
			$html .= " onclick=\"{$this->objeto_js()}.abrir_vinculo();\"";
			$html .= " href='#'>".$this->get_imagen_abrir()."</a>";
			$html .= "</span>";
		}
		if ($this->no_oblig_puede_borrar) {
			$display = ($this->es_solo_lectura()) ? "visibility:hidden" : "";
			$html .= "<span class='input-group-addon'>";
			$html .= "<a id='{$this->id_form}_borrar' style='$display' $extra";
			$html .= " onclick=\"{$this->objeto_js()}.set_estado(null, null);\"";
			$html .= " href='#'>".$this->get_imagen_limpiar()."</a>";
			$html .= "</span>";
		}
		$html .= $this->get_html_iconos_utilerias();
		$html .= "</div>\n";
		return $html;
	}
	
	function get_imagen_abrir()
	{
		return "<span class='glyphicon glyphicon-pencil' title='Seleccionar un elemento'></span>";
	}
	
	function get_imagen_limpiar()
	{
		return "<span class='glyphicon glyphicon-erase' title='Borrar Selección'></span>";
	}
	
}