<?php
	$fila 		= toba::memoria()->get_parametro('fila');
   	$clave_fila = toba_ei_cuadro::recuperar_clave_fila(45000011, $fila);
	
	if (empty($clave_fila)) {
		return;
	}
	
	include_once('nucleo/formulario/formulario_controlador_config.php');
	include_once('nucleo/formulario/vista/builder_pdf_respondido.php');
	
	$encuestado = toba::consulta_php('consultas_usuarios')->get_encuestado_por_usuario($clave_fila['usuario_encuestado']);
	$respondido_form = toba::consulta_php('consultas_formularios')->get_respondido_formulario($encuestado['encuestado'], $clave_fila['formulario']);
	
	$builder = new builder_pdf();
	$config  = new formulario_controlador_config($clave_fila['formulario'], $respondido_form['respondido_formulario']);
	$formulario_controlador = new formulario_controlador();
	
	$config->set_vista_builder($builder);
	$formulario_controlador->set_configuracion($config);
	$formulario_controlador->procesar_request();
?>