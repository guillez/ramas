<?php
    include_once('nucleo/formulario/formulario_controlador_config.php');
	include_once('nucleo/formulario/vista/builder_pdf.php');
   	
	$fh = toba::memoria()->get_parametro('fh');

	if ( is_null($fh) || empty($fh) ) {
		return;
	}
    
    $config  = new formulario_controlador_config($fh);
    $builder = new builder_pdf();
	$formulario_controlador = new formulario_controlador();
	
	$config->set_vista_builder($builder);
	$formulario_controlador->set_configuracion($config);
	$formulario_controlador->procesar_request();
?>