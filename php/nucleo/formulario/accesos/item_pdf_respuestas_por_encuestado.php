<?php
	$cuadro_fuente = 45000011;
	$fila 		= toba::memoria()->get_parametro('fila');
   	$clave_fila = toba_ei_cuadro::recuperar_clave_fila($cuadro_fuente, $fila);
	$datos_encuestado = toba::consulta_php('consultas_usuarios')->get_encuestado_por_usuario($clave_fila['usuario_encuestado']);
	$encuestado = $datos_encuestado['encuestado'];
	require_once 'int_pdf_respuestas.php';
?>