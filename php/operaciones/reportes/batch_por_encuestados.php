<?php

global $argv;

	//------------------------------------------------------------------------------
	//-- Generación de reporte.
	//------------------------------------------------------------------------------
	$path_logs = toba::proyecto()->get_path() . "/procesos/logs/"; 	
	$path_txts = toba::proyecto()->get_path() . "/procesos/reportes/";	

	$id_reporte = $argv[8];
	$estado_proceso = $path_logs."estado_proceso_".$id_reporte.".log";
	
	//obtener datos del filtro segun codigo de exportacion
	$r = toba::consulta_php('consultas_reportes')->obtener_filtros($id_reporte);
	$filtro = $r[0];
	if (isset($filtro)) 
	{	$reportes = new reportes_kolla();
		if ($filtro['multiples']) {	//Reporte con preguntas de respuesta multiple, en txt
			$contenido = $reportes->reporte_por_encuestado_con_multiples_txt($filtro, $estado_proceso);	
		} else { //Reporte sin preguntas de respuesta multiple, en txt
			//$contenido = $reportes->reporte_por_encuestado_sin_multiples_txt($filtro, $estado_proceso);
            $contenido = $reportes->generar_reporte_encuestado();
            
		}
			
		$nombre = date('Ymd-Hi').'_por_encuestado_e'.$filtro['encuesta'].'_h'.$filtro['habilitacion'].'_i'.$filtro['inconclusas'].'_m'.$filtro['multiples'];
		$file = $path_txts.$nombre.'.txt';
		toba::consulta_php('consultas_reportes')->guardar_nombre_archivo($id_reporte, $nombre);
		
		if (!file_exists($file)) {
			file_put_contents($file ,$contenido);
		} 
		file_put_contents($estado_proceso , file_get_contents($estado_proceso)."Proceso Terminado!");		
	} else {
		file_put_contents($estado_proceso , "Faltan los parámetros para ejecutar el proceso.");
	}
?>