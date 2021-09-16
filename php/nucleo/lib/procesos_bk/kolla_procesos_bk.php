<?php
/**
 * Catálogo de tipos de procesos
 */
abstract class kolla_procesos_bk
{
	protected $path_proceso_serializado;
	protected $log;
	protected $log_estado;
	protected $reportes;

	function __construct()
	{
		$proceso = get_class($this);
		$this->log = new kolla_logs_resultados($proceso);
		$this->log_estado = new kolla_logs_estados($proceso);
		$this->path_proceso_serializado = toba::proyecto()->get_path_temp() . "/procesos_bk/proc_serializados/".uniqid($proceso."_");
	}
	
	function get_resultado($reporte)
	{
		return $this->log->get($reporte);
	}

	function get_estado()
	{
		$mensajes = $this->log_estado->get();
		if (!empty($mensajes)) {
			return $mensajes;
		} else {
			return array();
		}
	}

	function get_progreso()
	{
		return $this->log_estado->get_progreso();
	}

	function get_path_proceso_serializado()
	{
		return $this->path_proceso_serializado;
	}

}
?>