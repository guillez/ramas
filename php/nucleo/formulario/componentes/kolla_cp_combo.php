<?php
class kolla_cp_combo extends kolla_comp_opciones
{
	protected $existe_seleccion = false;

	function __construct($validador, $atributos, $clases)
	{
		parent::__construct($validador, $atributos, $clases);
	}

	function get_html($id, $respuestas, $obligatoria)
	{
		$this->existe_seleccion = false;
		foreach ($respuestas as $respuesta) {
			$seleccionada = (isset($respuesta['sel']) && $respuesta['sel'] == 'S');
			$this->existe_seleccion |= $seleccionada;
		}
		return parent::get_html($id, $respuestas, $obligatoria);
	}

	public function get_pre($id)
	{
		$html = '';
        
        $seleccion = (!$this->existe_seleccion)?" selected ":"";
        
		$html .= "<option ".$seleccion." value=''></option>";
		
		$att = $this->get_atributos($this->obligatorio);
		return "<select id='".$id."' name='".$id."'  $att> $html";
		
	}

	public function get_opcion($id, $clave, $valor, $seleccionada)
	{
		$selected = $seleccionada ? 'selected' : '';
		return '<option '.$selected.' value="'.$clave.'">'.$valor.'</option>';
	}

	public function get_post($id)
	{
		return '</select>';
	}

}
?>