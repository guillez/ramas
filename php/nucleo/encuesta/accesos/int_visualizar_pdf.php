<?php
	include_once("nucleo/formulario/vista/builder_pdf.php");
	include_once("nucleo/formulario/accesos/config_visualizar_encuesta.php");
	
	$fila	   = toba::memoria()->get_parametro('fila');
   	$seleccion = toba_ei_cuadro::recuperar_clave_fila('45000038', $fila);
	
	if (empty($seleccion)) {
		return;
	}
	
	$config 				= new config_visualizar_encuesta($seleccion['encuesta']);
    $builder 				= new builder_pdf();
	$formulario_controlador = new formulario_controlador();
	
	$config->set_vista_builder($builder);
	$formulario_controlador->set_configuracion($config);
	$formulario_controlador->procesar_request();
?>