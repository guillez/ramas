<?php
	$form = toba::memoria()->get_parametro('formulario_habilitado');
	$respuestas_form = toba::memoria()->get_parametro('respondido_formulario');

	//Al invocarse desde la operación de recuperación de la encuesta anónima (por hash) no hay parámetros en la url
	//Se los levanta de los datos de memoria
	if (!isset($form)) {
		$form = toba::memoria()->get_dato('formulario_habilitado');
	}
	if (!isset($respuestas_form)) {
		$respuestas_form = toba::memoria()->get_dato('respondido_formulario');
	}
	
	include_once('nucleo/formulario/vista/builder_internos.php');
	include_once('nucleo/formulario/formulario_controlador_config.php');
	
	$builder = new builder_internos();
	$config = new formulario_controlador_config($form, $respuestas_form);
	$formulario_controlador = new formulario_controlador();
	
	$config->set_vista_builder($builder);
	$config->set_editable(false);
	$config->set_paginada(false);
	
	$formulario_controlador->set_configuracion($config);
	$formulario_controlador->procesar_request();
?>