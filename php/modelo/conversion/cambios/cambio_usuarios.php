<?php

require_once('cambio.php');

class cambio_usuarios extends cambio
{
    function get_descripcion()
    {
        return 'Cambio Usuarios: Para instalación de desarrollo, crea encuestados en base a la información en apex_usuario';
    }
    
	function cambiar()
	{
        $sql = "SELECT 	apex_usuario.*
				FROM 	apex_usuario,
                        apex_usuario_proyecto
				WHERE 	apex_usuario.usuario = apex_usuario_proyecto.usuario
				AND		apex_usuario_proyecto.proyecto = 'kolla'";
        
        $usuarios = toba::instancia()->get_db()->consultar($sql);
        
        foreach ($usuarios as $usuario) {
            $encuestado = array(
                'usuario'           => $usuario['usuario'],
                'clave'             => $usuario['clave'],
                'guest'             => 'N',
                'externo'           => 'N',
                'sexo'              => 'M',
                'apellidos'         => $usuario['nombre'],
                'nombres'           => 'Kolla',
                'email'             => $usuario['email'],
                'documento_pais'    => 54,
                'documento_tipo'    => '0',
                'documento_numero'  => '123456',
                'fecha_nacimiento'  => '1978-06-21'
            );
            $sql = sql_array_a_insert('sge_encuestado', $encuestado);
            $this->ejecutar($sql);
        }
	}

}