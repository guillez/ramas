<?php

class kolla_cp_localidad_y_cp extends kolla_comp_encuesta
{
	protected $separador_localidad = '|||';

	public function __construct($validador, $atributos, $falta)
	{
		parent::__construct($validador, $atributos, $falta);
	}
		
	function get_html($id, $respuesta, $obligatoria)
	{
		$valor = $respuesta[0]['respuesta_valor'];
	
		if ($valor != '') {
			$resp = explode($this->separador_localidad, $valor);
			$respuesta_id = $resp[0];
			$res = catalogo::consultar(dao_encuestas::instancia(), 'get_localidad', array($respuesta_id));
			$respuesta_desc = $res[0]['nombre'];
		} else {
			$respuesta_id = null;
			$respuesta_desc = null;
		}
		
		$action = isset($this->atributos['disabled']) ? 'disabled' : "onClick='f_localidad.get_localidad($id, desc_$id)'";
		
		$this->html= "
				<div class='input-group'>
					<input type='hidden' id='$id' name='$id' class='localidad_y_cp' value='$respuesta_id'>
					<input type='text' id='desc_$id' name='desc_$id' class='form-control pertenece_localidad' value='$respuesta_desc' disabled>
					<a href='#form_localidades' id='boton_$id' role='button' class='btn input-group-addon' $action><span class='glyphicon glyphicon-search'></span></a>
				</div>
		";
		
		return $this->html;
	}

	function get_pdf($respuesta, $imprimir_respuestas_completas = false, $respuestas_diferidas = null, $completar_impreso = true)
	{//actualmente nunca se activa la opción de diferidas para este componente 
        //porque al builder solo llega la elegida y no todo el conjunto de opciones
            
		$respuesta_desc	 = isset($respuestas_diferidas) ? '('.$respuestas_diferidas.') ' : '';
		$respuesta_valor = $respuesta[0]['respuesta_valor'];
		
		if ($respuesta_valor != '') {
			$res = catalogo::consultar(dao_encuestas::instancia(), 'get_localidad', array($respuesta_valor));
			$respuesta_desc = $res[0]['nombre'];
		}
		
		return $this->get_pre_pdf($completar_impreso).$respuesta_desc.$this->get_post_pdf();
	}
	
}
?>