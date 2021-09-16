<?php

require_once('cambio.php');

/*
 * Creación de usuario Admin luego de la instalación de una nueva base
 */
class cambio_316 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 316: usuario administrador para base nueva';
	}
    
	function cambiar()
	{
        $schema = 'kolla';
            
        $sql = "SELECT  au.nombre,
                        au.email,
                        au.usuario,
                        au.clave
                FROM    toba_$schema.apex_usuario AS au
                        INNER JOIN toba_$schema.apex_usuario_proyecto AS aup ON au.usuario = aup.usuario
                WHERE   aup.proyecto = 'kolla'
                        AND aup.usuario_grupo_acc = 'admin'";

        $admin = $this->consultar_fila($sql);

        if ( !empty($admin) ) {
            $sql = "
                SET search_path TO $schema;
                INSERT INTO	sge_encuestado
                (
                    apellidos,
                    documento_pais,
                    email,
                    usuario,
                    clave,
                    guest
                )
                VALUES
                (
                    '{$admin['nombre']}',
                    54,
                    '{$admin['email']}',
                    '{$admin['usuario']}',
                    '{$admin['clave']}',
                    'N'
                )
            ";
            
            $this->ejecutar($sql);
        }
    }
    
}

?>