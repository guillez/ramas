<?php
class ci_exportar_o_importar_encuestas extends ci_navegacion
{
    protected $ejecucion_ok;
    protected $mensaje_error;
    protected $s__datos;
    
    //-----------------------------------------------------------------------------------
	//---- PANTALLA INICIAL -------------------------------------------------------------
	//-----------------------------------------------------------------------------------
    
    //---- formulario -------------------------------------------------------------------
	
	function evt__formulario__modificacion($datos)
	{
        $this->s__datos = $datos;
        
        if ($datos['accion'] == 'E') {
            $this->accion_exportar();
        } else {
            $this->accion_importar();
        }
        
        $this->set_pantalla('pant_resultados');
	}
    
    function accion_exportar()
    {
        try {
            //Creo el archivo txt y le agrego como contenido la encuesta
            $nombre_archivo = nombre_archivo::instancia()->get_nombre($this->s__datos['encuesta']);
            toba::memoria()->set_dato('nombre_archivo', $nombre_archivo);
            $path_archivo = $this->get_path_archivo($nombre_archivo);
            $datos_encuesta = $this->get_datos_encuesta();
            file_put_contents($path_archivo , $datos_encuesta);
            chmod($path_archivo, 0600);
            $this->ejecucion_ok = true;
        } catch (toba_error $e) {
            $this->ejecucion_ok = false;
            $this->mensaje_error = $e->getMessage();
        }
    }
    
    function get_path_archivo($nombre_archivo)
    {
        return toba::proyecto()->get_path().'/procesos/scripts/'.$nombre_archivo;
    }
    
    function get_datos_encuesta()
    {
        //Creación del objeto creacional (valga la redundancia) de la encuesta
        $nom_tabla_asoc = nombre_archivo::instancia()->get_nombre_tabla_asociada($this->s__datos['encuesta']);
        $datos_encuesta = new datos_encuesta($this->s__datos['encuesta'], $nom_tabla_asoc);
        
        //Creación del esquema
        $salida_sql  = $datos_encuesta->crear_esquema();
        
        //Creación de las tablas
        $salida_sql .= $datos_encuesta->crear_tabla_sge_componente_pregunta();
        $salida_sql .= $datos_encuesta->crear_tabla_sge_pregunta();
        $salida_sql .= $datos_encuesta->crear_tabla_sge_respuesta();
        $salida_sql .= $datos_encuesta->crear_tabla_sge_pregunta_respuesta();
        $salida_sql .= $datos_encuesta->crear_tabla_sge_bloque();
        $salida_sql .= $datos_encuesta->crear_tabla_sge_encuesta_atributo();
        $salida_sql .= $datos_encuesta->crear_tabla_sge_encuesta_definicion();
        $salida_sql .= $datos_encuesta->crear_tabla_sge_pregunta_dependencia();
        $salida_sql .= $datos_encuesta->crear_tabla_sge_pregunta_dependencia_definicion();
        $salida_sql .= $datos_encuesta->crear_tabla_sge_pregunta_cascada();
        
        //Inserción de datos
        $salida_sql .= $datos_encuesta->insertar_datos_sge_componente_pregunta();
        $salida_sql .= $datos_encuesta->insertar_datos_sge_pregunta();
        $salida_sql .= $datos_encuesta->insertar_datos_sge_respuesta();
        $salida_sql .= $datos_encuesta->insertar_datos_sge_pregunta_respuesta();
        $salida_sql .= $datos_encuesta->insertar_datos_sge_bloque();
        $salida_sql .= $datos_encuesta->insertar_datos_sge_encuesta_atributo();
        $salida_sql .= $datos_encuesta->insertar_datos_sge_encuesta_definicion();
        $salida_sql .= $datos_encuesta->insertar_datos_sge_pregunta_dependencia();
        $salida_sql .= $datos_encuesta->insertar_datos_sge_pregunta_dependencia_definicion();
        $salida_sql .= $datos_encuesta->insertar_datos_sge_pregunta_cascada();
        
        //Creación de la estructura de tablas asociadas
        $salida_sql .= $datos_encuesta->crear_estructura_tablas_asociadas();
        
        //Eliminación del esquema
        $salida_sql .= $datos_encuesta->eliminar_esquema();
        
        //Retorno del string sql que crea la estructura
        return $salida_sql;
    }
    
    function accion_importar()
    {
        //Obtengo el contenido del archivo
        $string_sql   = file_get_contents($this->s__datos['archivo']['tmp_name']);
        $array_sql    = explode('<END_STATEMENT>', $string_sql);
        $ultima_clave = array_pop(array_keys($array_sql)) - 1;
        $this->ejecucion_ok = false;
        
        //Creo el esquema temporal con las tablas y datos correspondientes
        try {
            foreach ($array_sql as $clave => $sql) {
                if ($clave != $ultima_clave) {
                    kolla_db::consultar($sql);
                }
            }
        } catch (toba_error $e) {
            $this->mensaje_error = $e->getMessage();
        }
        
        //Importo la encuesta desde el esquema temporal al esquema definitivo
        act_encuestas::importar_encuesta($this->s__datos['unidad_gestion']);

        //Elimino el esquema temporal
        kolla_db::consultar($array_sql[$ultima_clave]);
        $this->ejecucion_ok = true;
    }
    
    //-----------------------------------------------------------------------------------
	//---- PANTALLA RESULTADOS ----------------------------------------------------------
	//-----------------------------------------------------------------------------------
    
    //---- Configuraciones --------------------------------------------------------------
	
	function conf__pant_resultados(toba_ei_pantalla $pantalla)
	{
        if ($this->s__datos['accion'] == 'I') {
            $pantalla->eliminar_evento('ejecutar');
        }
        
        $this->ejecucion_ok ? $pantalla->set_descripcion('La ejecución finalizó correctamente.') :
                              $pantalla->set_descripcion('Hubo un error', 'error');
	}
    
    //---- form_resultados --------------------------------------------------------------
	
	function conf__form_resultados(bootstrap_formulario $form)
	{
        if ($this->s__datos['accion'] == 'I') {
            if ($this->ejecucion_ok) {
                $estado = 'Importación OK.';
            } else {
                $estado = 'Importación con ERROR. Detalle: '.$this->mensaje_error;
            }
        } else {
            if ($this->ejecucion_ok) {
                $estado = ' El script se generó correctamente de manera <b>temporal</b>. Se elaboraron las sentencias para las creaciones de tablas e inserciones para: <br>
                            <ul>
                                <li>Creación de la encuesta.</li>
                                <li>Datos externos.</li>
                                <li>Dependencias entre preguntas.</li>
                                <li>Preguntas en estilo cascada.</li>
                            </ul>
                            Por favor, presione el botón para realizar su descarga.';
            } else {
                $estado = 'ERROR: No se pudo generar el script. Detalle: '.$this->mensaje_error;
            }
        }
        
        $form->ef('resultados')->set_estado($estado);
	}
    
    function servicio__ejecutar()
    {
        $nombre_archivo = toba::memoria()->get_dato('nombre_archivo');
        $path_archivo = $this->get_path_archivo($nombre_archivo);
		
		if (file_exists($path_archivo)) {
			header('Content-Description: File Transfer');
			header('Content-Type: text/plain');
			header('Content-Disposition: attachment; filename="'.$nombre_archivo.'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: '.filesize($path_archivo));
			readfile($path_archivo);
            unlink($path_archivo);
		}
    }
    
    //---- Eventos ----------------------------------------------------------------------
	
	function evt__volver()
	{
        $nombre_archivo = toba::memoria()->get_dato('nombre_archivo');
        $path_archivo = $this->get_path_archivo($nombre_archivo);
		
		if (file_exists($path_archivo)) {
			unlink($path_archivo);
		}
        
        unset($this->s__datos);
        $this->set_pantalla('pant_inicial');
	}
	
    //-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		
		//---- Eventos ---------------------------------------------
		
		{$this->objeto_js}.evt__enviar_datos = function()
		{
			var accion = this.dep('formulario').ef('accion').get_estado();
			
			if (accion == 'E') {
				var mensaje = 'Esta a punto de exportar una encuesta a un archivo de texto ¿Desea continuar?';
			} else {
				var mensaje = 'Esta a punto de importar una encuesta a una Unidad de Gestión ¿Desea continuar?';
			}
			
			return confirm(mensaje);
		}
		";
	}

}
?>