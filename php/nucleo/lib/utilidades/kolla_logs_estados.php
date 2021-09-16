<?php

class kolla_logs_estados extends kolla_logs
{
	// Atributos
	protected $fila;
	protected $ultimo_elemento_leido;
	protected $progreso;
	protected $progreso_leido;
	protected $error = false;
	protected $elemento_numero;
	
	function inicializar_parser()
	{
		$this->elemento_numero = 0;
		parent::inicializar_parser();
	}

	function obtener_resultados()
	{
		$resultado['mensajes']	= $this->array_resultado;
		$resultado['progreso']	= $this->progreso_leido;
		$resultado['error']		= $this->error;
		return $resultado;
	}

	/**
	* handlerDatos
	* Esta funcion se invoca al encontrar una etiqueta
	*/
	function handlerInicial($parser, $nombre, $atributos)
	{
		$this->etiqueta_actual = $nombre;
		$this->elemento_numero++;
		if ($this->elemento_numero > $this->ultimo_elemento_leido) {
			if ($this->etiqueta_actual == self::etiqueta_estado) {
				$this->array_resultado[$this->fila] = $atributos;
			}
			if ($this->etiqueta_actual == self::etiqueta_info) {
				if (isset($atributos['PROGRESO'])) {
					$this->progreso_leido = round($atributos['PROGRESO']);
				} elseif(isset($atributos['ERROR']) && $atributos['ERROR']) {
					$this->error = true;
				}
			}
			
		}
	}

	/**
	* handlerDatos
	* Esta funcion se invoca al encontrar datos "el contenido que hay entre etiquetas"
	* <etiqueta>contenido</etiqueta>
	*/
	function handlerDatos($parser, $dato)
	{
		if ($this->elemento_numero > $this->ultimo_elemento_leido) {
			if ($this->etiqueta_actual == self::etiqueta_estado) {
				if ($dato == trim($dato)) {
					$this->array_resultado[$this->fila]['MENSAJE'] = $dato;
					$this->fila = $this->fila + 1;
				}
			}
			$this->ultimo_elemento_leido++;
		}
	}

	/**
	* handlerFinal
	* Esta funcion se invoca al cierre de una etiqueta
	*/
	function handlerFinal($parser, $nombre)
	{
	
	}

	function guardar()
	{
		if (isset($this->mensajes) && is_array($this->mensajes)) {
			foreach ($this->mensajes as $mensaje) {
				// Se abre el archivo XML
				if (!file_exists($this->archivo)) {
					toba::error()->info('Kolla LOGS: No se puede abrir el archivo ' .$this->archivo);
					return false;
				}
				$fich = fopen($this->archivo, 'a');
				$etiqueta = "\t<". kolla_logs::etiqueta_estado ." tiempo='". $mensaje['tiempo'] . "' nivel='". $mensaje['nivel'] . "'>" . $mensaje['mensaje'] . '</'. kolla_logs::etiqueta_estado .">\n";
				fwrite($fich, $etiqueta);
				fclose($fich);
			}
			unset($this->mensajes);
		}
	}

	function incrementar_progreso($paso)
	{
		// Se abre el archivo XML
		if (!file_exists($this->archivo)) {
			toba::error()->info('Kolla LOGS: No se puede abrir el archivo ' .$this->archivo);
			return false;
		}
		$fich = fopen($this->archivo, 'a');
		$this->progreso = $this->progreso + $paso;
		$encabezado = "\t<". kolla_logs::etiqueta_info ." progreso = '$this->progreso'/>\n";
		fwrite($fich, $encabezado);
		fclose($fich);
	}

	function set_error()
	{
		// Se abre el archivo XML
		if (!file_exists($this->archivo)) {
			toba::error()->info('Kolla LOGS: No se puede abrir el archivo ' .$this->archivo);
			return false;
		}
		$fich = fopen($this->archivo, 'a');
		$encabezado = "\t<". kolla_logs::etiqueta_info ." error= 'true' />\n";
		fwrite($fich, $encabezado);
		fclose($fich);

	}
	
	function set($mensaje, $nivel)
	{
		$array['mensaje'] = $mensaje;
		$array['nivel'] = $nivel;
		$array['tiempo'] = date('d-m-Y H:i:s');
		$this->mensajes[] = $array;
	}
	
}
?>