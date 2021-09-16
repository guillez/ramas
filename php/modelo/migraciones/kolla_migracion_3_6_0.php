<?php

class kolla_migracion_3_6_0 extends kolla_migracion
{
    /**
     * Estructura para tablas asociadas
     */
    function negocio__443()
    {
        $sql = "CREATE SEQUENCE sge_tabla_asociada_seq START 1;
            
                CREATE  TABLE sge_tabla_asociada
                (
                    tabla_asociada INTEGER NOT NULL DEFAULT nextval('sge_tabla_asociada_seq'::text) ,
                    unidad_gestion Varchar NOT NULL,
                    tabla_asociada_nombre Varchar NOT NULL
                );

                ALTER TABLE sge_tabla_asociada ADD CONSTRAINT pk_sge_tabla_asociada PRIMARY KEY (tabla_asociada);
                
                CREATE INDEX ifk_sge_tabla_asociada_sge_unidad_gestion ON  sge_tabla_asociada (unidad_gestion);

                ALTER TABLE sge_tabla_asociada
                    ADD CONSTRAINT fk_sge_tabla_asociada_sge_unidad_gestion FOREIGN KEY (unidad_gestion)
                    REFERENCES sge_unidad_gestion (unidad_gestion);
                ";

        $this->get_db()->ejecutar($sql);
    }
    
    /*
     * Se agrega en la definición de Grupo el campo para la UG
     */
    function negocio__428()
    {
        $existe = $this->get_db()->existe_columna('unidad_gestion', 'sge_grupo_definicion');
		
        if (!$existe) {
        	$this->alter_grupo_definicion();
        }
        
        $grupos = $this->get_grupos_definicion();
        $unidades_gestion = $this->get_unidades_gestion();
        
        /*
         * Para cada uno de los grupos de usuarios, se lo replicará de
         * acuerdo a si el mismo está o no asociado a una habilitación
         */
        foreach ($grupos as $grupo) {
        	$grupos_detalle = $this->get_grupos_detalle($grupo['grupo']);
        	
        	if ($this->es_grupo_habilitado($grupo['grupo'])) {
        		$unidades_gestion_grupo = $this->get_unidades_gestion_grupo($grupo['grupo']);
        		$formularios_habilitados = $this->get_formularios_habilitados_grupo($grupo['grupo']);
        		
        		/*
        		 * Si el grupo está habilitado, se recorren las UG de las habilitaciones a las
        		 * que fue asociado, y se va generando [Grupo, UG] y modificando la asociación
        		 * entre el grupo y la habilitación, para asignar el grupo recientemente creado
        		 */
        		foreach ($unidades_gestion_grupo as $unidad_gestion) {
        			$this->insert_grupo_definicion($grupo['nombre'], $grupo['estado'], $grupo['externo'], $grupo['descripcion'], $unidad_gestion['unidad_gestion']);
        			$id_grupo = $this->get_secuencia_grupo_definicion();
        			
        			foreach ($grupos_detalle as $grupo_detalle) {
    	    			$this->insert_grupo_detalle($id_grupo, $grupo_detalle['encuestado']);
        			}
        			
        			foreach ($formularios_habilitados as $formulario_habilitado) {
        				$this->insert_grupo_habilitado($id_grupo, $formulario_habilitado['formulario_habilitado']);
        			}
        		}
        		
        		/*
        		 * Se eliminan los datos del grupo, el detalle de los encuestados y también
        		 * las asociaciones [Grupo, Formulario Habilitado] ya que quedaron obsoletas
        		 */
       			$this->delete_grupo_usuarios($grupo['grupo']);
       		} else {
       			
       			/*
        		 * Si el grupo NO está habilitado, se recorren TODAS las
        		 * UG, y para cada una de ellas se genera [Grupo, UG]
        		 */
       			foreach ($unidades_gestion as $unidad_gestion) {
       				
       				if ($unidad_gestion['unidad_gestion'] == '0') {
       					$this->update_grupo_definicion('grupo = '.$this->get_db()->quote($grupo['grupo']));
       				} else {
       					$this->insert_grupo_definicion($grupo['nombre'], $grupo['estado'], $grupo['externo'], $grupo['descripcion'], $unidad_gestion['unidad_gestion']);
       					$id_grupo = $this->get_secuencia_grupo_definicion();
       					
        				foreach ($grupos_detalle as $grupo_detalle) {
   	    					$this->insert_grupo_detalle($id_grupo, $grupo_detalle['encuestado']);
       					}
       				}
       			}
       		}
       	}
    }
    
    /**
     * Estructura de tablas para Preguntas Dependientes
     */
    function negocio__434()
    {
        // Si se llama desde línea de comandos (modo desarrollo) o desde el instalador
        $dir = $this->get_dir_ddl();
        
        $archivos = array(
            $dir.'10_Secuencias/sge_pregunta_dependencia_seq.sql',
            $dir.'10_Secuencias/sge_pregunta_dependencia_definicion_seq.sql',
            $dir.'20_Tablas/sge_pregunta_dependencia.sql',
            $dir.'20_Tablas/sge_pregunta_dependencia_definicion.sql',
            $dir.'60_FK/fk_sge_pregunta_dependencia_definicion_sge_bloque.sql',
            $dir.'60_FK/fk_sge_pregunta_dependencia_definicion_sge_encuesta_definicion.sql',
            $dir.'60_FK/fk_sge_pregunta_dependencia_definicion_sge_pregunta.sql',
            $dir.'60_FK/fk_sge_pregunta_dependencia_definicion_sge_pregunta_dependencia.sql'
        );
        
        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);    
        }
    }
    
    /**
     * Modificación en tabla para Reportes Exportados
     */
    function negocio__436()
    {
        $sql = "DELETE FROM sge_reporte_exportado;
                ALTER TABLE sge_reporte_exportado ADD COLUMN usuario character varying NOT NULL;
                ALTER TABLE sge_reporte_exportado ADD COLUMN fecha_reporte timestamp without time zone NOT NULL DEFAULT NOW();";
        
        $this->get_db()->ejecutar($sql);
    }
    
    /**
     * Modificación en tabla para las Habilitaciones
     */
    function negocio__458()
    {
    	$sql = "ALTER TABLE	sge_habilitacion ADD COLUMN	imprimir_respuestas_completas character(1) DEFAULT 1;";
        
        $this->get_db()->ejecutar($sql);
    }
    
    /*
     * Actualización del reporte de resumen. 
     */
    function negocio__456()
    {   
        // Si se llama desde línea de comandos (modo desarrollo) o desde el instalador
        if ( php_sapi_name() === 'cli' ) {
            $dir = toba::proyecto()->get_path();
        } else {
            $dir = inst::configuracion()->get_dir_inst_aplicacion();
        }
        
        //Se debe actualizar la definición del sp respuestas_completas_habilitacion_conteo
        $archivo = $dir.'/sql/ddl/80_Procesos/120_respuestas_completas_habilitacion_conteo.sql';
		$this->get_db()->ejecutar_archivo($archivo);
    }
    
    /*
     * Se corrigen errores en los sps de reportes   
     */
    function negocio__462()
    {
        // Si se llama desde línea de comandos (modo desarrollo) o desde el instalador
        if ( php_sapi_name() === 'cli' ) {
            $dir = toba::proyecto()->get_path();
        } else {
            $dir = inst::configuracion()->get_dir_inst_aplicacion();
        }
        
        $archivos = array();
        $archivos[] = $dir.'/sql/ddl/80_Procesos/40_preguntas_formulario_habilitado.sql';
        $archivos[] = $dir.'/sql/ddl/80_Procesos/50_respuestas_completas_formulario_habilitado.sql';       
        $archivos[] = $dir.'/sql/ddl/80_Procesos/100_preguntas_habilitacion.sql';
        $archivos[] = $dir.'/sql/ddl/80_Procesos/110_respuestas_completas_habilitacion.sql';
        $archivos[] = $dir.'/sql/ddl/80_Procesos/120_respuestas_completas_habilitacion_conteo.sql';
        
        foreach($archivos as $arch) {
            $this->get_db()->ejecutar_archivo($arch);
        }
    }
    
    //---- Funciones de actualización para 428----------------------------------
    
    function alter_grupo_definicion()
	{
		$sql = 'ALTER TABLE sge_grupo_definicion
        		ADD COLUMN	unidad_gestion Varchar;';
        
        $this->get_db()->ejecutar($sql);
	}
	
	function update_grupo_definicion($where=null)
	{
		$where = isset($where) ? " WHERE $where" : '';
		
		$sql = "UPDATE	sge_grupo_definicion
        		SET		unidad_gestion = 0
        		$where;";
        
        $this->get_db()->ejecutar($sql);
	}
	
	function insert_grupo_definicion($nombre, $estado, $externo, $descripcion, $unidad_gestion)
	{
		$nombre 		= $this->get_db()->quote($nombre);
		$estado 		= $this->get_db()->quote($estado);
		$externo 		= $this->get_db()->quote($externo);
		$descripcion 	= $this->get_db()->quote($descripcion);
		$unidad_gestion = $this->get_db()->quote($unidad_gestion);
		
		$sql = "INSERT INTO sge_grupo_definicion
        			(
        				nombre,
        			 	estado,
        			 	externo,
        			 	descripcion,
        			 	unidad_gestion
        			 )
				VALUES
					(
						$nombre,
					    $estado,
					    $externo,
					    $descripcion,
					    $unidad_gestion
					);";
		
        $this->get_db()->ejecutar($sql);
	}
	
	function insert_grupo_detalle($grupo, $encuestado)
	{
		$grupo 		= $this->get_db()->quote($grupo);
		$encuestado = $this->get_db()->quote($encuestado);
		
		$sql = "INSERT INTO sge_grupo_detalle
        			(
        				grupo,
        				encuestado
        			 )
				VALUES
					(
						$grupo,
						$encuestado
					);";
		
        $this->get_db()->ejecutar($sql);
	}
	
	function insert_grupo_habilitado($grupo, $formulario_habilitado)
	{
		$grupo 				   = $this->get_db()->quote($grupo);
		$formulario_habilitado = $this->get_db()->quote($formulario_habilitado);
		
		$sql = "INSERT INTO sge_grupo_habilitado
        			(
        				grupo,
        				formulario_habilitado
        			 )
				VALUES
					(
						$grupo,
						$formulario_habilitado
					);";
		
        $this->get_db()->ejecutar($sql);
	}
	
	function delete_grupo_usuarios($grupo)
	{
		$grupo = $this->get_db()->quote($grupo);
		
		$sql = "-- Eliminacion de la asociación con el formulario habilitado
				DELETE
				FROM 	sge_grupo_habilitado
        		WHERE	sge_grupo_habilitado.grupo = $grupo;
        		
        		-- Eliminacion de los encuestados del grupo
        		DELETE 
				FROM 	sge_grupo_detalle
        		WHERE	sge_grupo_detalle.grupo = $grupo;
        		
        		-- Eliminacion de los datos del grupo
        		DELETE 
				FROM 	sge_grupo_definicion
        		WHERE	sge_grupo_definicion.grupo = $grupo;";
		
        $this->get_db()->ejecutar($sql);
	}
	
	//---- Funciones de consulta --------------------------------------------------------
	
	function es_grupo_habilitado($grupo)
	{
		$grupo = $this->get_db()->quote($grupo);
        
        $sql = "SELECT EXISTS
	        		(
		                SELECT	1
		                FROM	sge_grupo_habilitado
		                WHERE	sge_grupo_habilitado.grupo = $grupo
		            ) AS habilitado
	        	";
        
        $res = $this->get_db()->consultar_fila($sql);
        
        return $res['habilitado'];
	}
	
	function get_grupos_definicion()
	{
		$sql = 'SELECT	sge_grupo_definicion.grupo,
						sge_grupo_definicion.nombre,
						sge_grupo_definicion.estado,
						sge_grupo_definicion.externo,
						sge_grupo_definicion.descripcion
				FROM	sge_grupo_definicion';
        
        return $this->get_db()->consultar($sql);
	}
	
	function get_grupos_detalle($grupo)
	{
		$grupo = $this->get_db()->quote($grupo);
		
		$sql = "SELECT	sge_grupo_detalle.grupo,
						sge_grupo_detalle.encuestado
				FROM	sge_grupo_detalle
				WHERE	sge_grupo_detalle.grupo = $grupo";
        
        return $this->get_db()->consultar($sql);
	}
	
	function get_unidades_gestion()
	{
		$sql = 'SELECT	sge_unidad_gestion.unidad_gestion,
						sge_unidad_gestion.nombre
	            FROM	sge_unidad_gestion';
       	
        return $this->get_db()->consultar($sql);
	}
	
	function get_secuencia_grupo_definicion()
	{
		$sql = "SELECT currval('sge_grupo_definicion_seq') AS seq;";
		$res = $this->get_db()->consultar_fila($sql);
		return $res['seq'];
	}
	
	function get_unidades_gestion_grupo($grupo)
	{
		$grupo = $this->get_db()->quote($grupo);
		
		$sql = "SELECT	DISTINCT sge_habilitacion.unidad_gestion
				FROM	sge_grupo_habilitado,
						sge_formulario_habilitado,
						sge_habilitacion
				WHERE	sge_grupo_habilitado.grupo = $grupo
				AND		sge_grupo_habilitado.formulario_habilitado = sge_formulario_habilitado.formulario_habilitado
				AND		sge_formulario_habilitado.habilitacion = sge_habilitacion.habilitacion";
		
		return $this->get_db()->consultar($sql);
	}
	
	function get_formularios_habilitados_grupo($grupo)
	{
		$grupo = $this->get_db()->quote($grupo);
		
		$sql = "SELECT	sge_grupo_habilitado.formulario_habilitado
				FROM	sge_grupo_habilitado
				WHERE	sge_grupo_habilitado.grupo = $grupo";
		
		return $this->get_db()->consultar($sql);
	}
    
}