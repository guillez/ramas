<?php
class kolla_cp_radio extends kolla_comp_opciones
{
    private static $obligatorio_ = true; //lo doy vacio y que tenga que elegir 1
	
	function __construct($validador, $atributos, $clases)
	{
		parent::__construct($validador, $atributos, $clases);
	}

	function get_pre($id)
	{
	}
	
	function get_post($id)
	{
	}
	
	function get_opcion($id, $clave, $valor, $seleccionada, $visualizacion_horizontal = 'N')
	{
		$checked = ($seleccionada) ? 'checked' : '';
        $html = $visualizacion_horizontal == 'S' ? "<div class='radio col-sm-4'>" : "<div class='radio'>";
        
		$html .= "
				<label>
					<input $checked id = '$id$clave'  name='$id' value='$clave' ".$this->get_atributos($this->obligatorio).">
					$valor
				</label>
			</div>
		";
        
        return $html;
	}
	
}
?>