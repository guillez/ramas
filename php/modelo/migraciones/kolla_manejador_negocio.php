<?php

require_once('kolla_migrador.php');

class kolla_manejador_negocio extends manejador_negocio
{
/*
	function migrar_negocio($version, $es_base_nueva)
	{
        $inst     = inst::configuracion()->get_info_instalacion();
        $desde    = new toba_version($inst['sistema']['version_actual']);
        $hasta    = new toba_version($version);
        $base     = new base_intermedia($this->conexion);
        $migrador = new kolla_migrador($base);
        $migrador->migrar($desde, $hasta);
	}

	function crear_negocio($version, $grupo_datos)
    {   
        try {
            inst::db_manager()->abrir_transaccion($this->conexion);
            // Datos
            $directorios = array(
                $this->path_proyecto.'/sql/datos/base',
                $this->path_proyecto.'/sql/datos/juegos_de_datos/mug',
                $this->path_proyecto.'/sql/datos/juegos_de_datos/mdi',
                $this->path_proyecto.'/sql/datos/juegos_de_datos/encuestas_graduados',
                $this->path_proyecto.'/sql/datos/juegos_de_datos/relevamiento_ingenieria',
                $this->path_proyecto.'/sql/datos/juegos_de_datos/desgranamiento_universitario',
                $this->path_proyecto.'/sql/datos/juegos_de_datos/relevamiento_convocatorias',
                $this->path_proyecto.'/sql/ddl/50_SetVals',
                $this->path_proyecto.'/sql/ddl/80_Procesos',
                $this->path_proyecto.'/sql/ddl/90_Vistas',
                $this->path_proyecto.'/sql/ddl/100_Otros'
            );

            foreach ($directorios as $directorio) {
                $archivos = inst::archivos()->get_archivos_directorio($directorio, '/.sql$/');
                foreach ($archivos as $archivo) {
                    inst::db_manager()->ejecutar_archivo($this->conexion, $archivo);
                }
            }
            inst::db_manager()->cerrar_transaccion($this->conexion);
        } catch (Exception $e) {
            inst::db_manager()->abortar_transaccion($this->conexion);
            inst::logger()->error($e);
            throw $e;
        }
    }
	
	function migrar_codigo($version, $desde, $hacia)
    {
        $inst  = inst::configuracion()->get_info_instalacion(); 
        $desde = $inst['sistema']['version_actual'];
        $path  = $inst['sistema']['path'];
        $path_reportes_origen  = "$path.$desde.backup/procesos/reportes";
        $path_reportes_destino = "$path/procesos/reportes";

		$archivos = glob($path_reportes_origen. '/*.txt');
		foreach ($archivos as $origen) {
			//copiar $rep al nuevo directorio
            $destino = str_replace($path_reportes_origen, $path_reportes_destino, $origen);
            copy($origen, $destino);
		}
    }

	function post_instalacion($es_base_nueva)
    {
        if ( $es_base_nueva ) {
            // Para bases nuevas, se crea en sge_encuestado un usuario Administrador del sistema
            $schema = toba::instancia()->get_db()->get_schema();
                    
            $sql = "SELECT  au.nombre,
                            au.email,
                            au.usuario,
                            au.clave
                    FROM    $schema.apex_usuario AS au
                            INNER JOIN $schema.apex_usuario_proyecto AS aup ON au.usuario = aup.usuario
                    WHERE       aup.proyecto = 'kolla'
                            AND aup.usuario_grupo_acc = 'admin'";
            
            $usuario = inst::db_manager()->consultar_fila($this->conexion, $sql);
            
            if ( !$usuario ) {
                $sql = "INSERT INTO sge_encuestado  (apellidos, documento_pais, email, usuario, clave, guest)
                        VALUES                      ('{$usuario['nombre']}',
                                                    54,
                                                    '{$usuario['email']}',
                                                    '{$usuario['usuario']}',
                                                    '{$usuario['clave']}',
                                                    'N')";
                inst::db_manager()->ejecutar($this->conexion, $sql);
            }

            // También se crea un usuario invitado y un grupo que lo incluye para habilitar encuestas
            $this->invitado_y_grupo_predefinidos();
        }

    }

    private function invitado_y_grupo_predefinidos ()
    {
        $schema = toba::instancia()->get_db()->get_schema();

        //el usuario toba
        $clave = inst::db_manager()->quote($this->conexion, toba_usuario::generar_clave_aleatoria(8));
        $sql_usuario = "INSERT INTO $schema.apex_usuario (usuario, clave, nombre, autentificacion)
                    VALUES ('invitado_kolla', $clave, 'Usuario Anónimo predeterminado SIU-Kolla', 'bcrypt')";
        inst::db_manager()->ejecutar($this->conexion, $sql_usuario);

        //el perfil en el proyecto
        $sql_usuario_proyecto = "INSERT INTO $schema.apex_usuario_proyecto (proyecto, usuario_grupo_acc, usuario)
                                  VALUES ('kolla', 'guest', 'invitado_kolla')";
        inst::db_manager()->ejecutar($this->conexion, $sql_usuario_proyecto);

        $schema_kolla = toba::db()->get_schema();

        //el encuestado kolla
        $sql_encuestado = "INSERT INTO $schema_kolla.sge_encuestado (usuario, guest) 
                          VALUES ('invitado_kolla', 'S')
                          RETURNING encuestado;";
        $res = inst::db_manager()->consultar_fila($this->conexion, $sql_encuestado);
        $e = $res['encuestado'];

        //el grupo de encuestados
        $sql_grupo= "INSERT INTO $schema_kolla.sge_grupo_definicion (nombre, estado, descripcion, unidad_gestion) 
                        VALUES ('Grupo invitado - Unidad de Gestión Predeterminada', 'O', 'Grupo predefinido para usuario invitado', '0')
                        RETURNING grupo;";
        $res = inst::db_manager()->consultar_fila($this->conexion, $sql_grupo);
        $g = $res['grupo'];

        $sql_grupo_det = "INSERT INTO $schema_kolla.sge_grupo_detalle (grupo, encuestado)
                          VALUES ($g, $e)";
        inst::db_manager()->ejecutar($this->conexion, $sql_grupo_det);
    }

	function pre_actualizacion($version, $path_aplicacion){}

	function post_actualizacion($version, $path_aplicacion){}
*/
}