<?php

namespace ext_bootstrap\componentes\botones;

class bootstrap_tab extends \toba_tab{
	
	
	function get_html($tipo, $id_submit, $id_componente, $seleccionado, $editor='')
	{
		if ( $this->anulado ) 
			return null;
		
		if( ($tipo != 'V') && ($tipo != 'H') )
			throw new \toba_error_def("Los tipos validos de TABS son 'V' y 'H'.");
		
		static $id_tab = 1;
		$evento = $this->datos['identificador'];
		$contenido = ' ';
		$tab_order = \toba_manejador_tabs::instancia()->siguiente();
		
		$tip = $this->datos['ayuda'];
		$acceso = tecla_acceso( $this->datos['etiqueta'] );
		$contenido .= $acceso[0];
		$tecla = $acceso[1];
		if (!isset($tecla)&&($id_tab<10)) $tecla = $id_tab;
		$tip = str_replace("'", "\\'",$tip);
		$acceso = \toba_recurso::ayuda($tecla, $tip);
		$id = $id_submit.'_cambiar_tab_'.$evento;
		$js = !$seleccionado?"onclick=\"{$id_componente}.ir_a_pantalla('$evento');return false;\"":"";
		$js_extra = '';
		$clase_tab = $seleccionado ?'active':'';
		$habilitar = $seleccionado ? 'data-toggle="tab"' : '';//Analizo si es un tab navegable
		$oculto = $this->oculto ? 'style="display: none"' : '';
		
		if( $tipo == 'H' || $tipo == 'V' ) {	//********************* TABs HORIZONTALES **********************
			$html = "<li class='$clase_tab'>$editor";
			$html .= "<a  $habilitar aria-expanded='true' href='#' id='$id' $acceso $js>$contenido</a>";
			$html .= "</li>";
			
			if( ! $seleccionado ) {// -- Tab ACTUAL --
				$html .= $js_extra;
			} 
		} /*else {
			// ********************* TABs VERTICALES ************************
			
			if( $seleccionado ) {// -- Tab ACTUAL --
				$html = "<div class='ci-tabs-v-solapa-sel'><div class='ci-tabs-v-boton-sel'>$editor ";
				$html .= "<div id='$id'>$contenido</div>";
				$html .= "</div></div>";
			} else {
				$clase_extra = '';
				if (! $this->activado) {
					$clase_extra = 'ci-tabs-v-desactivado';
				}
				$oculto = $this->oculto ? "style='display: none'" : '';
				$html = "<div class='ci-tabs-v-solapa $clase_extra' $oculto >$editor ";
				$html .= "<a href='#' id='$id' $clase_extra $acceso $js>$contenido</a>";
				$html .= "</div>";
				$html .= $js_extra;
			}
		}*/
		$id_tab++;
		return $html;
	}
	
}