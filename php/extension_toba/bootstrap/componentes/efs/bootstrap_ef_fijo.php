<?php
namespace ext_bootstrap\componentes\efs;


class bootstrap_ef_fijo extends \toba_ef_fijo
{
	function get_input()
	{
		$estado = (isset($this->estado)) ? $this->estado : null;
		if (! $this->permitir_html) {
			$estado = texto_plano($estado);
		}
		
		if ( $this->get_fila_actual() === '' ){ // No estoy dentro de una tabla
			$html = "<p class='form-control-static {$this->clase_css}' id='{$this->id_form}'>".$estado."</p>";}
		else{
			$html = "<div class='{$this->clase_css}' id='{$this->id_form}'>".$estado."</div>";
		}
		$html .= $this->get_html_iconos_utilerias();
		return $html;
	}


}
