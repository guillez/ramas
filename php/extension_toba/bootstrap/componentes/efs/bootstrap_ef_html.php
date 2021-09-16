<?php
namespace ext_bootstrap\componentes\efs;

class bootstrap_ef_html extends \toba_ef_html
{
	/*
	function get_consumo_javascript(){
		$consumo = parent::get_consumo_javascript();
		$consumo[] = '../../'.\toba_recurso::url_proyecto(). '/bt-assets/js/editor';
		return $consumo;
	}
	function get_input()
	{
		if(isset($this->estado)){
			$estado = $this->estado;
		}else{
			$estado = "";
		}
		if ($this->es_solo_lectura()) {
			$html = "<div class='ef-html'>$estado</div>";
		} else {
			$html = $this->get_editor($estado);
		}
		return $html;
	}
	
	function get_editor($valor)
	{
		echo \toba_recurso::link_css('../../'.\toba_recurso::url_proyecto(). '/bt-assets/css/editor');
		return "<textarea id='{$this->id_form}'></textarea>
		<script>
		$(document).ready(function() {
			$('#{$this->id_form}').Editor();
		});
		</script>
	
	";
	}*/
	
}

