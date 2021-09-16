<?php

require_once('conversor.php');

/**
 *	Factory que utiza el instalador/actualizador para crear o migrar la base de negocios
 */
class kolla_manejador_instalacion extends manejador_negocio
{
    
	function migrar_negocio($version, $es_base_nueva)
	{
		$conversion = new conversor($this->conexion, $this->logger);
        // La versión vieja del sistema la saco de get_info_instalacion
        $inst = inst::configuracion()->get_info_instalacion();
        $desde = new inst_version($inst['sistema']['version_actual']);
        // La versión que se acaba de copiar es la nueva y puedo sacar la info de proyecto.ini
        $hasta = new inst_version($version);
        $conversion->set_path_inst($inst['sistema']['path']);
		$conversion->migrar($desde, $hasta, false);
	}

	function crear_negocio($version, $grupo_datos)
    {
        $conversion = new conversor($this->conexion, $this->logger);
        $conversion->set_schema(inst::configuracion()->get('base', 'schema'));
        $conversion->convertir(array('319'), false);
    }
	
	function migrar_codigo($version, $desde, $hacia)
    {
        $inst  = inst::configuracion()->get_info_instalacion(); 
        $desde = $inst['sistema']['version_actual'];
        $path  = $inst['sistema']['path'];
        $path_reportes_origen  = "$path.$desde.backup/procesos/reportes";
        $path_reportes_destino = "$path/procesos/reportes";

		$archivos = glob($path_reportes_origen. '/*.txt');
		foreach ($archivos as $origen) {
			//copiar $rep al nuevo directorio
            $destino = str_replace($path_reportes_origen, $path_reportes_destino, $origen);
            copy($origen, $destino);
		}
    }

	function post_instalacion($es_base_nueva)
    {
        if ( $es_base_nueva ) {
            // Esto se hace acá porque el instalador primero crea la base de negocios y luego la instancia de Toba
            $conversion = new conversor($this->conexion, $this->logger);
            $conversion->set_schema(inst::configuracion()->get('base', 'schema'));
            $conversion->convertir(array('316'), false);
        }
    }

	function pre_actualizacion($version, $path_aplicacion){}

	function post_actualizacion($version, $path_aplicacion){}

}