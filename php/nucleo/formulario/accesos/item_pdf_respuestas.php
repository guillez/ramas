<?php
	$cuadro_fuente = 200000043;
	$fila 		= toba::memoria()->get_parametro('fila');
   	$clave_fila = toba_ei_cuadro::recuperar_clave_fila($cuadro_fuente, $fila);
	$encuestado = toba::consulta_php('consultas_usuarios')->get_codigo_encuestado(toba::usuario()->get_id());
	require_once 'int_pdf_respuestas.php';
?>