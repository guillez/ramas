<?php

class ci_exportar_datos extends toba_ci
{
	protected $s__archivo_reporte;
	protected $s__datos_config_reporte;
	protected $respuestas;	
	
	//-----------------------------------------------------------------------------------
	//---- Pantalla Selección -----------------------------------------------------------
	//-----------------------------------------------------------------------------------
	
	//---- datos_reporte ----------------------------------------------------------------
	
	function conf__datos_reporte(toba_ei_formulario $form)
	{
		//Datos para el nombre del archivo
		$institucion = toba::consulta_php('consultas_mgi')->get_institucion();
		$cod_institucion = '';
		$cod_carrera = 'NA';
		
		if (empty($institucion) || ($institucion[0]['institucion_araucano'] == null)) {
			$form->set_solo_lectura();
			$this->pantalla('seleccion')->evento('exportar')->desactivar();
			$this->pantalla('seleccion')->evento('descargar')->desactivar();
			toba::notificacion()->error('Faltan datos de la institución. Debe indicar el código de Araucano.</br>
			Puede cargar estos datos mediante la operación Institución Local en el menú Maestros.');			
		} else {
			if (isset($this->s__datos_config_reporte)) {
				$form->set_datos($this->s__datos_config_reporte);
			} else {
				$cod_institucion = $institucion[0]['institucion_araucano'];
				$anioymes = date('Ym');	
				$nombre_archivo = 'ing-'.$cod_institucion.'-'.$cod_carrera.'-'.$anioymes.'kol.txt';
				$datos['institucion_araucano'] = $cod_institucion;
				$datos['archivo'] = $nombre_archivo;
				$form->set_datos($datos);
				$form->set_solo_lectura(array('institucion_araucano', 'archivo'));
				$this->pantalla('seleccion')->evento('descargar')->desactivar();
			}
		}
	}
	
	function evt__datos_reporte__modificacion($datos)
	{
		$this->s__datos_config_reporte = $datos;
	}
	
	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__exportar()
	{
		//Path reporte
		$path_txts = toba::proyecto()->get_path().'/procesos/reportes/';
		$this->s__archivo_reporte = $path_txts.$this->s__datos_config_reporte['archivo'];
	
		//Obtener los datos de los alumnos a incluir en el reporte segun el filtro
		$filtro = $this->s__datos_config_reporte;
		$resultados = toba::consulta_php('consultas_relevamiento_ingenierias')->get_resultados($filtro);
		$cant_alumnos = count($resultados);
		$handle = fopen($this->s__archivo_reporte, 'w');
		
		for ($i = 0; $i < $cant_alumnos; $i++) {
			
			//Son 18 datos + los datos encuestado y usuario, el dato 12 (nro de documento) se debe codificar en base64
			$datos_parte1 = array_slice($resultados[$i], 0, 11);
			$datos_nrodoc = array_slice($resultados[$i], 11, 1);
			$datos_parte2 = array_slice($resultados[$i], 12, 6);
			
			//Se empieza a armar la linea para la persona
			$linea  = implode('|', $datos_parte1);
			$linea .= '|'.base64_encode($datos_nrodoc['numero_documento']);
			$linea .= '|'.implode('|', $datos_parte2);
			$alumno = array_slice($resultados[$i], 18, 2);
			
			//Determinar las respuestas que dio en los formularios
			$respuestas = $this->datos_censales($alumno);
			
			//Agregar los datos a la linea
			$linea .= $respuestas;
			 
			//Agregar al archivo de salida
			fwrite($handle, $linea."\n");
		}
		
		//Cierro el puntero al archivo abierto
		fclose($handle);
		
		if (empty($resultados)) {
			
			//Se emite mensaje de error: no hay personas de ingeniería relevamiento para exportar 
			toba::notificacion()->agregar($this->get_mensaje('exportacion_sin_usuarios'), 'error');
		} else {
			
			//Se emite mensaje de información: archivo txt se generó correctamente
			toba::notificacion()->agregar($this->get_mensaje('exportacion_ok'), 'info');
		}
	}
	
	function evt__descargar()
	{
		if (file_exists($this->s__archivo_reporte)) {
			toba::memoria()->set_dato('nombre_archivo', substr($this->s__datos_config_reporte['archivo'], 0, -4));
			toba::vinculador()->navegar_a(null, '40000129');
		} else {
			toba::notificacion()->agregar('El archivo no fue creado o ha sido borrado.', 'error');
		}
	}

	//------------------------------------------------------------------------------
	//-- (TXT) POR ENCUESTADO - CON PREGUNTAS DE RESPUESTA MULTIPLE ----------------
	//------------------------------------------------------------------------------

	function datos_censales($alumno)
	{
        $formulario_habilitado = $this->s__datos_config_reporte['formulario_habilitado'];
		//$habilitacion = $this->s__datos_config_reporte['habilitacion'];
        
		$formulario_terminado = toba::consulta_php('consultas_relevamiento_ingenierias')->get_formulario_terminado($formulario_habilitado, $alumno['encuestado']);
		
		if (!empty($formulario_terminado)) {
			$formulario_habilitado = $formulario_terminado[0]['formulario_habilitado'];
			$this->respuestas = toba::consulta_php('consultas_relevamiento_ingenierias')->get_respuestas_completas_formulario_habilitado_encuestado($formulario_habilitado, $alumno['encuestado']);
			
			//Respuestas Datos Censales Principales
			$rta_estado_civil 	= $this->get_respuestas(247);
			$rta_cant_hijos 	= $this->get_respuestas(248);
			$rta_cant_fliares 	= $this->get_respuestas(249);
			$rta_calle			= $this->get_respuestas(251);
			$rta_numero			= $this->get_respuestas(252);
			$rta_piso			= $this->get_respuestas(253);
			$rta_depto			= $this->get_respuestas(254);
			$rta_unidad			= $this->get_respuestas(255);
			$rta_localidad		= $this->get_respuestas(256);
			$rta_calle_proc		= $this->get_respuestas(258);
			$rta_numero_proc	= $this->get_respuestas(259);
			$rta_piso_proc		= $this->get_respuestas(260);
			$rta_depto_proc		= $this->get_respuestas(261);
			$rta_unidad_proc	= $this->get_respuestas(262);
			$rta_localidad_proc	= $this->get_respuestas(263);
			
			//Respuestas Datos Económicos - Financiamiento estudios
			$rtas_fuente = $this->get_respuestas(265, true);
			$rtas_beca	 = $this->get_respuestas(266, true);
			
			//Situación laboral
			$rta_cond_activ		  = $this->get_respuestas(268);
			$rta_es_usted		  = $this->get_respuestas(269);
			$rta_ocupacion_es	  = $this->get_respuestas(270);
			$rta_horas_semanales  = $this->get_respuestas(271);
			$rta_rel_trab_carrera = $this->get_respuestas(272);
			
			//Situación del padre
			$rta_nivel_est_padre	 = $this->get_respuestas(274);
			$rta_vive_padre			 = $this->get_respuestas(275);
			$rta_cond_activ_padre	 = $this->get_respuestas(276);
			$rta_es_usted_padre		 = $this->get_respuestas(277);
			$rta_ocupacion_es_padre	 = $this->get_respuestas(278);
			$rta_si_no_trabaja_padre = $this->get_respuestas(279);
			
			//Situación de la madre
			$rta_nivel_est_madre	 = $this->get_respuestas(281);
			$rta_vive_madre			 = $this->get_respuestas(282);
			$rta_cond_activ_madre	 = $this->get_respuestas(283);
			$rta_es_usted_madre		 = $this->get_respuestas(284);
			$rta_ocupacion_es_madre	 = $this->get_respuestas(285);
			$rta_si_no_trabaja_madre = $this->get_respuestas(286);
			
			//Se arma la linea con las respuestas - Datos Censales Principales
			$linea  = '|'.$rta_estado_civil['respuesta_valor'];
			$linea .= '|'.$rta_cant_hijos['respuesta_valor'];
			$linea .= '|'.$rta_cant_fliares['respuesta_valor'];
			$linea .= '|'.$rta_calle['respuesta_valor'].'|'.$rta_numero['respuesta_valor'].'|'.$rta_piso['respuesta_valor'];
			$linea .= '|'.$rta_depto['respuesta_valor'].'|'.$rta_unidad['respuesta_valor'];
			$linea .= '|'.empty($rta_localidad) ? '' : $rta_localidad['respuesta_valor'];
			$linea .= '|'.$rta_calle_proc['respuesta_valor'].'|'.$rta_numero_proc['respuesta_valor'].'|'.$rta_piso_proc['respuesta_valor'];
			$linea .= '|'.$rta_depto_proc['respuesta_valor'].'|'.$rta_unidad_proc['respuesta_valor'];
			$linea .= '|'.empty($rta_localidad_proc) ? '' : $rta_localidad_proc['respuesta_valor'];
			
			//Respuestas Datos Económicos - Financiamiento estudios
			$linea .= '|'.$this->get_lista_array_fuente($rtas_fuente).'|'.$this->get_lista_array_beca($rtas_beca);
			
			//Situación laboral
			$linea .= '|'.$rta_cond_activ['respuesta_valor'];
			$linea .= '|'.empty($rta_es_usted) 		   ? '' : $rta_es_usted['respuesta_valor'];
			$linea .= '|'.empty($rta_ocupacion_es) 	   ? '' : $rta_ocupacion_es['respuesta_valor'];
			$linea .= '|'.empty($rta_horas_semanales)  ? '' : $rta_horas_semanales['respuesta_valor'];
			$linea .= '|'.empty($rta_rel_trab_carrera) ? '' : $rta_rel_trab_carrera['respuesta_valor'];
			
			//Situación del padre
			$linea .= '|'.$rta_nivel_est_padre['respuesta_valor'];
			$linea .= '|'.empty($rta_vive_padre) 		  ? '' : $rta_vive_padre['respuesta_valor'];
			$linea .= '|'.empty($rta_cond_activ_padre) 	  ? '' : $rta_cond_activ_padre['respuesta_valor'];
			$linea .= '|'.empty($rta_es_usted_padre) 	  ? '' : $rta_es_usted_padre['respuesta_valor'];
			$linea .= '|'.empty($rta_ocupacion_es_padre)  ? '' : $rta_ocupacion_es_padre['respuesta_valor'];
			$linea .= '|'.empty($rta_si_no_trabaja_padre) ? '' : $rta_si_no_trabaja_padre['respuesta_valor'];
			
			//Situación de la madre
			$linea .= '|'.$rta_nivel_est_madre['respuesta_valor'];
			$linea .= '|'.empty($rta_vive_madre) 		  ? '' : $rta_vive_madre['respuesta_valor'];
			$linea .= '|'.empty($rta_cond_activ_madre)	  ? '' : $rta_cond_activ_madre['respuesta_valor'];
			$linea .= '|'.empty($rta_es_usted_madre) 	  ? '' : $rta_es_usted_madre['respuesta_valor'];
			$linea .= '|'.empty($rta_ocupacion_es_madre)  ? '' : $rta_ocupacion_es_madre['respuesta_valor'];
			$linea .= '|'.empty($rta_si_no_trabaja_madre) ? '' : $rta_si_no_trabaja_madre['respuesta_valor'];
			
			//Fecha de terminación del formulario
			$linea .= '|'.$formulario_terminado[0]['fecha_terminado'];
		} else {
			$linea = '|||||||||||||||||||||||||||||||';
		}
		
		//Se retornan las respuestas del alumno
		return $linea;
	}
	
	function get_respuestas($encuesta_definicion, $es_multiple = false)
	{
		$respuestas = array();
		
		foreach ($this->respuestas as $respuesta) {
			if ($respuesta['encuesta_definicion'] == $encuesta_definicion) {
				if (isset($respuesta['respuesta_valor'])) {
					if ($es_multiple) {
						$opcion = array();
						$opcion['respuesta_codigo'] = $respuesta['respuesta_codigo'];
						$opcion['respuesta_valor'] = $respuesta['respuesta_valor'];
						$respuestas[] = $opcion;
					} else {
						return $respuesta;
					}
				}
			}
		}
		
		return $respuestas;
	}
	
	function get_lista_array_fuente($array)
	{
		$fuentes = kolla_arreglos::aplanar_matriz_sin_nulos($array, 'respuesta_codigo');
		$fuente1 = in_array(3069, $fuentes) ? 'S' : 'N';
		$fuente2 = in_array(3070, $fuentes) ? 'S' : 'N';
		$fuente3 = in_array(3071, $fuentes) ? 'S' : 'N';
		$fuente4 = in_array(3072, $fuentes) ? 'S' : 'N';
		$fuente5 = in_array(3073, $fuentes) ? 'S' : 'N';
		$fuente6 = in_array(3074, $fuentes) ? 'S' : 'N';
		
		return $fuente1.'|'.$fuente2.'|'.$fuente3.'|'.$fuente4.'|'.$fuente5.'|'.$fuente6;
	}
	
	function get_lista_array_beca($array)
	{
		$becas = kolla_arreglos::aplanar_matriz_sin_nulos($array, 'respuesta_codigo');
		$beca1 = in_array(3075, $becas) ? 'S' : 'N';
		$beca2 = in_array(3076, $becas) ? 'S' : 'N';
		$beca3 = in_array(3077, $becas) ? 'S' : 'N';
		
		return $beca1.'|'.$beca2.'|'.$beca3;
	}
	
}
?>