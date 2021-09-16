<?php

namespace ext_bootstrap\componentes\efs;

use ext_bootstrap\componentes\interfaz\bootstrap_form;

class bootstrap_ef_editable_captcha extends \toba_ef_editable_captcha
{
	protected $clase_css = 'form-control';
	function get_input()
	{
		$this->input_extra .= $this->get_estilo_visualizacion_pixeles();
		$this->input_extra .= $this->get_info_placeholder();
		$this->generar_texto_aleatorio();
		\toba::memoria()->set_dato_operacion('texto-captcha', $this->texto);
		\toba::memoria()->set_dato_operacion('tamanio-texto-captcha', $this->longitud);
	
		$this->estado  = false;
		$longitud = strlen($this->texto); //la longitud maxima de caracteres del ef
		$tab = ' tabindex="'.$this->padre->get_tab_index().'"';
		$text_input  = bootstrap_form::text($this->id_form, $this->estado, $this->es_solo_lectura(), $longitud, $this->tamano, $this->clase_css, $this->javascript.' '.$this->input_extra.$tab);
		$url = \toba::vinculador()->get_url(null, null, array(), array('servicio' => 'mostrar_captchas_efs', 'objetos_destino' => array( $this->padre->get_id() )));
	
		if ($this->permite_refrescar_codigo) {
			$url_refrescar = \toba::vinculador()->get_url(null, null, array('refrescar' => 1), array('servicio' => 'mostrar_captchas_efs', 'objetos_destino' => array( $this->padre->get_id() )));
			$js = "\"document.getElementById('{$this->id}-captcha').src = '$url_refrescar' + Math.random(); return false;\"";
			$img_refrescar = \toba_recurso::imagen_toba('refrescar.png');
			$refrescar = "<a href='#' onclick=$js><img src='$img_refrescar' alt='Refrescar código de imágen' title='Refrescar código de imágen' /></a>";
		} else {
			$refrescar = '';
		}
	
		//-- TODO: si alguien tiene ganas... metele que son pasteles!!!
		if ($this->permite_generar_audio) {
			$audio = '';
		} else {
			$audio = '';
		}
			
		$input = "<div>
		<div align='absmiddle' class='{$this->css_captcha}'>
		<img id='{$this->id}-captcha' src='$url' /> $refrescar $audio
		</div>
		$text_input
		</div>";
	
		$input .= $this->get_html_iconos_utilerias();
	
		return $input;
	}
}