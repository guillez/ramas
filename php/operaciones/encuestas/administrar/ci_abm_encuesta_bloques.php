<?php
use ext_bootstrap\componentes\bootstrap_ci;

class ci_abm_encuesta_bloques extends bootstrap_ci
{	
	protected $s__bloque;
	protected $s__bloques;
	protected $s__datos_bloque;
	protected $s__datos_preguntas;
	protected $s__datos_preguntas_carga;
    
    const PREGUNTA_TIPO_FECHA_CALCULO_ANIOS = 16;
    const PREGUNTA_LOCALIDAD_Y_CP = 18;

	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf()
	{
		if ($this->get_id_pantalla() == 'pant_bloques') {
        	$this->controlador()->pantalla('pant_bloques')->eliminar_evento('volver');
        	$this->controlador()->pantalla('pant_bloques')->eliminar_evento('cancelar_bloque');
        }
	}
	
	function conf__pant_preguntas(toba_ei_pantalla $pantalla)
	{
		if (!isset($this->s__bloque)) {
			$pantalla->eliminar_evento('eliminar');
		} else {
            //Si el bloque existe pero no es editable, quitar el boton de eliminar
            if (toba::consulta_php('consultas_encuestas')->es_bloque_no_editable($this->s__bloque)) {
                $pantalla->evento('eliminar')->anular();
            }
        }
	}

	//-----------------------------------------------------------------------------------
	//---- bloque -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__bloque(toba_ei_formulario $form)
	{
		if (!isset($this->s__bloque)) {
			$form->set_modo_descripcion(false);
			$form->set_descripcion('Todo bloque nuevo se agrega en la última posición de la encuesta. Usted puede cambiar el orden en el listado de bloques.');
		} else {
            // Si el bloque no es editable, no dejar modificar datos
            if (toba::consulta_php('consultas_encuestas')->es_bloque_no_editable($this->s__bloque)) {
                $form->set_solo_lectura();
                $form->set_modo_descripcion(false);
                $es_no_editable = toba::consulta_php('consultas_encuestas')->es_encuesta_no_editable($this->get_encuesta());
                if (!$es_no_editable) {
                    $form->set_descripcion('El nombre y la descripción de este bloque no se pueden modificar. Ya está siendo utilizado en otra encuesta.');
                }
            }            
        }

		if (isset($this->s__datos_bloque)) { // Hubo un error
			$form->set_datos($this->s__datos_bloque);
			unset($this->s__datos_bloque);
		} elseif (isset($this->s__bloque)) {
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
		$bloques = encuesta::get_bloques($this->get_encuesta(), 100);
		
		if (!empty($bloques)) {
			$this->s__bloques = $bloques;
			$form_ml->set_datos($bloques);
		}
        
        // Si la encuesta no es editable, eliminar botones de eventos que permiten modificar
        if (toba::consulta_php('consultas_encuestas')->es_encuesta_no_editable($this->get_encuesta())) {
            $form_ml->evento('nuevo_bloque')->anular();
            $form_ml->evento('copiar')->anular();
        }
	}

	function evt__bloques__seleccion($seleccion)
	{
		if (isset($this->s__bloques[$seleccion])) {
			$this->s__bloque = $this->s__bloques[$seleccion]['bloque'];
			$this->set_pantalla('pant_preguntas');
		}
	}
    
    function evt__bloques__copiar($seleccion)
	{
		if (isset($this->s__bloques[$seleccion])) {
            encuesta::copiar_bloque($this->s__bloques[$seleccion]['bloque'], $this->get_encuesta());
		}
	}
	
	function evt__bloques__nuevo_bloque()
	{
		$this->set_pantalla('pant_preguntas');
	}
	
	function evt__bloques__modificacion($datos)
	{
		try {
			toba::db()->abrir_transaccion();
			
			foreach ($datos as $key => $bloque) {
				if (isset($this->s__bloques[$bloque['x_dbr_clave']])) { // Es un dato que se envió al cliente
					$bloque_id = $this->s__bloques[$bloque['x_dbr_clave']]['bloque'];
					$orden	   = $bloque['orden'];
					
					if ($bloque['apex_ei_analisis_fila'] == 'M') {
						abm::modificacion('sge_bloque', array('orden' => $orden), array('bloque' => $bloque_id));
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
		if (isset($this->s__datos_preguntas)) { // Hubo un error, dejo todo como estaba
			$form_ml->set_datos($this->s__datos_preguntas);
			unset($this->s__datos_preguntas);
		} elseif (isset($this->s__bloque)) { // reload de preguntas
            $this->s__datos_preguntas_carga = encuesta::get_preguntas_bloque($this->get_encuesta(), $this->s__bloque, null, null, true);
			$form_ml->set_datos($this->s__datos_preguntas_carga);
		}
        
        if (toba::consulta_php('consultas_encuestas')->es_encuesta_no_editable($this->get_encuesta())) {
            $this->evento('eliminar')->anular();
            $form_ml->set_solo_lectura();
            $form_ml->desactivar_agregado_filas();
            $form_ml->desactivar_ordenamiento_filas();
        }
	}

	function evt__preguntas__modificacion($datos)
	{
		if (empty($datos)) {
			throw new toba_error('El bloque debe contener al menos una pregunta.');
		}
        
        $encuesta = $this->get_encuesta();
        
        if (!isset($this->s__bloque)) { // Es un alta de bloque + preguntas
            // Obtengo el max orden de la encuesta para insertar el bloque al final
            $this->s__datos_bloque['orden'] = bloque::get_max_orden($encuesta);
            abm::alta('sge_bloque', $this->s__datos_bloque);
            $bloque = toba::db()->recuperar_secuencia('sge_bloque_seq');	
        } else { // Edición de bloque + preguntas
            $bloque = $this->s__bloque;
            abm::modificacion('sge_bloque', $this->s__datos_bloque, array('bloque' => $bloque));
        }
            		
		try {
			toba::db()->abrir_transaccion();
			$orden = 1;
            $modelo_act_encuestas = kolla::abm('act_encuestas');
            $modelo_act_encuestas->eliminar_preguntas_ocultas($encuesta, $bloque);
            
            foreach ($datos as $indice => $pregunta) {
				$operacion = $pregunta['apex_ei_analisis_fila'];
				unset($pregunta['apex_ei_analisis_fila']);
				
				if ($operacion != 'B') {
					unset($pregunta['x_dbr_clave']);
				}
                
				switch ($operacion) {
					case 'A':
						unset($pregunta['encuesta_definicion']);
						$pregunta['orden']    = $orden++;
                        $pregunta['bloque']	  = $bloque;
						$pregunta['encuesta'] = $encuesta;
                        abm::alta('sge_encuesta_definicion', $pregunta);
                        
                        $datos_pregunta = toba::consulta_php('co_preguntas')->get_pregunta($pregunta['pregunta']);
                        
                        if ($datos_pregunta['componente_numero'] == self::PREGUNTA_TIPO_FECHA_CALCULO_ANIOS || $datos_pregunta['componente_numero'] == self::PREGUNTA_LOCALIDAD_Y_CP) {
                            $this->crear_pregunta_dependiente($pregunta['pregunta'], $orden++, $bloque, $encuesta);
                        }
                        
						break;
					case 'M':
                        $definicion = $pregunta['encuesta_definicion'];
                        unset($pregunta['encuesta_definicion']);
                        $pregunta['orden'] = $orden++;
                        abm::modificacion('sge_encuesta_definicion', $pregunta, array('encuesta_definicion' => $definicion));
                        $datos_pregunta = toba::consulta_php('co_preguntas')->get_pregunta($pregunta['pregunta']);

                        if ($datos_pregunta['componente_numero'] == self::PREGUNTA_TIPO_FECHA_CALCULO_ANIOS || $datos_pregunta['componente_numero'] == self::PREGUNTA_LOCALIDAD_Y_CP) {
                            $this->crear_pregunta_dependiente($pregunta['pregunta'], $orden++, $bloque, $encuesta);
                        }
                        
						break;
					case 'B':
						$definicion = $this->s__datos_preguntas_carga[$indice]['encuesta_definicion'];
                        
                        if (toba::consulta_php('co_preguntas_dependientes')->es_pregunta_dependiente($definicion)) {
                            toba::db()->abortar_transaccion();
                            throw new toba_error('No es posible eliminar Preguntas Dependientes definidas anteriormente.');
                        }
                        
						abm::baja('sge_encuesta_definicion', array('encuesta_definicion' => $definicion));
                        
						break;
				}
			}
			
			toba::db()->cerrar_transaccion();

			$this->s__bloque = $bloque; // Lo necesito para hacer el reload de la base
		} catch (toba_error_db $e) {
			toba::db()->abortar_transaccion();
            toba::logger()->error($e->get_sql_ejecutado());
			toba::notificacion()->error('Ocurrió un error al intentar dar de alta un bloque.');
		}
	}
    
    function valida_duplicados_preg_fecha_calculo_anios_y_localidad_y_cp($datos, $bloque)
    {
        //Validación dentro del bloque
        $elementos = array();
		foreach ($datos as $clave => $valor) {
            if ($valor['apex_ei_analisis_fila'] != 'B') {
                $pregunta = toba::consulta_php('co_preguntas')->get_pregunta($valor['pregunta']);
                if ($pregunta['componente_numero'] == self::PREGUNTA_TIPO_FECHA_CALCULO_ANIOS || $pregunta['componente_numero'] == self::PREGUNTA_LOCALIDAD_Y_CP) {
                    if (in_array($valor['pregunta'], $elementos)) {
                        $this->s__datos_preguntas = $datos;
                        return false;
                    } else {
                        $elementos[] = $valor['pregunta'];
                    }
                }
            }
		}
        
        //Validación con los demas bloques
        $encuesta = toba::consulta_php('consultas_encuestas')->get_datos_preguntas($this->get_encuesta());
        foreach ($encuesta as $clave => $valor) {
            if ($valor['bloque'] != $bloque && in_array($valor['pregunta'], $elementos)) {
                $this->s__datos_preguntas = $datos;
                return false;
            }
		}
        
        //Validación OK
        return true;
    }
    
    function crear_pregunta_dependiente($pregunta, $orden, $bloque, $encuesta)
    {
        $preg_dep = toba::consulta_php('co_preguntas')->get_pregunta_dependiente_calculo_anios($pregunta);
        
        if (empty($preg_dep)) {
			throw new toba_error('Existe una pregunta compuesta sin su correspondiente pregunta receptora. Por favor contacte a su administrador.');
		}
        
        $preg_calculo_anios                = array();
        $preg_calculo_anios['orden']       = $orden;
        $preg_calculo_anios['bloque']      = $bloque;
        $preg_calculo_anios['encuesta']    = $encuesta;
        $preg_calculo_anios['pregunta']    = $preg_dep['pregunta'];
        $preg_calculo_anios['obligatoria'] = 'N';
        abm::alta('sge_encuesta_definicion', $preg_calculo_anios);
    }

	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function cancelar()
	{
		unset($this->s__bloque);
		unset($this->s__datos_bloque);
		unset($this->s__datos_preguntas);
        unset($this->s__datos_preguntas_carga);
        
		$this->set_pantalla('pant_bloques');
	}
	
	function evt__eliminar()
	{
		if (isset($this->s__bloque)) {
			try {
				toba::db()->abrir_transaccion();

				$encuesta = $this->get_encuesta();
				$bloque   = $this->s__bloque;
                
                if (isset($this->s__datos_preguntas)) {
                    $preguntas = $this->s__datos_preguntas;
                } else {
                    $preguntas = $this->s__datos_preguntas_carga;
                }
                
                /*
                 * Si alguna de las pregunta del bloque se encuentra definida
                 * como pregunta dependiente entonces no es posible eliminarlo
                 */
                foreach ($preguntas as $pregunta) {
                    if (toba::consulta_php('co_preguntas_dependientes')->es_pregunta_dependiente($pregunta['encuesta_definicion'])) {
                        toba::db()->abortar_transaccion();
                        throw new toba_error('No es posible eliminar un bloque que contiene Preguntas Dependientes definidas anteriormente.');
                    }
                }

				abm::baja('sge_encuesta_definicion', array('encuesta' => $encuesta, 'bloque' => $bloque));
				abm::baja('sge_bloque', array('bloque' => $bloque));

				toba::db()->cerrar_transaccion();
				$this->cancelar();
			} catch (toba_error_db $e) {
				toba::db()->abortar_transaccion();
				toba::notificacion()->error('Ocurrió un error al intentar eliminar el bloque y sus preguntas.');
			}
		}
		$this->cancelar();
	}

	//-- Auxiliares
	
	function get_encuesta()
	{
		return $this->controlador->get_encuesta();
	}

	public function resetear()
	{
        unset($this->s__bloque);
        unset($this->s__bloques);
        unset($this->s__datos_bloque);
        unset($this->s__datos_preguntas);
        unset($this->s__datos_preguntas_carga);
	}
    
    function get_preguntas_para_combo($codigo=null)
    {
        $datos = $this->controlador()->get_tabla()->get();
        return toba::consulta_php('consultas_encuestas')->get_preguntas_para_combo($codigo, $datos['unidad_gestion']);
    }

}
?>