<?php

class kolla_migrador
{
    protected $desde;
    protected $hasta;
    protected $db;
    protected $interface;
    
    function __construct($db)
    {
        $this->db = $db;
        $this->db->set_schema('kolla');
    }
    
    function set_interface($interface)
    {
        $this->interface = $interface;
    }
    
    function get_db()
    {
        return $this->db;
    }

    function migrar(toba_version $desde, toba_version $hasta)
    {
        $base = new toba_version('3.5.2');
        
        if ($desde->es_mayor_igual($base)) {
            $this->desde = $desde;
            $this->hasta = $hasta;
            $versiones = $this->get_secuencia_migraciones();
            
            $this->get_db()->ejecutar('SET CONSTRAINTS ALL IMMEDIATE;');
            
            foreach ($versiones as $version) {
                $nombre_clase = 'kolla_migracion_'.$version->get_string_partes();
                require_once(dirname(__FILE__).'/'.'kolla_migracion.php');
                require_once(dirname(__FILE__).'/'.$nombre_clase.'.php');
                $migracion = new $nombre_clase($this->get_db(), $this->interface);
                $this->interface->mensaje("Versión a aplicar: ".$version);
                $migracion->set_interface($this->interface);
                $migracion->ejecutar_metodos_negocio();
            }
            
            $this->get_db()->ejecutar('SET CONSTRAINTS ALL DEFERRED;');
                
            if ($this->interface) {
                $this->interface->separador();
                $this->interface->mensaje("Migración existosa!");
                $this->interface->separador();
            }
        } else {
            $mensaje = 'ERROR: Versión mínima requerida para actualizar: 3.5.2';
            if ($this->interface) {
                $this->interface->separador();
                $this->interface->mensaje($mensaje);
                $this->interface->separador();
            } else {
                inst::logger()->error($mensaje);
                throw new inst_error($mensaje);
            }
        }
    }
    
    /**
	 * Retorna todas las migraciones disponibles desde la actual hasta la versión parametro
	 *
	 * @param toba_version $hasta
	 */
	function get_secuencia_migraciones()
	{
        $dir       = dirname(__FILE__);
        $exp       = "/migracion_(.+)\\.php/";
        $archivos  = $this->_get_archivos_migracion($dir, $exp);
		$versiones = array();
		foreach ($archivos as $archivo) {
			$partes = array();
			preg_match($exp, $archivo, $partes);
			$numero = str_replace('_', '.', $partes[1]);
			$version = new toba_version($numero);
			if ($this->desde->es_menor($version) && $this->hasta->es_mayor_igual($version)) {
				$versiones[] = $version;
			}
		}
		usort($versiones, array('toba_version', 'comparar_versiones'));

        if (isset($this->interface)) {
            $this->interface->mensaje("Versiones a migrar:" . implode(",", $versiones));
        }

		return $versiones;
	}
    
    function ejecutar_migracion($version, $metodo_part = null, $usar_transaccion=false)
	{
		$nombre_clase = 'kolla_migracion_'.$version->get_string_partes();
		$archivo = $nombre_clase.'.php';
        require_once('kolla_migracion.php');
		require_once($archivo);
		$migracion = new $nombre_clase($this->get_db(), $this->interface);
		$clase = new ReflectionClass($nombre_clase);
        $prefijo = 'negocio__';
        try {
            if ($usar_transaccion) {
                $this->get_db()->abrir_transaccion();
            }
            if (isset($this->interface)) {
                $this->interface->titulo("Aplicando versión: " . $version->__toString());
            }
            foreach ($clase->getMethods() as $metodo) {
                $nombre_metodo = $metodo->getName();
                $es_metodo = ($nombre_metodo == $prefijo.$metodo_part || (!isset($metodo_part) && strpos($nombre_metodo, $prefijo) === 0));
                if ($es_metodo) {
                    $metodo->invoke($migracion);
                    if ( isset($this->interface) ) {
                        $this->interface->mensaje("Método ejecutado: " . substr($nombre_metodo, 9));
                    }
                }
            }
            if ($usar_transaccion) {
                $this->get_db()->cerrar_transaccion();
            }
        } catch (Exception $e) {
            if ($usar_transaccion) {
                $this->get_db()->abortar_transaccion();
            }
            if ($this->interface) {
                $mensaje = 'Error ejecutando '.$nombre_clase.'::negocio__'.substr($nombre_metodo, 9);
                $this->interface->mensaje($mensaje);
                $this->interface->separador();
                $this->interface->mensaje($e->getMessage());
                $this->interface->separador();
                exit;
            } else {
                $mensaje = "ERROR: problemas ejecutando el método <b>$nombre_metodo</b>.<br><br>
                            <i>{$e->getMessage()}</i><br><br>
                            Por favor genere el diagnóstico y envíelo por correo electrónico.";
                throw new Exception($mensaje);
            }
        }
	}
    
    private function _get_archivos_migracion($directorio, $patron)
    {
        $archivos_ok = array();
        $dir         = opendir($directorio);
        while (false !== ($archivo = readdir($dir))) {
            if ($archivo != ".svn" &&  $archivo != "." && $archivo != "..") {
                $archivos_ok[] = $directorio . '/' . $archivo;
            }
        }
        closedir($dir);
        
        $temp = array();
        foreach($archivos_ok as $archivo) {
            if (preg_match( $patron, $archivo )) {
                $temp[] = $archivo;
            }
        }
        return $temp;
    }
 
    function migrar_desarrollo(toba_version $desde, toba_version $hasta)
    {
        $base = new toba_version('3.5.2');
        
        if ($desde->es_mayor_igual($base)) {
            $this->desde = $desde;
            $this->hasta = $hasta;
            $versiones = $this->get_secuencia_migraciones();
            try {                
                if (empty($versiones)) {
                    //ejecutar solo métodos pendientes
                    $this->actualizar_pendientes($hasta);
                } else {
                    foreach ($versiones as $version) {
                        $this->ejecutar_migracion($version);
                        //actualizar parámetro de tabla
                        $vb = quote($version);
                        $sql = "UPDATE kolla.sge_parametro_configuracion
                                SET valor = $vb 
                                WHERE (seccion = 'actualizacion' AND parametro = 'version_base');";
                        $this->get_db()->ejecutar($sql);
                    }
                }
            } catch (Exception $e) {
                $this->get_db()->abortar_transaccion();
                if ($this->interface) {
                    $this->interface->separador();
                    $this->interface->mensaje("ERROR en la migración!");
                    $this->interface->separador();
                    exit;
                } else {
                    inst::logger()->error($e->getMessage());
                    throw new inst_error($e->getMessage());
                }
            }
            if ($this->interface) {
                $this->interface->separador();
                $this->interface->mensaje("Migración existosa!");
                $this->interface->separador();
            }
        } else {
            $mensaje = 'ERROR: Versión mínima requerida para actualizar: 3.5.2';
            if ($this->interface) {
                $this->interface->separador();
                $this->interface->mensaje($mensaje);
                $this->interface->separador();
            } else {
                inst::logger()->error($mensaje);
                throw new inst_error($mensaje);
            }
        }
    }
    
    function actualizar_pendientes($version) 
    {
        $sql = "SELECT parametro 
                FROM kolla.sge_parametro_configuracion 
                WHERE seccion = 'actualizacion' AND parametro != 'version_base'; ";
        $res = $this->get_db()->consultar($sql);
        $metodos = array();
        foreach ($res as $val) {
            $metodos[] = $val['parametro'];
        }
        
        $nombre_clase = 'kolla_migracion_'.$version->get_string_partes();
        $archivo = $nombre_clase.'.php';
        require_once('kolla_migracion.php');
        require_once($archivo);
        
        $migracion = new $nombre_clase($this->get_db(), $this->interface);
        $clase = new ReflectionClass($nombre_clase);
        $prefijo = 'negocio__';
        try {
            $this->get_db()->abrir_transaccion();
            foreach ($clase->getMethods() as $metodo) {
                $nombre_metodo = $metodo->getName();
                $nro_metodo = str_replace($prefijo, "", $nombre_metodo);
                $es_metodo = ($nombre_metodo == $prefijo.$nro_metodo);
                
                $es_pendiente = ($es_metodo && !in_array($nro_metodo, $metodos));
                
                if ($es_pendiente) {
                    $metodo->invoke($migracion);
                     $sql = "INSERT INTO kolla.sge_parametro_configuracion(
                            seccion, parametro, valor)
                            VALUES ('actualizacion', ".quote($nro_metodo).", 'ok');";
                    $this->get_db()->ejecutar($sql);
                    if (isset($this->interface)) {
                        $this->interface->mensaje("Método ejecutado: " . substr($nombre_metodo, 9));
                    }
                }
                else {
                    if ($es_metodo && isset($this->interface)) {
                        $this->interface->mensaje("Método ignorado: " . substr($nombre_metodo, 9));
                    }
                }
                
            }
            $this->get_db()->cerrar_transaccion();
        } catch (Exception $e) {
            $this->get_db()->abortar_transaccion();
            if ($this->interface) {
                $mensaje = 'Error ejecutando '.$nombre_clase.'::negocio__'.substr($nombre_metodo, 9);
                $this->interface->mensaje($mensaje);
                $this->interface->separador();
                $this->interface->mensaje($e->getMessage());
                $this->interface->separador();
                exit;
            } else {
                $mensaje = "ERROR: problemas ejecutando el método <b>$nombre_metodo</b>.<br><br>
                            <i>{$e->getMessage()}</i><br><br>.";
                throw new Exception($mensaje);
            }
        }
    }
    
    function _max_migracion_disponible() 
    {
        $this->desde =  new toba_version('3.5.2');
        $this->hasta = new toba_version('100.0.0');//un maxint de versiones
        $versiones = $this->get_secuencia_migraciones();
        
        return $versiones[count($versiones)-1];
    }
}