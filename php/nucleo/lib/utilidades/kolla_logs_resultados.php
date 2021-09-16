<?php

class kolla_logs_resultados extends kolla_logs
{
	protected $fila;
	protected $reporte_pedido;
	protected $reportes = array();

	function obtener_resultados()
	{
		$resultado['mensajes'] = $this->array_resultado;
		return $resultado;
	}

	function get($reporte)
	{
		$this->reporte_pedido = $reporte;
		return parent::get();
	}

	/**
	* handlerDatos
	* Esta funcion se invoca al encontrar una etiqueta
	*/
	function handlerInicial($parser, $nombre, $atributos)
	{
		$this->etiqueta_actual = $nombre;
		if ($this->etiqueta_actual == self::etiqueta_fila) {
			$this->reportes = explode(',', $atributos['REPORTES']);
		}
	}

	/**
	* handlerDatos
	* Esta funcion se invoca al encontrar datos "el contenido que hay entre etiquetas"
	* <etiqueta>contenido</etiqueta>
	*/
	function handlerDatos($parser, $dato)
	{
		if ($this->etiqueta_actual != kolla_logs_estados::etiqueta_estado && (in_array($this->reporte_pedido, $this->reportes))) {
			if ($dato == trim($dato)) {
				// Esto se hace porque el xml parser cuando encuentra el primer caracter especial como acento, hace 2 llamadas por lo tanto debo concatenar
				if (!isset($this->array_resultado[$this->fila][strtolower($this->etiqueta_actual)])) {
					$this->array_resultado[$this->fila][strtolower($this->etiqueta_actual)] ='';
				}
				$this->array_resultado[$this->fila][strtolower($this->etiqueta_actual)] = $this->array_resultado[$this->fila][strtolower($this->etiqueta_actual)].$dato;
			} else  {
				// si no encuentra dato setea en vacio
				if (!isset($this->array_resultado[$this->fila][strtolower($this->etiqueta_actual)])) {
					$this->array_resultado[$this->fila][strtolower($this->etiqueta_actual)] = '';
				}
			}
		}
	}

	/**
	* handlerFinal
	* Esta funcion se invoca al cierre de una etiqueta
	*/
	function handlerFinal($parser, $nombre)
	{
		if ($nombre == kolla_logs::etiqueta_fila) {
			$this->fila = $this->fila + 1;
			$this->reportes = array();
		}
	}

	//==============================================

	/**
	 * Acumula en $this->mensajes, el $mensaje que se utilizaran en los reportes $reportes
	 * 
	 * @param <type> $mensaje
	 * @param <type> $reportes 
	 */
	function set($mensaje, $reportes)
	{
		$array['mensaje'] = $mensaje;
		$array['reportes'] = $reportes;
		$this->mensajes[] = $array;
	}

	function guardar()
	{
		if (isset($this->mensajes) && is_array($this->mensajes))
		{
			foreach ($this->mensajes as $mensaje) {
				// Se abre el archivo XML
				$etiqueta = '';
				if (!file_exists($this->archivo)) {
					toba::error()->info('Kolla LOGS: No se puede abrir el archivo ' .$this->archivo);
					return false;
				}
				$fich = fopen($this->archivo, 'a');

				$etiqueta .= "\t<". kolla_logs::etiqueta_fila ." reportes='" . implode(',', $mensaje['reportes']) ."'>\n";
				foreach ($mensaje['mensaje'] as $tag => $valor) {
					$etiqueta .= "\t\t<$tag>$valor</$tag>\n";
				}
				$etiqueta .= "\t</". kolla_logs::etiqueta_fila .">\n";
				fwrite($fich, $etiqueta);
				fclose($fich);
			}
			unset($this->mensajes);
		}
	}
}
?>