<?php

class co_toba 
{

    function existe_usuario($usuario)
    {
        try {
            $usuario = toba::instancia()->get_info_usuario($usuario);
            return true;
        } catch (toba_error $ex) {
            return false;
        }
    }
    
    function get_largo_pwd()
	{
		return toba::proyecto()->get_parametro('proyecto', 'pwd_largo_minimo', null, false);
	}
}
