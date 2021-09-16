<?php


class cache_memoria_memcached extends cache
{
	protected $cache = array();

	protected static $m;

	protected static $config = array (
		'memcached' => 
			array (
			'server_1' => 
				array (
					'host' => 'localhost',
					'port' => 11211,
					'peso' => 1,
				),
			),
	);
        	
	protected function get($clave){
		if(isset(self::$config[$clave]))
			return self::$config[$clave];
		return null;
	}
		
	protected function disponible(){
            return extension_loaded('memcached');
        }
        
	protected function memcached()
	{
		if (! isset($this->m)) { 
			$conf_memcached = $this->get('memcached');
			if (empty($conf_memcached)) {
				return null;
			} 
                       
                        /*if(!$memcached_enabled){
                            kolla::logger()->info("Memcached no está habilitado");
                            return null;
                        }*/
			$this->m = new Memcached();
			$servers = array();
			foreach ($conf_memcached as $key => $data) {
				$servers[] = array(
					$data['host'], $data['port'], $data['peso']
				);
			}
			$b = $this->m->addServers($servers);
		}
		return $this->m;
	}
        
	function get_tipo()
	{
		return admin_cache::tipo_memoria;
	}
	
	function existe($id, $cache=true)
	{
		if ($this->memcached() !== null) {
			$value = $this->memcached()->get($id);
			if($value===false){
				return false;
			} else {
				if($cache) $this->cache[$id] = $value;
				return true;
			}
		} 
		return false;
	}

	function guardar($id, $datos, $expiration=0)
	{
  		if ($this->memcached() !== null) {
			return $this->memcached()->set($id, $datos, $expiration);
		}
		return false;
	}

	function buscar($id)
	{
		if ($this->memcached() !== null) {
			if(isset($this->cache[$id])){
				return $this->cache[$id];
			}
			return $this->memcached()->get($id);
		}
		return false;
	}

	function eliminar($id)
	{
		if ($this->memcached() !== null) {
			return $this->memcached()->delete($id);
		}
	}
}
?>