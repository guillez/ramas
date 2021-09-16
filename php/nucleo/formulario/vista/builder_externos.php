<?php
include_once("nucleo/formulario/vista/builder_base.php");

class builder_externos extends builder_base
{
	function crear_encabezado_formulario($nombre_form, $texto_preliminar=null, $url_action_post, $puede_guardar){
		$title = $nombre_form; //parametro al header..
		$estilo = $this->plantilla_css;
		include ("nucleo/formulario/vista/header.php");//head-body
		parent::crear_encabezado_formulario($nombre_form, $texto_preliminar, $url_action_post, $puede_guardar);
	}

	public function crear_cierre_formulario()
    {
		parent::crear_cierre_formulario();
		$scripts = acceso_externo::obtener_script_encuesta_cargada();
		echo "$scripts</body></html>";
	}        
}

?>
