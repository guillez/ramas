<?php

use ext_bootstrap\componentes\bootstrap_ci;
require __DIR__."/../../../../vendor/siu-toba/framework/proyectos/toba_usuarios/php/lib/rest_arai_usuarios.php";

class ci_seleccion_usuario_arai extends bootstrap_ci {
	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(bootstrap_cuadro $cuadro)
	{
	    //$cuadro->set_datos(gestion_arai_usuarios::get_usuarios_disponibles_aplicacion($this->s__filtro));
        $cuadro->set_datos($this->get_usuarios_disponibles_aplicacion(null));
	}

    function get_usuarios_disponibles_aplicacion($filtro)
    {
        $datos = array();
        //if (toba::instalacion()->vincula_arai_usuarios() && self::verifica_version_arai_cli()) {
        if (toba::instalacion()->vincula_arai_usuarios()) {
            $datos = rest_arai_usuarios::instancia()->get_usuarios($filtro, SIUToba\Framework\Arai\RegistryHooksProyectoToba::getAppUniqueId());
        }
        return $datos;
    }

}
?>