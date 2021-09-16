<?php

class kolla_comando extends toba_aplicacion_comando_base 
{
	
	function opcion__crear_negocio($parametros)
	{
		$this->modelo->crear_negocio($parametros);
	}

    function opcion__migrar_negocio($parametros)
    {
        $this->modelo->migrar_negocio($parametros);
    }

    function opcion__crear_negocio_test($parametros)
    {
        $this->modelo->crear_negocio_test($parametros);
    }
    
    function opcion__actualizar_desarrollo(){
        $this->modelo->actualizar_desarrollo();
    }

}

?>