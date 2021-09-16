<?php

use ext_bootstrap\componentes\bootstrap_ci;

class ci_cambio_de_clave extends bootstrap_ci
{
    protected $s__formulario;
    
    function conf__formulario(toba_ei_formulario $form)
    {
        if (isset($this->s__formulario)) {
            $form->set_datos($this->s__formulario);
        }
    }
    
    function evt__formulario__modificacion($datos)
	{
        $this->s__formulario = $datos;
	}	
    
    function evt__procesar()
    {
        $usuario      = toba::usuario()->get_id();
		$clave_actual = $this->s__formulario['clave_actual'];
		$clave_nueva  = $this->s__formulario['clave_nueva'];
		
		if (toba::usuario()->autenticar($usuario, $clave_actual)) {
            
            //Se verifica que la composición de la clave sea valida
            $largo_clave = kolla::co('co_toba')->get_largo_pwd();
            toba_usuario::verificar_composicion_clave($clave_nueva, $largo_clave);

            //Guardar la nueva contraseña - actualizar la tabla apex_usuario
            toba::usuario()->set_clave($clave_nueva);
            unset($this->s__formulario);
            toba::notificacion()->info('La contraseña se ha cambiado con exito.');
		} else {
			toba::notificacion()->agregar('La contraseña actual es incorrecta.');
		}
    }
}

?>