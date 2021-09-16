<?php
namespace ext_bootstrap\componentes\efs;

use ext_bootstrap\componentes\interfaz\bootstrap_form;

class bootstrap_ef_editable_clave extends \toba_ef_editable_clave
{
	protected $clase_css ="form-control" ;

	function get_input()
	{
		$this->input_extra .= $this->get_estilo_visualizacion_pixeles();
		$tab = ' tabindex="'.$this->padre->get_tab_index(2).'"';
		$estado = isset($this->estado)? $this->estado : "";
		$opciones_extra = $this->input_extra . $tab;
		
		
		$js = " onKeyUp=\"{$this->objeto_js()}.runPassword(this.value,'{$this->id_form}');\" ";
		$html = bootstrap_form::password($this->id_form,$estado, $this->maximo, $this->tamano, $this->clase_css,$js. $opciones_extra );
		
		if ($this->confirmar_clave) { //Agrego div para mostrar la 'fortaleza' del pwd y tambien ef para confirmacion
			$opciones_extra .= " placeholder='Ingrese nuevamente la contraseña'";
			$html .= '	<div ">
							<div id="'.$this->id_form.'_text" class="col-md-3 no-padding"></div>
							<div class="col-md-9 no-padding" >
								<div class=" ef-editable-clave-fortaleza"  id="'.$this->id_form.'_bar"></div>							
							</div>
						</div>
					';
			$html .= bootstrap_form::password($this->id_form ."_test", $estado, $this->maximo, $this->tamano, $this->clase_css, $opciones_extra);
		}
		$html .= $this->get_html_iconos_utilerias();
		return $html;
	}
}