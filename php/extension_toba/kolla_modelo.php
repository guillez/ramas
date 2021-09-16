<?php
//Se modifica el require para resolver el path al proyecto
require_once(dirname(__FILE__).'/../modelo/migraciones/kolla_migrador.php');

class kolla_modelo extends toba_aplicacion_modelo_base 
{
    protected $desde;
    protected $hasta;

    function instalar($parametros) {

        if (isset($parametros['schema'])) {
            $this->schema_modelo = $parametros['schema'];
        }
        else {
            $this->schema_modelo = 'kolla';
        }

        $parametros_db_toba = $this->instancia->get_parametros_db();
        if (isset($parametros_db_toba['schema'])) {
            $this->schema_toba = $parametros_db_toba['schema'];
        } else {
            $this->schema_toba = 'toba_'.$this->schema_modelo;
        }

        //se replica la creación de la sección en bases.ini
        $id_def_base = $this->proyecto->construir_id_def_base($this->get_fuente_defecto());
        //--- Chequea si existe la entrada de la base de negocios en el archivo de bases
        if (! $this->instalacion->existe_base_datos_definida($id_def_base)) {
            if (! isset($parametros['base'])) {
                $id_base = $this->get_id_base();
                $parametros['base'] = $id_base;
            }
            //-- Cambia el schema
            if (! isset($parametros['schema'])) {
                $parametros['schema'] =  $this->schema_modelo;
            }
            //-- Agrega la definición de la base
            $this->instalacion->agregar_db($id_def_base, $parametros);
            if ($this->permitir_determinar_encoding_bd) {
                $this->instalacion->determinar_encoding($id_def_base);
            }
        }

        $this->crear_negocio();
        $this->agregar_admin();
        // Se crea un usuario invitado y un grupo que lo incluye para habilitar encuestas
        $this->invitado_y_grupo_predefinidos();
    }

	function crear_negocio()
	{

        try {
            // Se crea el esquema
            $this->get_db()->abrir_transaccion();
            $sql = "DROP SCHEMA IF EXISTS $this->schema_modelo CASCADE;
                    CREATE SCHEMA $this->schema_modelo;
                    SET search_path TO $this->schema_modelo;
                    SET client_encoding=LATIN1;
                    SET CONSTRAINTS ALL DEFERRED;";
            $this->get_db()->ejecutar($sql);
            // Estructura
            $archivo = $this->proyecto->get_dir().'/sql/estructura.sql';
            $this->get_db()->ejecutar_archivo($archivo);

            // Datos
            $dirs = array(
                $this->proyecto->get_dir().'/sql/datos/base',
                $this->proyecto->get_dir().'/sql/datos/juegos_de_datos/mug',
                $this->proyecto->get_dir().'/sql/datos/juegos_de_datos/mdi',
                $this->proyecto->get_dir().'/sql/datos/juegos_de_datos/mdp',
                $this->proyecto->get_dir().'/sql/datos/juegos_de_datos/encuestas_graduados',
                $this->proyecto->get_dir().'/sql/datos/juegos_de_datos/relevamiento_ingenieria',
                $this->proyecto->get_dir().'/sql/datos/juegos_de_datos/desgranamiento_universitario',
                $this->proyecto->get_dir().'/sql/datos/juegos_de_datos/relevamiento_convocatorias',
                $this->proyecto->get_dir().'/sql/ddl/50_SetVals',
                $this->proyecto->get_dir().'/sql/ddl/80_Procesos',
                $this->proyecto->get_dir().'/sql/ddl/90_Vistas',
                $this->proyecto->get_dir().'/sql/ddl/100_Otros'
            );

            foreach ($dirs as $dir) {
                $archivos = toba_manejador_archivos::get_archivos_directorio($dir, '/.sql$/');
                foreach ($archivos as $archivo) {
                    $result = $this->get_db()->ejecutar_archivo($archivo);
                    echo $result;
                }
            }

            $this->manejador_interface->separador();
            //$this->generar_encuestados();
            $this->get_db()->cerrar_transaccion();

        } catch (Exception $e) {
            $this->get_db()->abortar_transaccion();
            $this->manejador_interface->separador();
            $this->manejador_interface->mensaje("ERROR al intentar crear la base de Kolla!");
            $this->manejador_interface->separador();
            $this->manejador_interface->mensaje($e->getMessage());
            $this->manejador_interface->separador();
            exit;
        }

        $this->manejador_interface->separador();
        $this->manejador_interface->mensaje("Base de Kolla creada correctamente!");
        $this->manejador_interface->separador();

    }


	function migrar(toba_version $desde, toba_version $hasta)
    {
        $this->manejador_interface->mensaje("Se pide ir de $desde a ".$hasta);

        $this->desde = $this->get_version_a_actualizar();
        $this->hasta = $hasta;

        $this->manejador_interface->mensaje("Comienza el proceso de migración de versión $desde a ".$hasta);

        $migrador = new kolla_migrador($this->get_db());
        $migrador->set_interface($this->manejador_interface);

        try {
            $this->get_db()->abrir_transaccion();
            $migrador->migrar($this->desde, $this->hasta);
            $this->get_db()->cerrar_transaccion();
        }
        catch (Exception $exception) {
            $this->manejador_interface->error($exception->getMessage());
            $this->manejador_interface->error($exception->getTrace());
        }
    }

    function migrar_negocio($parametros)
    {
        // Chequeo de parámetros
        if ( (!isset($parametros['-v']) || isset($parametros['-v']) && empty($parametros['-v'])) ||
             isset($parametros['-m']) && empty($parametros['-m'])) {
            $this->manejador_interface->separador();
			$this->manejador_interface->mensaje("Uso: toba proyecto migrar [-p proyecto] -v x.y.z [-m nombre_metodo]", true);
            $this->manejador_interface->enter();
            $this->manejador_interface->mensaje("-v x.y.z Es la version del sistema a la que se quiere llegar.", true);
			$this->manejador_interface->mensaje("-m nombre_metodo Para ejecutar un unico metodo de la clase de migracion x.y.z (util para testing)", true);
            $this->manejador_interface->separador();
            
			return false;
		}
        
        // Para indicar un método
        if ( isset($parametros['-m']) ) {
            $metodo = $parametros['-m'];
        } else {
            $metodo = null;
        }
        
        // Versión actual del proyecto
        $desde     = $this->get_version_actual();
        // Versión a la que quiero llegar
        $version   = $parametros['-v'];
        $hasta     = new toba_version($version);
        // Migrador
        $migrador   = new kolla_migrador($this->get_db());
        $migrador->set_interface($this->manejador_interface);
        // Si se indica un método en particular (para testing)
        if ( $metodo ) {
            $migrador->ejecutar_migracion($hasta, $metodo, true);
        } else {
            $migrador->migrar($desde, $hasta);
            $this->set_version_actual($hasta);
        }
    }
    
    private function get_db()
    {
        return $this->instancia->get_db();
    }
    
    /**
     * Permite crear la base de datos original para test
     * @todo Se deberia factorizar los metodos
     */
    function crear_negocio_test()
    {
        // Se crea el esquema
       
        $this->get_db()->abrir_transaccion();
        echo "Regenerando esquema 'kolla_test'...\n";
		
        $sql = "DROP SCHEMA IF EXISTS kolla_test CASCADE;
                CREATE SCHEMA kolla_test;
                SET search_path TO kolla_test;
                SET client_encoding=LATIN1;
                SET CONSTRAINTS ALL DEFERRED;";
        $result = $this->get_db()->ejecutar($sql);
        
        // Estructura
        echo "Creando estructura inicial... \n";
        $archivo = $this->proyecto->get_dir().'/sql/estructura.sql';

        $this->get_db()->ejecutar_archivo($archivo);
        // Datos
        echo "Insertando datos iniciales... \n";
        $dirs = array(
            $this->proyecto->get_dir().'/sql/datos/base',
            $this->proyecto->get_dir().'/sql/datos/juegos_de_datos/mug',
            $this->proyecto->get_dir().'/sql/datos/juegos_de_datos/mdi',
            $this->proyecto->get_dir().'/sql/datos/juegos_de_datos/encuestas_graduados',
            $this->proyecto->get_dir().'/sql/datos/juegos_de_datos/relevamiento_ingenieria',
            $this->proyecto->get_dir().'/sql/datos/juegos_de_datos/desgranamiento_universitario',
            $this->proyecto->get_dir().'/sql/datos/juegos_de_datos/relevamiento_convocatorias',
            $this->proyecto->get_dir().'/sql/ddl/50_SetVals',
            $this->proyecto->get_dir().'/sql/ddl/80_Procesos',
            $this->proyecto->get_dir().'/sql/ddl/90_Vistas',
            $this->proyecto->get_dir().'/sql/ddl/100_Otros'
        );

        foreach ($dirs as $dir) {
            $archivos = toba_manejador_archivos::get_archivos_directorio($dir, '/.sql$/');
            foreach ($archivos as $archivo) {
                $this->get_db()->ejecutar_archivo($archivo);
            }
        }
        $this->get_db()->cerrar_transaccion();
        echo "Base de datos 'kolla_test' generada... \n";
    }

    function actualizar_desarrollo()
    {
        // Migrador
        $migrador   = new kolla_migrador($this->get_db());
        $migrador->set_interface($this->manejador_interface);
        
        // Versión actual de la base de desarrollo
        $desde     = new toba_version($this->determinar_version_base());
        //determinar maxima version disponible
        $hasta = new toba_version($migrador->_max_migracion_disponible());
        $migrador->migrar_desarrollo($desde, $hasta);
    }
    
    function determinar_version_base()
    {
        $sql = "SELECT valor 
                FROM $this->schema_modelo.sge_parametro_configuracion 
                WHERE parametro = 'version_base'; ";
        $res = $this->get_db()->consultar_fila($sql);
        $version_base = $res['valor'];        
        
        if (!isset($version_base) || is_null($version_base) || ($version_base == '')) {
            $version_base = $this->get_version_actual();
            $vb = quote($version_base);
            $sql = "INSERT INTO $this->schema_modelo.sge_parametro_configuracion(
                    seccion, parametro, valor)
                    VALUES ('actualizacion', 'version_base', $vb);";
            $this->get_db()->ejecutar($sql);
        }
        return $version_base;
    }

    function get_version_actual()
    {
        $id_proyecto = $this->get_db()->quote($this->proyecto->get_id());
        $schema_toba = toba::instancia()->get_db()->get_schema();

        $sql = "SELECT version 
                FROM $schema_toba.apex_proyecto ap 
                WHERE ap.proyecto = $id_proyecto";
        $res = $this->get_db()->consultar_fila($sql);

        return new toba_version($res['version']);
    }

    function set_version_actual($version)
    {
        $id_proyecto = $this->get_db()->quote($this->proyecto->get_id());
        $schema_toba = toba::instancia()->get_db()->get_schema();


        $sql = "UPDATE $schema_toba.apex_proyecto ap 
                SET version = '$version' 
                WHERE ap.proyecto = $id_proyecto";
        $this->get_db()->ejecutar($sql);
    }


    function get_version_a_actualizar()
    {
        $id_proyecto = $this->get_db()->quote($this->proyecto->get_id());
        $schema_toba = toba::instancia()->get_db()->get_schema();
        $schema_toba .= "_backup";

        $sql = "SELECT version 
                FROM $schema_toba.apex_proyecto ap 
                WHERE ap.proyecto = $id_proyecto";
        $res = $this->get_db()->consultar_fila($sql);

        return new toba_version($res['version']);
    }

    private function invitado_y_grupo_predefinidos ()
    {
        $db = toba::instancia()->get_db();

        try {
            // Se crea el esquema
            $db->abrir_transaccion();

            $db->set_schema($this->schema_modelo);
            //el usuario toba
            $clave = toba::instancia()->get_db()->quote(toba_usuario::generar_clave_aleatoria(8));
            $sql_usuario = "INSERT INTO $this->schema_toba.apex_usuario (usuario, clave, nombre, autentificacion)
                        VALUES ('invitado_kolla', $clave, 'Usuario Anónimo predeterminado SIU-Kolla', 'bcrypt')";
            $db->ejecutar($sql_usuario);

            //el perfil en el proyecto
            $sql_usuario_proyecto = "INSERT INTO $this->schema_toba.apex_usuario_proyecto (proyecto, usuario_grupo_acc, usuario)
                                      VALUES ('kolla', 'guest', 'invitado_kolla')";
            $db->ejecutar($sql_usuario_proyecto);

            //el encuestado kolla
            $sql_encuestado = "INSERT INTO $this->schema_modelo.sge_encuestado (usuario, guest) 
                              VALUES ('invitado_kolla', 'S')
                              RETURNING encuestado;";
            $res = $db->consultar_fila($sql_encuestado);
            $e = $res['encuestado'];

            //el grupo de encuestados
            $sql_grupo= "INSERT INTO $this->schema_modelo.sge_grupo_definicion (nombre, estado, descripcion, unidad_gestion) 
                            VALUES ('Grupo invitado - Unidad de Gestión Predeterminada', 'O', 'Grupo predefinido para usuario invitado', '0')
                            RETURNING grupo;";
            $res = $db->consultar_fila($sql_grupo);
            $g = $res['grupo'];

            $sql_grupo_det = "INSERT INTO $this->schema_modelo.sge_grupo_detalle (grupo, encuestado)
                              VALUES ($g, $e)";
            $db->ejecutar($sql_grupo_det);

            $db->cerrar_transaccion();
        } catch  (Exception $e) {
            $db->abortar_transaccion();
            $this->manejador_interface->mensaje("ERROR al intentar crear el usuario invitado y su grupo.");
            $this->manejador_interface->mensaje($e->getMessage());
            $this->manejador_interface->separador();
            exit;
        }
    }

    private function agregar_admin()
    {
        $db = toba::instancia()->get_db();
        try {
            // Se crea el esquema
            $db->abrir_transaccion();

            $sql = "SELECT 	au.*
                    FROM 	$this->schema_toba.apex_usuario au";
            $usuarios = $db->consultar($sql);

            $db->set_schema($this->schema_modelo);

            foreach ($usuarios as $usuario) {
                $u = $usuario['usuario'];
                $sql_encuestado = "INSERT INTO $this->schema_modelo.sge_encuestado (usuario, guest) 
                              VALUES ('$u', 'N');";
                $db->ejecutar($sql_encuestado);
            }

            $db->cerrar_transaccion();
            $this->manejador_interface->mensaje("Se generaron los usuarios encuestados para todos los usuarios del módulo-");
        } catch  (Exception $e) {
            $db->abortar_transaccion();
            $this->manejador_interface->mensaje("ERROR al intentar crear el encuestado para el usuario admin");
            $this->manejador_interface->mensaje($e->getMessage());
            $this->manejador_interface->separador();
            exit;
        }
    }

}