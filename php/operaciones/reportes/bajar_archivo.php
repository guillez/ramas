<?php

	$path_reportes = toba::proyecto()->get_path().'/procesos/reportes/';
	$nombre = toba::memoria()->get_dato('nombre_archivo');
	$resultados = file_get_contents($path_reportes.$nombre.'.txt');
	
	if (!empty($resultados)) {
		header('Cache-Control: private');
		header('Content-type: application/text/plain');
		header('Content-Length: '.strlen(ltrim($resultados)));
		header('Content-Disposition: attachment; filename="'.$nombre.'.txt"');
		header('Pragma: no-cache');
		header('Expires: 0');
		echo $resultados;
	}
	
	exit;
	
?>