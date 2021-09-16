<?php

class paso_inst_destino_kolla extends paso_instalar_destino
{
	function procesar()
	{
		parent::procesar();
		
		//Se les dan permisos a scripts y carpetas donde escriben procesos
		$path_proyecto = $_SESSION['path_instalacion']; 
		
		$path_proceso  = $path_proyecto.'/aplicacion/php/nucleo/lib/procesos_bk/script_proceso.sh';
		$path_reportes = $path_proyecto.'/aplicacion/procesos/reportes';
		$path_logs	   = $path_proyecto.'/aplicacion/procesos/logs';
		
		chmod($path_proceso, 0744);
		chmod($path_reportes, 0744);
		chmod($path_logs, 0744);
	}
}
?>