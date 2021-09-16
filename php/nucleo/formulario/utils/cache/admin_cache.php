<?php

//require_once 'cache_memoria_memcached.php';
class admin_cache
{
	const tipo_memoria = 'memoria';
	const tipo_sesion = 'sesion';
	const tipo_archivo = 'archivo';
	const tipo_no = 'no';
	
	const manejador_memoria_apc = 'apc';
	const manejador_memoria_memcached = 'memcached';
	
	static protected $forzar_tipo;
	static protected $instancias = array();
    static private $no_disponibles = array(); //auxiliar
	
	static protected $activo = true;
	static protected $manejador_memoria = self::manejador_memoria_apc;
	//static protected $manejador_memoria = self::manejador_memoria_memcached;
	
	static function set_manejador_memoria($manejador)
	{
		self::$manejador_memoria = $manejador;
	}
	
	static function set_activo($estado)
	{
		self::$activo = $estado;
	}
	
	static function esta_activo($tipo)
	{
		self::controlar_tipo($tipo);		
		if($tipo == 'no') return false;
		return self::$activo;
	}	
	
	static function controlar_tipo($tipo)
	{
		if ($tipo != self::tipo_memoria && $tipo != self::tipo_archivo && 
			$tipo != self::tipo_sesion && $tipo != self::tipo_no ) {
			throw new ErrorException('El tipo de cache solicitado es incorrecto: ' . $tipo);
		}
	}

	
	
	static function forzar_tipo($tipo)
	{
		self::controlar_tipo($tipo);
		self::$forzar_tipo = $tipo;
		kolla::logger()->debug('ADMIN CACHE: forzar tipo '.$tipo);			
	}

	/**
	 * @return siu\modelo\datos\cache\cache
	 */
	static function instancia($tipo)
	{
		self::controlar_tipo($tipo);
                if(isset(self::$no_disponibles[$tipo])) return null;
                
		if (isset(self::$forzar_tipo)) $tipo = self::$forzar_tipo;
		if (!isset(self::$instancias[$tipo])) {
			if ($tipo == self::tipo_memoria) {
				if (self::$manejador_memoria == self::manejador_memoria_apc) {
					$nombre_clase = 'memoria_apc';
				} else {
					$nombre_clase = 'memoria_memcached';
				}
			} else {
				$nombre_clase = $tipo;
			}
			
			
			$clase = "cache_" . $nombre_clase;
			$obj = new $clase();
                        if($obj->disponible()){
                            self::$instancias[$tipo] = new $clase();
                            kolla::logger()->debug('ADMIN CACHE: instanciando: ' . $nombre_clase);
                        }else {
                           self::$no_disponibles[$tipo] = true;//lo marco para que no haga todo devuelta
                            kolla::logger()->debug('ADMIN CACHE: No se puede instanciar: ' . $nombre_clase . ' ¿Se encuentra configurada?');
                        }
		}
               // if(isset(self::$instancias[$tipo]))
                   // return self::$instancias[$tipo];
                return null;
                
        }
}
?>