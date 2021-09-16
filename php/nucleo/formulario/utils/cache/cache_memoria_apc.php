<?php

class cache_memoria_apc extends cache
{
	function get_tipo()
	{
		return admin_cache::tipo_memoria;
	}
	
	function existe($id, $cache=true)
	{
		return (boolean)apc_fetch($id);
	}

	function guardar($id, $datos, $expiration=0)
	{
		return apc_store($id, $datos, $expiration);
	}

	function buscar($id)
	{
		return apc_fetch($id);
	}

	function eliminar($id)
	{
		return apc_delete($id);
	}

        public function disponible() {
            return extension_loaded('apc');
        }
}
?>