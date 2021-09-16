<?php
namespace  ext_bootstrap\componentes\filtro_columnas;

use ext_bootstrap\componentes\efs\bootstrap_ef_radio;

class bootstrap_filtro_columna_booleano extends \toba_filtro_columna_booleano
{
	
	function ini()
	{
		//-- Parámetros del ef
		$parametros = $this->_datos;
		if (! isset($parametros['selec_cant_columnas'])) {
			$parametros['selec_cant_columnas'] = 2;
		}
		if (! isset($parametros['estado_defecto'])) {
			$parametros['estado_defecto'] = 1;
		}
		$obligatorio = array($this->_datos['obligatorio'], false);
		$this->_ef = new bootstrap_ef_radio($this, null, $this->_datos['nombre'], $this->_datos['etiqueta'],
				null, null, $obligatorio, $parametros);
	
		$opciones = array();
		$opciones['1'] = 'Sí';
		$opciones['0'] = 'No';
		$this->_ef->set_opciones($opciones);
	
		//--- Condiciones
		$this->agregar_condicion('es_igual_a', 		new \toba_filtro_condicion('es igual a',	 	'=', 	'', 	'', 	'', 	''));
	}
	
	function get_html_condicion()
	{
		$class="form-control";
		if (count($this->_condiciones) > 1) {
			//-- Si tiene mas de una condicion se muestran con un combo
			$onchange = "{$this->get_objeto_js()}.cambio_condicion(\"{$this->get_nombre()}\");";
			$html = '';
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
			//-- Si tiene una unica, seria redundante mostrarle la unica opción, se pone un hidden
			return "<input class='$class' type='hidden' id='{$this->_id_form_cond}' name='{$this->_id_form_cond}' value='$condicion'/>&nbsp;";
		}
	}
}