<?php
abstract class kolla_logs
{
	protected $parser;
	protected $archivo;
	protected $datos;
	protected $etiqueta_actual;

	protected $array_resultado;

	const etiqueta_estado	= '_ESTADO';
	const etiqueta_info		= '_INFO';
	const etiqueta_fila		= '_FILA';

	function __construct($proceso, $solo_lectura=false)
	{
		$logs_dir = toba::nucleo()->toba_instalacion_dir() . '/logs_procesos_kolla/';
		if (!$solo_lectura) {
			toba_manejador_archivos::crear_arbol_directorios($logs_dir);
		}
		$this->archivo = $logs_dir . $proceso .'.xml';
		$this->ultimo_elemento_leido = 0;
		$this->iniciar_archivo();
		$this->progreso = 0;
		$this->progreso_leido = 0;
		$this->error = false;
		$this->mensajes = array();
	}

	function inicializar_parser()
	{
		$this->parser = xml_parser_create('ISO-8859-1');
		$this->array_resultado = Array();
		$this->fila = 0;
	
		xml_set_object($this->parser, $this);
		
		//Callbacks
		xml_set_element_handler($this->parser, 'handlerInicial', 'handlerFinal');
		xml_set_character_data_handler($this->parser, 'handlerDatos');
	}


	function get()
	{
		// Se abre el archivo XML
		if (!file_exists($this->archivo)) {
			toba::error()->info('Kolla LOGS: No se puede abrir el archivo ' .$this->archivo);
			return false;
		}
		$fich = fopen($this->archivo, 'r');
		// se va leyendo el archivo y parseando
		$this->inicializar_parser();
		while ($data = fread($fich, 512)) {
			$resultado = xml_parse($this->parser, $data, feof($fich));

			// En caso de error
			if (!$resultado) {
				// en caso de error por no cierre de la etiqueta raiz (5) no muestro el error
				if (!xml_get_error_code($this->parser) == 5) {
					toba::error()->info(sprintf('Error de XML: %s en la linea %d',
						xml_error_string(xml_get_error_code($this->parser)),
						xml_get_current_line_number($this->parser)));
					xml_parser_free($this->parser);
				}
			}
		}
		// esta funcion se implementa en cada subclase de kolla_logs
		return $this->obtener_resultados();
	}


	//==================================

	function set($mensaje)
	{
		$this->mensajes[] = $mensaje;
	}

	function iniciar_archivo() /// estaria en la clase padre
	{
		$fich = fopen($this->archivo, 'w+');
		// TODO ver de donde recuperar el encoding (encoding de la base?)
		$encabezado = "<?xml version='1.0' encoding='ISO-8859-1'?>\n";
		fwrite($fich, $encabezado);
		$encabezado = "<resultado>\n";
		fwrite($fich, $encabezado);
		fclose($fich);
	}

	abstract function obtener_resultados();
	
}
?>