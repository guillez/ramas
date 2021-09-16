<?php
namespace ext_bootstrap\componentes\efs;

use ext_bootstrap\componentes\interfaz\bootstrap_form;

class bootstrap_ef_editable_fecha_hora extends \toba_ef_editable_fecha_hora
{
	protected $clase_css ="form-control" ;

	function get_input()
	{
		$estado_fecha = (! is_null($this->estado)) ? $this->estado['fecha']: '';
		$estado_hora = (! is_null($this->estado))? $this->estado['hora'] : '';

		$tab = ' tabindex="'.$this->padre->get_tab_index().'"';
		$id_form_fecha = $this->id_form . '_fecha';
		$id_form_hora = $this->id_form . '_hora';
		$html = "<span class='ef-fecha-hora'>";
		$html .= bootstrap_form::text($id_form_fecha ,$estado_fecha, $this->es_solo_lectura(),$this->tamano, $this->tamano, $this->clase_css, $this->input_extra.$tab);
		$visibilidad = "style= 'visibility:hidden;'";
		if (! $this->es_solo_lectura()) {	//Hay que ver si es solo lectura por la cascada o que?
			$visibilidad = "style= 'visibility:visible;'";
		}
		$html .= "<a id='link_". $this->id_form . "' ";
		$html .= " onclick='calendario.select(document.getElementById(\"$id_form_fecha\"),\"link_".$this->id_form."\",\"dd/MM/yyyy\");return false;' ";
		$html .= " href='#' name='link_". $this->id_form . "' $visibilidad>".\toba_recurso::imagen_toba('calendario.gif',true,16,16,"Seleccione la fecha")."</a>\n";

		$html .= bootstrap_form::text($id_form_hora, $estado_hora, $this->es_solo_lectura(), 5,  5, $this->clase_css . '  ef-numero ', $this->input_extra. $tab);
		$html .= $this->get_html_iconos_utilerias();
		$html .= "</span>\n";
		return $html;
	}
}