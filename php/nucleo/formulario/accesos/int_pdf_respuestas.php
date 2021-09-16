<?php
	if (empty($clave_fila)) {
		throw new toba_error('Error, no existe la fila');
	}
	
	include_once('nucleo/formulario/formulario_controlador_config.php');
	include_once('nucleo/formulario/vista/builder_pdf.php');
	
	$respondido_form = toba::consulta_php('consultas_formularios')->get_respondido_formulario($encuestado, $clave_fila['formulario']);

    $builder = new builder_pdf;
    $builder->set_completar_impreso(false);
	$config  = new formulario_controlador_config($clave_fila['formulario'], $respondido_form['respondido_formulario']);
	$formulario_controlador = new formulario_controlador();
	
	$config->set_vista_builder($builder);
	$formulario_controlador->set_configuracion($config);
	$formulario_controlador->procesar_request();
?>