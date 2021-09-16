<?php

require_once('cambio.php');

class cambio_428 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 428 : Se agrega en la definición de Grupo el campo para la Unidad de Gestión.
							 Migración de los grupos existentes de acuerdo a los perfiles de datos,
							 y si el grupo estaba habilitado o no.';
	}

	function cambiar()
	{
		$existe = $this->existe_columna('kolla', 'sge_grupo_definicion', 'unidad_gestion');
		
        if (!$existe) {
        	$this->alter_grupo_definicion();
        }
        
        $version_desde = $this->get_version_desde();
        
        if ($version_desde->es_menor('3.4.2')) {
        	$this->update_grupo_definicion();
        	return;
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
       					$this->update_grupo_definicion('grupo = '.$this->quote($grupo['grupo']));
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
	
	//---- Funciones de actualización ---------------------------------------------------
	
	function alter_grupo_definicion()
	{
		$sql = 'ALTER TABLE sge_grupo_definicion
        		ADD COLUMN	unidad_gestion Varchar;';
        
        $this->ejecutar($sql);
	}
	
	function update_grupo_definicion($where=null)
	{
		$where = isset($where) ? " WHERE $where" : '';
		
		$sql = "UPDATE	sge_grupo_definicion
        		SET		unidad_gestion = 0
        		$where;";
        
        $this->ejecutar($sql);
	}
	
	function insert_grupo_definicion($nombre, $estado, $externo, $descripcion, $unidad_gestion)
	{
		$nombre 		= $this->quote($nombre);
		$estado 		= $this->quote($estado);
		$externo 		= $this->quote($externo);
		$descripcion 	= $this->quote($descripcion);
		$unidad_gestion = $this->quote($unidad_gestion);
		
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
		
        $this->ejecutar($sql);
	}
	
	function insert_grupo_detalle($grupo, $encuestado)
	{
		$grupo 		= $this->quote($grupo);
		$encuestado = $this->quote($encuestado);
		
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
		
        $this->ejecutar($sql);
	}
	
	function insert_grupo_habilitado($grupo, $formulario_habilitado)
	{
		$grupo 				   = $this->quote($grupo);
		$formulario_habilitado = $this->quote($formulario_habilitado);
		
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
		
        $this->ejecutar($sql);
	}
	
	function delete_grupo_usuarios($grupo)
	{
		$grupo = $this->quote($grupo);
		
		$sql = "-- Eliminación de la asociación con el formulario habilitado
				DELETE
				FROM 	sge_grupo_habilitado
        		WHERE	sge_grupo_habilitado.grupo = $grupo;
        		
        		-- Eliminación de los encuestados del grupo
        		DELETE 
				FROM 	sge_grupo_detalle
        		WHERE	sge_grupo_detalle.grupo = $grupo;
        		
        		-- Eliminación de los datos del grupo
        		DELETE 
				FROM 	sge_grupo_definicion
        		WHERE	sge_grupo_definicion.grupo = $grupo;";
		
        $this->ejecutar($sql);
	}
	
	//---- Funciones de consulta --------------------------------------------------------
	
	function es_grupo_habilitado($grupo)
	{
		$grupo = $this->quote($grupo);
        
        $sql = "SELECT EXISTS
	        		(
		                SELECT	1
		                FROM	sge_grupo_habilitado
		                WHERE	sge_grupo_habilitado.grupo = $grupo
		            ) AS habilitado
	        	";
        
        $res = $this->consultar_fila($sql);
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
        
        return $this->consultar($sql);
	}
	
	function get_grupos_detalle($grupo)
	{
		$grupo = $this->quote($grupo);
		
		$sql = "SELECT	sge_grupo_detalle.grupo,
						sge_grupo_detalle.encuestado
				FROM	sge_grupo_detalle
				WHERE	sge_grupo_detalle.grupo = $grupo";
        
        return $this->consultar($sql);
	}
	
	function get_unidades_gestion()
	{
		$sql = 'SELECT	sge_unidad_gestion.unidad_gestion,
						sge_unidad_gestion.nombre
	            FROM	sge_unidad_gestion';
       	
        return $this->consultar($sql);
	}
	
	function get_secuencia_grupo_definicion()
	{
		$sql = "SELECT currval('sge_grupo_definicion_seq') AS seq;";
		$res = $this->consultar($sql);
		return $res[0]['seq'];
	}
	
	function get_unidades_gestion_grupo($grupo)
	{
		$grupo = $this->quote($grupo);
		
		$sql = "SELECT	DISTINCT sge_habilitacion.unidad_gestion
				FROM	sge_grupo_habilitado,
						sge_formulario_habilitado,
						sge_habilitacion
				WHERE	sge_grupo_habilitado.grupo = $grupo
				AND		sge_grupo_habilitado.formulario_habilitado = sge_formulario_habilitado.formulario_habilitado
				AND		sge_formulario_habilitado.habilitacion = sge_habilitacion.habilitacion";
		
		return $this->consultar($sql);
	}
	
	function get_formularios_habilitados_grupo($grupo)
	{
		$grupo = $this->quote($grupo);
		
		$sql = "SELECT	sge_grupo_habilitado.formulario_habilitado
				FROM	sge_grupo_habilitado
				WHERE	sge_grupo_habilitado.grupo = $grupo";
		
		return $this->consultar($sql);
	}
	
}

?>