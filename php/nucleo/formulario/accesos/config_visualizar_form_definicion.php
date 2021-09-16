<?php

include_once('nucleo/formulario/formulario_controlador_config.php');

class config_visualizar_form_definicion extends formulario_controlador_config
{
	private $texto_preliminar;
	
	
	public function __construct($id_formulario_atributo)
	{
		$this->planilla = $this->obtener_planilla_form_atributo($id_formulario_atributo);
	}
		
	private function obtener_planilla_form_atributo($id_form)
	{
		$f = toba::consulta_php('consultas_formularios')->get_formulario_estructura($id_form);
		
		if (empty($f)) {
			return array();
		}
		$this->texto_preliminar = $f[0]['texto_preliminar'];
		$nombre = $f[0]['nombre'];
		$planilla = array();
		foreach ($f as $fila) {
			$encuesta =	array(
				'nombre_f' => $nombre, 
				'fhd' 	   => null,
				'encuesta' => $fila['encuesta'], 
				'elemento' => " [{$fila['tipo_elemento']}]",
				'orden'	   => $fila['orden']);
			$planilla[] = $encuesta;
		}
		return $planilla;
	}
	
	protected function get_texto_preliminar()
	{
		return $this->texto_preliminar;
	}
	
}
?>