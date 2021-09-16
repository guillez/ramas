<?php

class conversor
{
	private $clase_escenario;
    private $schema;
	private $conexion;
	private $logger;
    private $desde;
    private $hasta;
    private $path_inst;

	// Constructores

	public function __construct($conn, $logger=null)
	{
		$this->conexion = $conn;
		if ( isset($logger) ) {
			$this->logger = $logger;
		}
	}

	public function set_schema($schema)
	{
		$this->schema = $schema;
	}

	public function get_schema()
	{
		return $this->schema;
	}
    
    function get_db()
    {
        return $this->conexion;
    }
    
    function set_path_inst($path)
    {
        $this->path_inst = $path;
    }
    
    function get_path_inst()
    {
        return $this->path_inst;
    }
    
    function get_version_desde()
    {
        return $this->desde;
    }
    
    function get_version_hasta()
    {
        return $this->hasta;
    }

    private function personalizar_conexion()
	{
		$sql = array(
            'SET datestyle="ISO,DMY"',
            'SET client_encoding="LATIN1"',
            'SET CONSTRAINTS ALL IMMEDIATE'
        );
        $this->ejecutar($sql);
        if ( $this->schema ) {
            $sql = 'SET search_path TO ' . $this->schema;
            $this->ejecutar($sql);
        }
	}

	protected function pre_conversion()
	{
        $desde = $this->desde;
        $hasta = $this->hasta;
        // Verifico que sea un escenario válido
        $this->clase_escenario = 'escenario_'.$desde->get_string_partes('').'_'.$hasta->get_string_partes('');
        $archivo_escenario = dirname(__FILE__).'/escenarios/'.$this->clase_escenario.'.php';

        if ( !file_exists($archivo_escenario) ) {
            $mensaje = '[CONVERSOR][ERROR] El escenario ' . $desde->get_string_partes('.') . ' -> ' . $hasta->get_string_partes('.') . ' no es un escenario válido';
            $this->mensaje($mensaje);
            throw new Exception('Ocurrio un error. Verifique el log.');
        }

        $mensaje = '[CONVERSOR][PRE CONVERSION] Se va a ejecutar el escenario '.$desde->get_string_partes('.').' -> '.$hasta->get_string_partes('.');
        $this->mensaje($mensaje);
	}

	protected function ejecutar_cambio($cambio)
	{
		$clase_cambio = 'cambio_'.$cambio;
		$archivo_cambio = dirname(__FILE__).'/cambios/'.$clase_cambio.'.php';
		if ( !file_exists($archivo_cambio) ) {
			$mensaje = '[CONVERSOR][ERROR] El cambio ' . $cambio . ' no existe';
			$this->mensaje($mensaje);
			throw new Exception($mensaje);
		}
		
		// Creo el objeto de cambio
		$mensaje = '[CAMBIO]['.$cambio.']';
		require_once($archivo_cambio);
		$cambio = new $clase_cambio();
		$cambio->set_conversor($this);
		$cambio->set_logger($this->logger);
        
        try {
			if ( $cambio->mostrar_debug() ) {
				$mensaje .= ' ' . $cambio->get_descripcion(). '...';
				$this->mensaje($mensaje);
			}			
			$cambio->cambiar();
			if ( $cambio->mostrar_debug() ) {
				$mensaje = " OK! \n";
				$this->mensaje($mensaje);
			}
			
		} catch(Exception $e) {
			$mensaje  = 'ERROR: problemas ejecutando el cambio <b> ' . $cambio->get_id() . '</b>.<br><br>';
			$mensaje .= '<i>' . $e->getMessage() . '</i>';
			$mensaje .= '<br><br>Por favor genere el diagnóstico y envíelo por correo electrónico.';
			throw new Exception($mensaje);
		}
	}

	protected function ejecutar_conversion()
	{
		// Construyo el escenario
		require_once('escenarios/' . $this->clase_escenario . '.php');
		$escenario = new $this->clase_escenario();

		// Obtengo la lista de conversiones involucradas en ese escenario
		$conversiones = $escenario->get_conversiones();

		// Obtengo los cambios de cada conversión y los agrego a la lista de cambios a ejecutar
		$cambios = array();
		foreach ($conversiones as $conversion) {
			$clase_conversion = 'conversion_' . $conversion;
			require_once('conversiones/' . $clase_conversion . '.php');
			$conversion = new $clase_conversion();
			$cambios = array_merge($cambios, $conversion->get_cambios());
		}

		// Ejecuto los cambios
		foreach ($cambios as $cambio) {
			$this->ejecutar_cambio($cambio);
		}
	}

	protected function post_conversion()
	{
        $hasta = $this->hasta;
        $mensaje = '[CONVERSOR][POST-CONVERSION] se actualizó la versión de la base de datos a ' . $hasta->get_string_partes('.');
        $this->mensaje($mensaje);
	}
    
    /**
     * 
     * @param type $desde Versión inicial del sistema
     * @param type $hasta Versión a la que se quiere llegar
     * @param type $usar_transaccion En línea de comandos se utilizan transacciones, si viene en false significa que se está
     *                               corriendo desde el instalador web.
     * @throws Exception
     */
    function migrar($desde, $hasta, $usar_transaccion=true)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
        
        if ( $hasta->es_mayor($desde) ) {
            ini_set('max_execution_time', 0);
            try {
                if ( $usar_transaccion ) {
                    $this->abrir_transaccion();   
                }
                $this->personalizar_conexion();
                $this->pre_conversion();
                $this->ejecutar_conversion();
                $this->post_conversion();
                if ( $usar_transaccion ) {
                    $this->cerrar_transaccion();
                }
            } catch (Exception $e) {
                if ( $usar_transaccion ) {
                    $this->abortar_transaccion();
                }
                throw $e;
            }
        } else {
            $this->mensaje('No es posible migrar hacia una version menor o igual.');
        }
    }

    /**
     * 
     * @param type $cambios Arreglo con nombres de cambios (esto luego se mapea con nombres de archivos en el file system)
     * @param type $usar_transaccion En línea de comandos se utilizan transacciones, si viene en false significa que se está
     *                               corriendo desde el instalador web.
     * @throws Exception
     */
	public function convertir($cambios, $usar_transaccion=true)
	{
		ini_set('max_execution_time', 0);
		try {
            if ( $usar_transaccion ) {
                $this->abrir_transaccion();
            }
			$this->personalizar_conexion();
            if ( is_array($cambios) ) {
                foreach ($cambios as $cambio) {
                    $this->ejecutar_cambio($cambio);
                }
            }
            if ( $usar_transaccion ) {
                $this->cerrar_transaccion();
            }
		} catch (Exception $e) {
            if ( $usar_transaccion ) {
                $this->abortar_transaccion();
            }
			throw $e;
		}
	}
	
	function mensaje($mensaje)
	{
		if ( isset($this->logger) ) {
			$this->logger->grabar($mensaje);
		} else {
			echo $mensaje;
		}
	}
	
	function existe_tabla($esquema, $tabla)
	{
		$sql = "SELECT EXISTS(
					SELECT  *
					FROM    information_schema.tables
					WHERE	table_schema = '{$esquema}' AND
                            table_name   = '{$tabla}'
				) AS existe
		";

        $rs = $this->consultar_fila($sql);		
		return $rs['existe'];
	}
        
    // Wrappers para el db_manager/toba_db
    
    function quote($valor)
    {
        if ($this->get_db() instanceof toba_db_postgres7) {
            return $this->get_db()->quote($valor);
        } else {
            return inst::db_manager()->quote($this->get_db(), $valor);
        }
    }
    
    function consultar($sql)
    {
        if ($this->get_db() instanceof toba_db_postgres7) {
            return $this->get_db()->consultar($sql);
        } else {
            return inst::db_manager()->consultar($this->get_db(), $sql);
        }
    }
    
    function consultar_fila($sql)
    {
        if ($this->get_db() instanceof toba_db_postgres7) {
            return $this->get_db()->consultar_fila($sql);
        } else {
            return inst::db_manager()->consultar_fila($this->get_db(), $sql);
        }
    }
    
    function ejecutar($sql)
    {
        if ($this->get_db() instanceof toba_db_postgres7) {
            return $this->get_db()->ejecutar($sql);
        } else {
            return inst::db_manager()->ejecutar($this->get_db(), $sql);
        }
    }
    
    function ejecutar_archivo($archivo)
    {
        if ($this->get_db() instanceof toba_db_postgres7) {
            return $this->get_db()->ejecutar_archivo($archivo);
        } else {
            $this->logger->grabar($archivo);
            return inst::db_manager()->ejecutar_archivo($this->get_db(), $archivo);
        }
    }
    
    function abrir_transaccion()
    {
        if ($this->get_db() instanceof toba_db_postgres7) {
            $this->get_db()->abrir_transaccion();
        } else {
            inst::db_manager()->abrir_transaccion($this->get_db());
        }
    }
    
    function cerrar_transaccion()
    {
        if ($this->get_db() instanceof toba_db_postgres7) {
            $this->get_db()->cerrar_transaccion();
        } else {
            inst::db_manager()->cerrar_transaccion($this->get_db());
        }
    }
    
    function abortar_transaccion()
    {
        if ($this->get_db() instanceof toba_db_postgres7) {
            $this->get_db()->abortar_transaccion();
        } else {
            inst::db_manager()->abortar_transaccion($this->get_db());
        }
    }
    
    function existe_columna($esquema, $tabla, $columna)
    {
        $esquema = $this->quote($esquema);
        $tabla   = $this->quote($tabla);
        $columna = $this->quote($columna);
        
        $sql = "SELECT EXISTS(
                    SELECT  1
                    FROM    information_schema.columns
                    WHERE   table_schema = $esquema AND 
                            table_name = $tabla AND
                            column_name = $columna
                ) AS existe";
                        
        $rs = $this->consultar_fila($sql);
        
		return $rs['existe'];
    }
    
    function eliminar_datos()
    {
        $tablas = array(
            'mgn_log_envio',
            'mgn_mail',
            'sge_grupo_habilitado',
            'sge_reporte_exportado',
            'sge_respondido_detalle',
            'sge_respondido_encuesta',
            'sge_respondido_encuestado',
            'sge_respondido_por',
            'sge_respondido_formulario',
            'sge_formulario_definicion',
            'sge_formulario_atributo',
            'sge_formulario_habilitado_indicador',
            'sge_formulario_habilitado_detalle',
            'sge_formulario_habilitado',
            'sge_elemento_concepto_tipo',
            'sge_concepto',
            'sge_elemento',
            'sge_tipo_elemento',
            'sge_log_formulario_definicion_habilitacion',
            'sge_habilitacion',
        );
        toba::db()->abrir_transaccion();
        try {
            $this->mensaje("Eliminando datos...\n\n");
            toba::db()->ejecutar('SET CONSTRAINTS ALL DEFERRED');
            foreach ($tablas as $tabla) {
                $sql = "DELETE FROM $tabla";
                toba::db()->ejecutar($sql);
                $this->mensaje("$tabla: Ok \n");
            }
            $this->mensaje("\nLos datos del sistema se eliminaron correctamente.");
            toba::db()->cerrar_transaccion();
        } catch (toba_error_db $ex) {
            toba::db()->abortar_transaccion();
            throw $ex;
        }
    }

}