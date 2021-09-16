<?php
namespace ext_bootstrap\componentes\efs;

use ext_bootstrap\componentes\interfaz\bootstrap_form;

class bootstrap_ef_editable_fecha extends \toba_ef_editable_fecha
{
	protected $clase_css ="form-control" ;
	
	function get_consumo_javascript()
    {
		$consumo = parent::get_consumo_javascript();
/*        $consumo[] = '../../kolla/bt-assets/plugins/datepicker/bootstrap-datepicker';
        $consumo[] = '../../kolla/bt-assets/plugins/datepicker/locales/bootstrap-datepicker.es';
        $consumo[] = '../../kolla/bt-assets/js/efs/datepicker';
 */
        $consumo[] = '../../../..'.\toba_recurso::url_proyecto(). '/bt-assets/plugins/datepicker/bootstrap-datepicker';
        $consumo[] = '../../../..'.\toba_recurso::url_proyecto(). '/bt-assets/plugins/datepicker/locales/bootstrap-datepicker.es';
        $consumo[] = '../../../..'.\toba_recurso::url_proyecto().'/bt-assets/js/efs/datepicker';
        return $consumo;
	}
	
	function get_input()
	{
		$tab = ' tabindex="'.$this->padre->get_tab_index().'"';
		$html = '<div class="input-group" >';
		$html .= bootstrap_form::text($this->id_form,$this->estado, $this->es_solo_lectura(),$this->tamano,
				$this->tamano, $this->clase_css, $this->input_extra.$tab);
		if (!$this->es_solo_lectura()) {
			//Genero el boton del calendario
			//$button  = '<div class="input-group-addon toba-datepicker">';
            $button  = '<div class="input-group-addon">';
			//$button .= "<a id='datepicker_{$this->id_form}' data-provide='datepicker' data-date-format='dd/mm/yyyy' onclick='asociar_datepicker(this)'><span class=' glyphicon glyphicon-calendar'></span></a>";
			$button .= '</div> ';
			$html   .= $button;
		}
		/**
		 * @todo usar helper de Toba
		 */
		echo \toba_recurso::link_css('../../../bt-assets/plugins/datepicker/datepicker3');
		$html .= "</div>";
		$html .= $this->get_html_iconos_utilerias();
		$html .= $this->agregar_script();
		return $html;
	}
	
	/**
	 * @deprecated
	 * @return string
	 */	
	private function agregar_script()
    {
		return "<script type='text/javascript'>
					$('#datepicker_{$this->id_form}').datepicker({
                        autoclose:true,
                        format:'dd/mm/yyyy',
                        language:'es',
                        todayHighlight:true,
					});
					$('#datepicker_{$this->id_form}').on('changeDate', function() {
					    $('#{$this->id_form}').val($('#datepicker_{$this->id_form}').datepicker('getFormattedDate'));
					    $('#{$this->id_form}').focus();
					});
				</script>";
	}

}
