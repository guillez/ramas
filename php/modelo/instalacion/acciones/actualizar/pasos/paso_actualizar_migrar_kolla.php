<?php

class paso_actualizar_migrar_kolla extends paso_actualizar_migrar
{
	function cambiar_archivos()
	{
        parent::cambiar_archivos();
        	
		//COPIAR ARCHIVOS DE REPORTES GENERADOS
        $path_reportes_or = $this->path_temp_apl.'/procesos/reportes';
        $path_reportes_dest = $this->path_apl.'/procesos/reportes';
		$archivos = glob($path_reportes_or.'/*.txt');
		
		foreach ($archivos as $origen) {
			//copia el reporte al nuevo directorio
            $destino = str_replace($path_reportes_or, $path_reportes_dest, $origen);
            if (!file_exists($destino)) {
                copy($origen, $destino);
            }
		}
        
        //COPIAR ARCHIVOS JAVASCRIPT DE PERSONALIZACIONES DE ENCUESTAS
        $path_js_or = $this->path_temp_apl.'/www/js/encuestas/';
        $path_js_dest = $this->path_apl.'/www/js/encuestas/';
		$archivos = glob($path_js_or.'/*.js');
		
		foreach ($archivos as $origen) {
			//copia el javascript al nuevo directorio
            $destino = str_replace($path_js_or, $path_js_dest, $origen);
            if (!file_exists($destino)) {
                copy($origen, $destino);
            }
		}
		
		//Se les dan permisos a scripts y carpetas donde escriben procesos
		$path_proyecto = $_SESSION['path_instalacion']; 
		
		$path_proceso  = $path_proyecto.'/aplicacion/php/nucleo/lib/procesos_bk/script_proceso.sh';
		$path_reportes = $path_proyecto.'/aplicacion/procesos/reportes';
		$path_logs	   = $path_proyecto.'/aplicacion/procesos/logs';
		
		chmod($path_proceso, 0744);
		chmod($path_reportes, 0744);
		chmod($path_logs, 0744);
	}
    
    function procesar()
	{
        parent::procesar();
        $instancia = inst::configuracion()->get_nombre_instancia();
        $proyecto = inst::configuracion()->get('proyecto', 'id');
        $ini_cliente = new inst_ini($_SESSION['path_instalacion']."/instalacion/i__$instancia/p__$proyecto/rest/guarani/cliente.ini");
        $conexion = $ini_cliente->get_entradas();
        
        if (!isset($conexion['conexion'])) {
            $datos = array( ';to'            => "https://url.a.proyecto/rest/",
                            ';auth_tipo'     => 'digest',
                            ';auth_usuario'  => 'usuario1',
                            ';auth_password' => 'CAMBIAR');
            $ini_cliente->agregar_entrada('conexion', $datos);
            $ini_cliente->guardar();
        }
    }
    
}
?>