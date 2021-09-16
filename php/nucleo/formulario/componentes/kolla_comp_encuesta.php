<?php

abstract class kolla_comp_encuesta
{
	public $validador;
	public $atributos;
	public $clases;
	public $clase_bootstrap;
    public $valor_diferido = null;

	function __construct($validador, $atributos, $clases)
	{
		$this->validador = $validador;
		$this->atributos = $atributos;
		$this->clases    = $clases;
		$this->clase_bootstrap = 'form-control';
	}
	
	abstract function get_html($id, $respuestas, $obligatoria);
	
	function validar_respuesta($respuesta, $es_obligatorio)
	{
		return true;
	}
	
	/**
	 * Genera parte del html con las clases y atributos, mas la validacion
	 * @param unknown $obligatorio
	 * @return string
	 */
	protected function get_atributos($obligatorio)
	{
		$clases = isset($this->validador) ? $this->validador->get_clase_js_validacion() : array();
		$clases = array_merge($clases, $this->clases);
		$atributos_val = ($this->validador != null) ? ' '.$this->validador->get_atributos_js_validacion() : '';
		$clase_input =$this->get_class_css();
		$html= "class=' $clase_input ".implode(' ', $clases)."'";
		
		foreach($this->atributos as $attr=>$val) {
			if (!empty($val)) {
				$html.= " $attr=$val";
			} else {
				$html.= " $attr";
			}
		}
		
		$html.= $atributos_val;
		$html.= $obligatorio ? ' required' : '';
		return $html;
	}

	private function get_class_css()
    {
		if (!$this instanceof kolla_cp_radio && !$this instanceof kolla_cp_list)
			return "form-control";
        
		return "";
	}

	public function get_pre_pdf($completar_impreso = true)
	{
        if (isset($this->valor_diferido)) {
            return '<td height="26" style="border-top: 1px solid #EEEEEE; border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; border-left: 1px solid #EEEEEE;" width="100%">';
        } else {
            return '<td style="border-top: 1px solid #EEEEEE; border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; border-left: 1px solid #EEEEEE;" width="100%">';
        }
	}
	
	public function get_post_pdf()
	{
		return '</td>';
	}
	
	function get_pdf($respuestas, $imprimir_respuestas_completas = false, $respuestas_diferidas = null, $completar_impreso = true)
	{
        $texto = isset($respuestas_diferidas) ? '('.$respuestas_diferidas.') ' : '';
		$texto .= isset($respuestas[0]['respuesta_valor']) ? $respuestas[0]['respuesta_valor'] : '';
                
		return $this->get_pre_pdf($completar_impreso).$texto.$this->get_post_pdf();
	}
    
    public function set_valor_diferido($valor_diferido)
    {
        $this->valor_diferido = $valor_diferido;
    }
	
}
?>