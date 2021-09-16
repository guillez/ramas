<?php

class kolla_cp_textarea extends kolla_cp_input
{
	public function __construct($validador, $atributos, $clases)
	{
		parent::__construct($validador, $atributos, $clases);
	}
	
	function get_html($id, $respuestas, $obligatoria, $solo_lectura = false)
	{
		$valor = $respuestas[0]['respuesta_valor'];
		$html = "<textarea id='$id' name='$id'".$this->get_atributos($obligatoria).">$valor</textarea>"; 
		return $html;
	}
	
	public function get_pre_pdf($completar_impreso = true)
	{
	    $salida = '';

	    // Si no es para completar de manera impresa, el "texto libre" va a ocupar solo el espacio que implique su correspondiente respuesta.
	    if ($completar_impreso) {
            $salida = '<td style="border-top: 1px solid #EEEEEE; border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; border-left: 1px solid #EEEEEE;" width="100%" height="150px">';
        } else {
	        $salida = '<td style="border-top: 1px solid #EEEEEE; border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; border-left: 1px solid #EEEEEE;" width="100%">';
        }

		return $salida;
	}
	
	public function get_post_pdf()
	{
		return '</td>';
	}
	
}
?>