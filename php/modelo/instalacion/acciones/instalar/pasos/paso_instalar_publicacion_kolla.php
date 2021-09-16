<?php

class paso_instalar_publicacion_kolla extends paso_instalar_publicacion
{
    
    function procesar()
	{
		//Creo el archivo que setea las variables de entorno para dejar disponible una consola
		$toba_lib = new toba_lib();		
		$toba_lib->generar_consola_administrativa();			
		//Desactivo los servicios web que puedan haber quedado en el sistema
		if (method_exists($toba_lib, 'desactivar_servicios_web')) {
			$proyecto = inst::configuracion()->get('proyecto', 'id');
			$_SESSION['servicios_web_desactivados'] = $toba_lib->desactivar_servicios_web($proyecto);
		}
		
        /*
         * Creo una conexión ficticia para los servicios de Guarani. Esto es necesario
         * para que funcionen correctamente los WS de tipo REST. Toba leerá primero de
         * acá, pero luego lo pisa con lo configurado desde Kolla.
         */
        $instancia = inst::configuracion()->get_nombre_instancia();
        $ini_cliente = new inst_ini($_SESSION['path_instalacion']."/instalacion/i__$instancia/p__$proyecto/rest/guarani/cliente.ini");
        $datos = array( ';to'            => "https://url.a.proyecto/rest/",
                        ';auth_tipo'     => 'digest',
                        ';auth_usuario'  => 'usuario1',
                        ';auth_password' => 'CAMBIAR');
        $ini_cliente->agregar_entrada('conexion', $datos);
        $ini_cliente->guardar();
        
		$this->set_completo();		
	}
}

?>