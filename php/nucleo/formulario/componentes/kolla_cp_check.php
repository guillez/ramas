<?php
/**
 * No reconoce clases y atributos por el momento
 */
class kolla_cp_check extends kolla_comp_opciones
{
	public function __construct($validador, $atributos, $clases)
	{
		parent::__construct($validador, $atributos, $clases);
	}
	
	public function get_pre($id)
	{
		//return "<div id='".$id."' name='".$id."[]' class='ef_checkbox'>";
        //return "<fieldset id'".$id."' class='ef_checkbox'>";
	}
	
	public function get_opcion($id, $clave, $valor, $seleccionada, $visualizacion_horizontal = 'N')
	{
		$checked = ($seleccionada) ? 'checked' : '';
        $required = $this->obligatorio ? 'required' : '';
        $html = $visualizacion_horizontal == 'S' ? "<div class='col-sm-4'>" : "<div>";
        
		$html .= "
					<input id='$id.$clave'	class='ef_checkbox' type='checkbox' $checked name='{$id}[]' value='$clave' $required>$valor
				</div>
				";
        
        return $html;
	}

	public function get_post($id)
	{
        //return '</div>';
		return '</fieldset>';
	}
        
    function get_imagen()
	{
		return '<img src="file://'.toba::proyecto()->get_path().'/www/img/check_off.gif" height="10"/>'; 
	}
	
	function get_imagen_seleccionada()
	{
		return '<img src="file://'.toba::proyecto()->get_path().'/www/img/check_on.gif" height="10" align="left"/>'; 
	}
	
}
?>