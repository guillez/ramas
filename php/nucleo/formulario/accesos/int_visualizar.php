<?php
	$id		  = toba::memoria()->get_dato('visualizar_formulario_id');
	$paginada = toba::memoria()->get_dato('visualizar_formulario_paginado');

	if (empty($id)) {
		return;
	}

    $formulario_controlador = new formulario_controlador();
	
	include_once('nucleo/formulario/vista/builder_internos.php');
	$builder = new builder_internos();
		
	$config = new formulario_controlador_config($id);
	$config->set_vista_builder($builder);
	$config->set_editable(true);
	$config->set_paginada($paginada == 1);
	
	$formulario_controlador->set_configuracion($config);
	$formulario_controlador->procesar_request();
?>