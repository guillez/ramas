<?php
namespace ext_bootstrap\componentes\botones;

use ext_bootstrap\componentes\interfaz\bootstrap_form;


class bootstrap_evento_usuario extends \toba_evento_usuario
{
	/**
	 * @
	 * {@inheritDoc}
	 * @see toba_evento_usuario::get_html()
	 */
	function get_html($id_submit, $objeto_js, $id_componente)
	{
		if ( $this->anulado ) return null;
		$tab_order = \toba_manejador_tabs::instancia()->siguiente();
		$tip = '';
		if (isset($this->datos['ayuda'])) {
			$tip = $this->datos['ayuda'];
		}

		$acceso = tecla_acceso( $this->datos['etiqueta'] );
		if (! $this->es_seleccion_multiple()) {
				
			$clase = $this->get_clase_css();
			$tipo_boton = 'button';
				
			if ( !$this->esta_sobre_fila() && isset($this->datos['defecto']) && $this->datos['defecto']) {
				$tipo_boton = 'submit';
				$clase .=  '  ei-boton-defecto';
			}
			$estilo_inline = $this->oculto ? 'display: none' : null;
			$html = '';
			$html .= $this->get_imagen();
			$html .= $acceso[0];
			$tecla = $acceso[1];
			$js = $this->get_invocacion_js($objeto_js, $id_componente);
			if (isset($js)) {
				$js = $this->activado? 'onclick="'.$js.'"':'';
				if($this->esta_sobre_fila()){
					return bootstrap_form::link_html( $id_submit."_".$this->get_id(), $html, $js, $tab_order, $tecla,
							$tip, $tipo_boton, '', $clase, true, $estilo_inline, $this->activado);
				}

                if (strstr($clase, "sticky-button")) {
                    //return bootstrap_form::sticky_button_html( $id_submit."_".$this->get_id(), $html, $js, $tab_order, $tecla,
                    //    $tip, $tipo_boton, '', $clase, true, $estilo_inline, $this->activado);
                    return bootstrap_form::enhanced_red_sticky_button_html( $id_submit."_".$this->get_id(), $html, $js, $tab_order, $tecla,
                        $tip, $tipo_boton, '', $clase, true, $estilo_inline, $this->activado);
                } else {
                    return bootstrap_form::button_html( $id_submit."_".$this->get_id(), $html, $js, $tab_order, $tecla,
                        $tip, $tipo_boton, '', $clase, true, $estilo_inline, $this->activado);
                }
			}
		} else {
			$js = $this->get_invocacion_js($objeto_js, $id_componente);
			$html = '<label>';
			$html .= $this->get_imagen();
			if (isset($js)) {
				$extra = 'onclick="'.$js.'"';
				$extra .= " title='$tip'";
				$extra .= $this->activado ? '' : ' disabled';
				$valor_actual = ($this->es_check_activo) ? $this->parametros : null;
				$html .= bootstrap_form::checkbox($id_submit."_".$this->get_id(), $valor_actual, $this->parametros, '', $extra);
			}
			$html .= '</label>';
			return $html;
		}
	}

	function get_clase_css(){
		// Si esta dentro de una celda, los alineo a derecha por una mejor visualización
		$clase_predeterminada = $this->esta_sobre_fila() ? '' : 'btn btn-default';
		
		if(!$this->activado)
			$clase_predeterminada .= " btn-disabled";

		if (isset($this->datos['estilo']) && (trim( $this->datos['estilo'] ) == "") )
			return $clase_predeterminada;


		$estilo_definido = $this->datos['estilo'];

		$es_icono = strpos($estilo_definido, 'glyphicon-')!== false || strpos($estilo_definido, 'fa-')!== false; // se esta definiendo un icono a través del estilo
		if ( $this->esta_sobre_fila() ){
				
			$base = strpos($estilo_definido, 'glyphicon-')!== false ? 'glyphicon':'fa'; // es un icono de bootstrap o del template?
			if(!$this->activado)
				$base .= " btn-disabled";
			return "$base  $estilo_definido"; //Se inserta posteriomente un link
		}
		return "$clase_predeterminada $estilo_definido";

	}
	
	function get_imagen()
	{

		if (isset($this->datos['imagen']) && $this->datos['imagen'] != '') {
			
			$estilo_definido = $this->datos['imagen'];
			$es_icono = strpos($estilo_definido, 'glyphicon-')!== false || strpos($estilo_definido, 'fa-')!== false; //es icono definido por estilo
			
			if(!$this->esta_sobre_fila() && $es_icono){
				$base = strpos($this->datos['imagen'], 'glyphicon-')!== false ? 'glyphicon':'fa';
				return "<span class='$base $estilo_definido'></span>  ";
			}
			
			if (isset($this->datos['imagen_recurso_origen'])) {
				$img = \toba_recurso::imagen_de_origen($this->datos['imagen'], $this->datos['imagen_recurso_origen']);
				return \toba_recurso::imagen($img, null, null, null, null, null, 'vertical-align: middle;').' ';
			} else {
				\toba::logger()->warning("No se especifico el origen de la imagen '{$this->datos['imagen']}' del botón");
			}
		}
		
	}

}