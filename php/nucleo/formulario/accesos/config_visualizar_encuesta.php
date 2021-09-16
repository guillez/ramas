<?php

include_once('nucleo/formulario/formulario_controlador_config.php');

class config_visualizar_encuesta extends formulario_controlador_config
{
	public function __construct($id_encuesta)
	{
		$this->planilla = $this->obtener_planilla_encuesta_simple($id_encuesta);
	}
		
	private function obtener_planilla_encuesta_simple($id_encuesta)
	{
		return array(array('nombre_f' => null,
						   'fhd' 	  => null,
						   'encuesta' => $id_encuesta,
						   'elemento' => null,
						   'orden'	  => 0));
	}
	
}
?>