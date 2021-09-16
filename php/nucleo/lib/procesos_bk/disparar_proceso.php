<?php
	
	global $argv;
	$archivo = $argv[6];

	/*
     *  Es disparado por un invocador , (que serializo el objeto "proceso", genero un id, y guardo ese obj con el id en un path $argv[6])
     */
	
	// Busca el archivo en el path que viene en el parametro $argv[6]
	if (!file_exists($archivo)) {
		throw new toba_error("no existe el archivo $archivo, que contiene el proceso");
	}
	$objeto_serializado = file_get_contents($archivo);
	$proceso = unserialize($objeto_serializado);
	$proceso->procesar();

?>