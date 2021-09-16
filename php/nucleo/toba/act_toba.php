<?php

class act_toba 
{

    /**
     * Agrega un usuario a Toba y lo vincula al proyecto.
     * Utiliza el esquema hardcodeado para poder utilizar la sql en una transacción en la misma fuente que el proyecto.
     * 
     * @param string $usuario
     * @param string $nombre
     * @param string $grupo
     * @param string $email
     * @param array $atributos
     */
    static function agregar_usuario($usuario, $nombre, $grupo, $email=null, $atributos=array())
    {
        $schema    = toba::db('toba')->get_schema();
        $largo_pwd = kolla::co('co_toba')->get_largo_pwd();
        $hasher = new toba_hash(apex_pa_algoritmo_hash);
        $forzar_pwd = 0;

        $clave_enc = $atributos['clave'];

        if (!isset($atributos['clave']) || $atributos['clave'] == '') {
            //tiene sentido encriptarla?
            //no hay manera de conocerla por lo que se deberá generar una nueva de todos modos
            $clave_enc = $hasher->hash(toba_usuario::generar_clave_aleatoria($largo_pwd));
            $atributos['autentificacion'] = apex_pa_algoritmo_hash;
        } else {
            if (!isset($atributos['autentificacion']) || $atributos['autentificacion'] == '') {
                //en este caso se asume plana y se debe encriptar la clave
                try {
                    $clave_enc = $hasher->hash($atributos['clave']);
                } catch (toba_error $e) {
                    toba::logger()->debug("Error en hasher ".$e->get_mensaje());
                }
                $atributos['autentificacion'] = apex_pa_algoritmo_hash;
                $forzar_pwd = 1;
            }
            //en otro caso ya se asume encriptada la clave
        }
        $atributos['clave'] = $clave_enc;

        $datos_usuario = array(
            'usuario'           => $usuario,
            'clave'             => $clave_enc,
            'nombre'            => $nombre,
            'forzar_cambio_pwd' => $forzar_pwd
        );
        if ( isset($email) ) {
            $datos_usuario['email'] = $email;
        }
        foreach ($atributos as $clave => $valor) {
            $datos_usuario[$clave] = $valor;
        }
        toba::logger()->var_dump($datos_usuario);
        $sql = sql_array_a_insert("$schema.apex_usuario", $datos_usuario);
        toba::logger()->debug($sql);
        toba::db()->ejecutar($sql);

        $acceso = array(
            'proyecto'          => 'kolla',
            'usuario_grupo_acc' => $grupo,
            'usuario'           => $datos_usuario['usuario']
        );
        $sql = sql_array_a_insert("$schema.apex_usuario_proyecto", $acceso);
        toba::db()->ejecutar($sql);
    }
    
    /**
     * Elimina el usuario y la vinculación del mismo al proyecto Kolla
     * 
     * @param string $usuario Usuario a eliminar
     * @param array $grupo Arreglo con el identificador de los grupos a eliminar
     */
    static function eliminar_usuario($usuario, $grupo=array())
    {
        $schema  = toba::db('toba')->get_schema();
        $usuario = toba::db()->quote($usuario);
        $partes = array(
            "proyecto = 'kolla'",
            "usuario = $usuario"
        );
        if ( !empty($grupo) ) {
            $partes[] = 'usuario_grupo_acc IN (' . implode(',', toba::db()->quote($grupo)) . ')';
        }
        $where = implode(' AND ', $partes);
        // Se borra asociación del usuario al proyecto
        $sql = "DELETE
                FROM    $schema.apex_usuario_proyecto
                WHERE   $where";
        toba::db()->ejecutar($sql);
        // Se borra el usuario del proyecto
        $sql = "DELETE
                FROM    $schema.apex_usuario
                WHERE   usuario = $usuario";
        toba::db()->ejecutar($sql);
    }
    
    static function actualizar_usuario($usuario)
    {
        
    }
}
