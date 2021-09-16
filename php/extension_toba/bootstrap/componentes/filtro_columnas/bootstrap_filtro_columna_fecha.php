<?php
namespace ext_bootstrap\componentes\filtro_columnas;

use ext_bootstrap\componentes\efs\bootstrap_ef_editable_fecha;

class bootstrap_filtro_columna_fecha extends \toba_filtro_columna_fecha{
	
	function ini()
	{
		//--- Par�metros efs
		$parametros = $this->_datos;
		$obligatorio = array($this->_datos['obligatorio'], false);
		$this->_ef = new bootstrap_ef_editable_fecha($this, null, $this->_datos['nombre'], $this->_datos['etiqueta'],
				null, null, $obligatorio, $parametros);
	
		//--- Condiciones
		$this->agregar_condicion('es_igual_a', 		new \toba_filtro_condicion('es igual a',	 	'=', 	'', 	'', 	'::date', 	'::date'));
		$this->agregar_condicion('es_distinto_de', 	new \toba_filtro_condicion_negativa('es distinto de', '!=',	'', 	'', 	'::date', 	'::date'));
		$this->agregar_condicion('desde', 			new \toba_filtro_condicion('desde', 			'>=', 	'', 	'', 	'::date', 	'::date'));
		$this->agregar_condicion('hasta', 			new \toba_filtro_condicion('hasta', 			'<=', 	'', 	'', 	'::date', 	'::date'));
	
		//Condicion entre
		$this->agregar_condicion('entre', 			new \toba_filtro_condicion_entre('::date', 	'::date'));
	}
	
	
	function get_html_condicion()
	{
		$class="form-control";
		$html = '';
		if (count($this->_condiciones) > 1) {
			//-- Si tiene mas de una condicion se muestran con un combo
			$onchange = "{$this->get_objeto_js()}.cambio_condicion(\"{$this->get_nombre()}\");";
			if ($this->hay_condicion_default() && (!isset($this->_estado['condicion']) || is_null($this->_estado['condicion']))){
				//Si no tiene estado y hay default seteado, el default es el nuevo estado
				$this->_estado['condicion'] = $this->_condicion_default;
			}
			if ($this->_solo_lectura || $this->hay_condicion_fija()) {
				$id = $this->_id_form_cond.'_disabled';
				$disabled = 'disabled';
				$html .= "<input class='$class' type='hidden' id='{$this->_id_form_cond}' name='{$this->_id_form_cond}' value='{$this->_estado['condicion']}'/>\n";
			} else {
				$disabled = '';
				$id = $this->_id_form_cond;
			}
			$html .= "<select class='$class' id='$id' name='$id' $disabled onchange='$onchange'>";
			foreach ($this->_condiciones as $id => $condicion) {
				$selected = '';
				if (isset($this->_estado) && $this->_estado['condicion'] == $id) {
					$selected = 'selected';
				}
				$html .= "<option value='$id' $selected>".$condicion->get_etiqueta()."</option>\n";
			}
			$html .= '</select>';
	
			return $html;
		} else {
			reset($this->_condiciones);
			$condicion = key($this->_condiciones);
			//-- Si tiene una unica, seria redundante mostrarle la unica opci�n, se pone un hidden
			$html = "<input class='$class' type='hidden' id='{$this->_id_form_cond}' name='{$this->_id_form_cond}' value='$condicion'/>&nbsp;";
		}
		$html .= "<div id='{$this->_id_form_cond}_label_extra' style='display:none'>";
		$html .= '<br>y</div>';
		return $html;
	
	}
}