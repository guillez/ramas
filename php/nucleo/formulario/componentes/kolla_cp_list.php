<?php
class kolla_cp_list extends kolla_comp_opciones
{
	public function __construct($validador, $atributos, $clases)
	{
		parent::__construct($validador, $atributos, $clases);
	}
	
	public function get_pre($id)
	{
		$required = $this->obligatorio ? ' required' : '';
        return "<select multiple='multiple' id='".$id."' name='".$id."[]'  class='form-control ef_multi_seleccion_lista' $required>";
	}
	
	public function get_opcion($id, $clave, $valor, $seleccionada)
	{
		$selected = $seleccionada ? 'selected' : '';
		return "<option value='$clave' $selected ".$this->get_atributos(false).">$valor</option>";
	}

	public function get_post($id)
	{
		return '</select>';
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