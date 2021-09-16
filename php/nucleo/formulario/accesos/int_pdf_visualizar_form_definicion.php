<?php
   	include_once('nucleo/formulario/vista/builder_pdf.php');
   	include_once('nucleo/formulario/accesos/config_visualizar_form_definicion.php');
   	
	$fila 		= toba::memoria()->get_parametro('fila');
   	$clave_fila = toba_ei_cuadro::recuperar_clave_fila(38000848, $fila);

	if (empty($clave_fila)) {
		return;
	}
	
	$config 				= new config_visualizar_form_definicion($clave_fila['formulario']);
    $builder 				= new builder_pdf();
	$formulario_controlador = new formulario_controlador();
	
	$config->set_vista_builder($builder);
	$formulario_controlador->set_configuracion($config);
	$formulario_controlador->procesar_request();
?>