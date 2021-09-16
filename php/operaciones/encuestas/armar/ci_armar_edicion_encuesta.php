<?php
class ci_armar_edicion_encuesta extends toba_ci
{
	
	protected $s__bloque;
	protected $s__bloques;
	protected $s__datos_bloque;
	protected $s__datos_preguntas;
	protected $s__datos_preguntas_carga;
	
	function conf() 
	{
		if ( !isset($this->s__bloque) ) {
			$this->pantalla()->eliminar_evento('eliminar');
		}
	}

	//-----------------------------------------------------------------------------------
	//---- bloque -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__bloque(toba_ei_formulario $form)
	{
		if ( !isset($this->s__bloque) ) {
			$form->set_modo_descripcion(false);
			$form->set_descripcion('Todo bloque nuevo se agrega en la última posición de la encuesta. Usted puede cambiar el orden en el listado de bloques.');
		}
		
		if ( isset($this->s__datos_bloque) ) { // Hubo un error
			$form->set_datos($this->s__datos_bloque);
			unset($this->s__datos_bloque);
		} elseif ( isset($this->s__bloque) ) {
			$form->set_datos(bloque::get($this->s__bloque));
		}
	}

	function evt__bloque__modificacion($datos)
	{
		$this->s__datos_bloque = $datos;
	}

	//-----------------------------------------------------------------------------------
	//---- bloques ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
	
	function conf__bloques(toba_ei_formulario_ml $form_ml)
	{
		$bloques = encuesta::get_bloques($this->get_encuesta());
		
		if ( !empty($bloques) ) {
			$this->s__bloques = $bloques;
			$form_ml->set_datos($bloques);
		}
	}

	function evt__bloques__seleccion($seleccion)
	{
		if ( isset($this->s__bloques[$seleccion]) ) {
			$this->s__bloque = $this->s__bloques[$seleccion]['bloque'];
			$this->set_pantalla('pant_preguntas');
		}
	}
	
	function evt__bloques__guardar_orden($datos)
	{
		try {
			toba::db()->abrir_transaccion();
			
			foreach ($datos as $key => $bloque) {
				if ( isset($this->s__bloques[$bloque['x_dbr_clave']]) ) { // Es un dato que se envió al cliente
					$bloque_id = $this->s__bloques[$bloque['x_dbr_clave']]['bloque'];
					$orden	   = $bloque['orden'];

					switch ($bloque['apex_ei_analisis_fila']) {
						case 'M':
							abm::modificacion('sge_bloque', array('orden' => $orden), array('bloque' => $bloque_id));
							break;
					}
				}
			}
			
			toba::db()->cerrar_transaccion();
		} catch (toba_error_db $e) {
			toba::db()->abortar_transaccion();
			toba::notificacion()->error('Ocurrió un error al intentar guardar el orden de los bloques.');
		}
	}

	//-----------------------------------------------------------------------------------
	//---- preguntas --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__preguntas(toba_ei_formulario_ml $form_ml)
	{
		if ( isset($this->s__datos_preguntas) ) { // Hubo un error, dejo todo como estaba
			$form_ml->set_datos($this->s__datos_preguntas);
			unset($this->s__datos_preguntas);
		} elseif ( isset($this->s__bloque) ) { // reload de preguntas
			$this->s__datos_preguntas_carga = encuesta::get_preguntas_bloque($this->get_encuesta(), $this->s__bloque);
			$form_ml->set_datos($this->s__datos_preguntas_carga);
		}
	}

	function evt__preguntas__modificacion($datos)
	{
		$this->s__datos_preguntas = $datos;
	}

	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__volver()
	{
		$this->controlador->volver();
	}

	function evt__guardar()
	{
		if ( empty($this->s__datos_preguntas) ) {
			throw new toba_error('El bloque debe contener al menos una pregunta.');
		}
		
		try {
			toba::db()->abrir_transaccion();
			
			$encuesta = $this->get_encuesta();
			
			if ( !isset($this->s__bloque) ) { // Es un alta de bloque + preguntas
				// Obtengo el max orden de la encuesta para insertar el bloque al final
				$this->s__datos_bloque['orden'] = bloque::get_max_orden($encuesta);
				abm::alta('sge_bloque', $this->s__datos_bloque);
				// Obtengo el id del nuevo bloque
				$bloque = toba::db()->recuperar_secuencia('sge_bloque_seq');	
			} else { // Edición de bloque + preguntas
				$bloque = $this->s__bloque;
				abm::modificacion('sge_bloque', $this->s__datos_bloque, array('bloque' => $bloque));
			}

			foreach ($this->s__datos_preguntas as $indice => $pregunta) {
				$operacion = $pregunta['apex_ei_analisis_fila'];
				// Datos que no van
				unset($pregunta['apex_ei_analisis_fila']);
				
				if ( $operacion != 'B' ) {
					unset($pregunta['x_dbr_clave']);
				}
				
				switch ($operacion) {
					case 'A':
						unset($pregunta['encuesta_definicion']); // Si es un alta este dato no va (serial)
						
						$pregunta['bloque']	  = $bloque;
						$pregunta['encuesta'] = $encuesta;
						
						abm::alta('sge_encuesta_definicion', $pregunta);
						break;
					case 'M':
						$definicion = $pregunta['encuesta_definicion'];
						unset($pregunta['encuesta_definicion']); // No envío el serial a actualizar

						// TODO: se podría optimizar con una segunda estructura para analizar cambios en ciertos campos
						
						abm::modificacion('sge_encuesta_definicion', $pregunta, array('encuesta_definicion' => $definicion));
						break;
					case 'B':
						$definicion = $this->s__datos_preguntas_carga[$indice]['definicion'];
						
						abm::baja('sge_encuesta_definicion', array('encuesta_definicion' => $definicion));
						break;
				}
			}
			
			toba::db()->cerrar_transaccion();
			
			unset($this->s__datos_preguntas); // Si todo sale bien esto se limpia para luego hacer un reload de la base
			$this->s__bloque = $bloque; // Lo necesito para hacer el reload de la base
		} catch (toba_error_db $e) {
			toba::db()->abortar_transaccion();
			toba::notificacion()->error('Ocurrió un error al intentar dar de alta un bloque.');
		}
	}
	
	function evt__eliminar()
	{
		try {
			toba::db()->abrir_transaccion();
			
			$encuesta = $this->get_encuesta();
			$bloque   = $this->s__bloque;
			
			abm::baja('sge_encuesta_definicion', array('encuesta' => $encuesta, 'bloque' => $bloque));
			abm::baja('sge_bloque', array('bloque' => $bloque));
			
			toba::db()->cerrar_transaccion();
			$this->evt__cancelar();
		} catch (toba_error_db $e) {
			toba::db()->abortar_transaccion();
			toba::notificacion()->error('Ocurrió un error al intentar eliminar el bloque y sus preguntas.');
		}
	}

	function evt__cancelar()
	{
		unset($this->s__bloque);
		unset($this->s__datos_bloque);
		unset($this->s__datos_preguntas);
		$this->set_pantalla('pant_bloques');
	}
	
	function evt__nuevo_bloque()
	{
		$this->set_pantalla('pant_preguntas');
	}

	//-- Auxiliares
	
	function get_encuesta()
	{
		return $this->controlador->get_encuesta();
	}

}
?>