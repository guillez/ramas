<?php

	chdir(toba::instalacion()->get_path() . '/php/3ros/ezpdf');
	include('class.ezpdf.php');
	
	$pdf = new Cezpdf('a4','portrait');
	$pdf->selectFont('./fonts/Helvetica.afm');
	$pdf->ezImage('/www/img/logo-kolla.png', 0, 250, 'none', 'left');
	$fecha = date('d/m/Y H:i:s');
	$pdf->ezText("Fecha: $fecha",10,array('justification' => 'right'));
	
	$options = array(
			'showLines'=> 2,
			'showHeadings' => 1,
			'shaded'=> 1,
			'shaded'=> 0,
			'fontSize' => 10,
			'titleFontSize' => 14,
			'innerLineThickness'=>-1,
			'outerLineThickness'=>2,
			'rowGap' => 4 ,
			'colGap' => 5 ,
			'splitRows'=>0,
			'xPos' => 'center',
			'xOrientation' => 'center',
			'width'=>500,
			'maxWidth'=>500
	);
	
	$encuesta = toba::memoria()->get_dato('nombre_encuesta');
	$encuestado = toba::memoria()->get_dato('encuestado');
	$datos = toba::memoria()->get_dato('info_resultados');
	
	$pdf->ezText("Cargada por: ".$encuestado,10, array('justification' => 'right'));
	
	$pdf->ezText("\n".$encuesta."\n",16, array('justification' => 'center'));
	$cols = array ('pregunta' => "Pregunta", 'respuesta' => "Respuestas");
		
	foreach($datos as $b)
	{
		$nombre_bloque = key($b);
		$bloque = $b[$nombre_bloque]; 
		$tabla_bloque = array();
		foreach($bloque as $preg)
		{
			if ($preg['componente'] != 'label')
			{
				array_push($tabla_bloque, array('pregunta'=> $preg['nombre'], 'respuesta' => $preg['respuesta']));
			}
			else
			{
				array_push($tabla_bloque, array('pregunta'=> "<i><b>".$preg['nombre']."</b></i>", 
											'respuesta' => "------------------------------"));
			}
		}
		$pdf->ezTable($tabla_bloque, $cols, $nombre_bloque, $options);
		$pdf->ezText("\n",'', array('justification' => 'left'));
	}
	
	toba::memoria()->eliminar_dato('info_resultados');
	$nombre = $fecha."--".$encuestado;
	
	$resultados = $pdf->ezOutput();
	header('Cache-Control: private');
	header('Content-type: application/pdf');
	header('Content-Length: '.strlen(ltrim($resultados)));
	header('Content-Disposition: attachment; filename="'.$nombre.'.pdf"');
	header('Pragma: no-cache');
	header('Expires: 0');

	echo $resultados;
	
	exit;
	
?>