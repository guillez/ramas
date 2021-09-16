<?php

require_once('cambio.php');

class cambio_339 extends cambio
{
	function get_descripcion()
	{
		return " Cambio 339: Se registra en la tabla sge_ws_conexiones la conexion a Guarani 2.";
	}

	function cambiar()
	{
        $path    = $this->get_path_inst();
        $version = $this->get_version_desde();
        $ini     = "$path.$version.backup/proyecto.ini";
        $config  = parse_ini_file($ini);
        
        $wsurl  = $this->quote($config['ws_url']);
        $wsuser = $this->quote($config['ws_user']);
        $wspass = $this->quote($config['ws_password']);
        
		$sql = "INSERT INTO sge_ws_conexion (conexion_nombre, ws_url, ws_user, ws_clave)
                VALUES      ('Conexion default (migrada de instalación anterior)',
                            $wsurl,
                            $wsuser,
                            $wspass)";
   
        $this->ejecutar($sql);
	}
}
?>