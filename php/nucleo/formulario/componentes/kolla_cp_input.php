<?php

class kolla_cp_input extends kolla_comp_encuesta
{
	function __construct($validador, $atributos, $clases)
	{
		parent::__construct($validador, $atributos, $clases);
	}

	function validar_respuesta($respuestas, $es_obligatorio)
	{
		if (isset($respuestas[0])) {
			$valor = $respuestas[0]['respuesta_valor'];
			if ($valor == '') {
				if ($es_obligatorio) {
					return 'El campo es obligatorio';
				} else {
					return true;
				}
			}
			$rta = $this->validador->validar($valor); //no es vacio, se valida sea o no oblig.
			return $rta;
		} else {
            kolla::logger()->debug('No esta seteado respuestas[0]');
			if ($es_obligatorio) return 'El campo es obligatorio';
                return true;
		} 
	}
	
	function get_html($id, $respuestas, $obligatoria, $solo_lectura = false)
	{
		$valor    = $respuestas[0]['respuesta_valor'];
        $readonly = $solo_lectura ? 'readonly' : '';
		$html = "<input id='$id' name='$id' ". $this->get_atributos($obligatoria)." value='$valor' $readonly>";
		return $html;
	}
	
}
?>