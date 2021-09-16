<?php
use ext_bootstrap\componentes\bootstrap_ci;

class ci_abm_encuesta_edicion extends bootstrap_ci
{
    protected $s__datos_temp;
    
	function conf() 
	{
		if (!$this->encuesta_seleccionada()) {
			$this->pantalla()->eliminar_evento('eliminar');
			//Se desactiva el tab de bloques ya que necesita de una encuesta
			$this->pantalla()->tab('pant_bloques')->desactivar();
		} else {
            if (toba::consulta_php('consultas_encuestas')->es_encuesta_no_editable($this->get_encuesta())) {
                //La encuesta no se puede editar
                $this->pantalla('pant_atributos')->set_descripcion('La encuesta no se puede editar.');
                
                //Seteo de la encuesta como implementada
                $this->modelo_act['encuestas'] = kolla::abm('act_encuestas');
                $this->modelo_act['encuestas']->actualizar_implementada_encuesta($this->get_encuesta(), 'S');
            }
        }
	}
	
	function get_encuesta()
	{
		return $this->controlador()->get_encuesta();
	}
	
	function encuesta_seleccionada()
	{
		$encuesta = $this->get_encuesta();
		return isset($encuesta);
	}
	
	/**
	 * @return toba_datos_tabla
	 */
	function get_tabla()
	{
		return $this->dependencia('dt_atributos');
	}
    
    function get_tabla_formulario_atributo()
	{
		return $this->dep('datos')->tabla('atributos');
	}
    
    function get_tabla_formulario_definicion()
	{
		return $this->dep('datos')->tabla('definicion');
	}
    
    function get_relacion_formulario()
	{
		return $this->dep('datos');
	}
	
	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__cancelar() 
	{
		$this->controlador()->resetear();
        unset($this->s__datos_temp);
	}
	
	/**
	 * Los eventos Cancelar bloque y Listado de bloques funcionan de igual manera, sólo que
	 * éste último maneja datos, con lo cual al presionarlo sincroniza y mantiene los cambios.
	 * Se implementa así de manera análoga a como funciona la operación de Grupos.
	 */
	
	function evt__cancelar_bloque()
	{
		$this->dep('bloques')->cancelar();
	}
	
	function evt__volver()
	{
		$this->dep('bloques')->cancelar();
	}
	
	function evt__eliminar()
	{
		if ($this->encuesta_seleccionada()) {
			try {
				
				/*
				 * Si el perfil del usuario es administrador, la encuesta no se va a usar más ni tiene
				 * respuestas, se eliminan también las habilitaciones y formularios que la contienen
				 */
				
				$encuesta = $this->get_encuesta();
				$this->modelo_act['habilitaciones'] = kolla::abm('act_habilitaciones');
				
				if (toba::consulta_php('consultas_encuestas')->validar_eliminacion_encuesta($encuesta)) {
					$this->modelo_act['habilitaciones']->eliminar_habilitaciones_por_encuesta($encuesta);
				} else {
					$this->modelo_act['habilitaciones']->eliminar_encuesta($encuesta);
				}
				
				$this->controlador()->resetear();
				toba::notificacion()->agregar($this->get_mensaje('eliminar_ok'), 'info');
			
			} catch(toba_error_db $e) {
				throw new toba_error($e->getMessage());
				toba::logger()->error($e->getMessage());
			} catch(toba_error $e) {
				toba::notificacion()->agregar('Error al eliminar. No es posible eliminar encuestas que están incluídas en algún formulario.');
				toba::logger()->error($e->getMessage());
			}
		}
	}
    
    function evt__guardar()
	{
        if ($this->encuesta_seleccionada()) { // Modificación
			$this->get_tabla()->set($this->s__datos_temp);
            
            //Se debe volver a verificar si la encuesta no se implementó
            if (toba::consulta_php('consultas_encuestas')->es_encuesta_no_editable($this->s__datos_temp['encuesta'])) {
                //Seteo de la encuesta como implementada
                $this->modelo_act['encuestas'] = kolla::abm('act_encuestas');
                $this->modelo_act['encuestas']->actualizar_implementada_encuesta($this->s__datos_temp['encuesta'], 'S');
                
                //Emito mensaje de error
                toba::notificacion()->agregar('Atención: No se pueden guardar los cambios realizados. Verifique las habilitaciones vigentes que incluyen esta encuesta.');
                return;
            }
            
			try {
				$this->get_tabla()->sincronizar();
                toba::notificacion()->agregar($this->get_mensaje('guardar_ok'), 'info');
			} catch (toba_error_db $e) {
				$this->s__formulario = $this->s__datos_temp;
			}
		} else { // Alta
			$this->s__datos_temp['estado'] = 'A'; // Siempre es activa (o se borra el campo en la base o se mantiene compatibilidad de ésta manera)
			$this->s__datos_temp['implementada'] = 'N';
			$this->get_tabla()->get_persistidor()->desactivar_transaccion();
			
            try {
                toba::db()->abrir_transaccion();
			
                // Crea la encuesta
                $this->get_tabla()->nueva_fila($this->s__datos_temp);
                $this->get_tabla()->sincronizar();
                toba::notificacion()->agregar($this->get_mensaje('guardar_ok'), 'info');
                
                // Crea siempre un formulario activo conteniendo a dicha encuesta
                $destino = toba::db()->recuperar_secuencia('sge_encuesta_atributo_seq');
                $this->crear_formulario($this->s__datos_temp['nombre'], $destino);
                $this->get_relacion_formulario()->sincronizar();
				
				// Determinar si es una clonación
				if (isset($this->s__datos_temp['encuesta_modelo'])) {
					
					// Tomar todas las definiciones de la encuesta origen y replicarlas en la encuesta destino
                    $origen  = $this->s__datos_temp['encuesta_modelo'];
					$this->clonar_encuesta($origen, $destino);
				}
                
				toba::db()->cerrar_transaccion();
                $this->get_relacion_formulario()->resetear();
				$this->controlador()->set_encuesta($destino);
			} catch(toba_error $e) {
				toba::db()->abortar_transaccion();
				toba::notificacion()->agregar('Ocurrió un error al intentar dar de alta la encuesta.');
				toba::logger()->error($e->getMessage());
				$this->get_tabla()->resetear();
                $this->s__formulario = $this->s__datos_temp;
			}
		}
	}
	
	//-------------------------------------------------------------------
	//-- FORMULARIO
	//-------------------------------------------------------------------
	
	function conf__form_atributos(toba_ei_formulario $componente)
	{
		if ($this->encuesta_seleccionada()) {
			$t = $this->get_tabla();
			$encuesta = array('encuesta' => $this->get_encuesta());
			$t->cargar($encuesta);
			$datos = $t->get();
			$id_encuesta = $datos['encuesta'];
			//ocultar combo de clonacion de encuesta
			$componente->desactivar_efs(array('encuesta_modelo'));
            $componente->set_datos($datos);
            
            if (toba::consulta_php('consultas_encuestas')->es_encuesta_no_editable($id_encuesta)) {
                $componente->set_solo_lectura();
            }
		} else {
            $datos = array('unidad_gestion' => $this->controlador()->get_ug());
			if (isset($this->s__formulario)) {
				$componente->set_datos($this->s__formulario);
				unset($this->s__formulario);
			} else {
                $componente->set_datos($datos);
            }
		}
	}
	
	function evt__form_atributos__modificacion($datos)
	{
        $this->s__datos_temp = $datos;
	}

    function crear_formulario($nombre_encuesta, $encuesta)
    {
        $this->crear_formulario_atributo($nombre_encuesta);
        $this->crear_formulario_definicion($encuesta);
    }
    
    function crear_formulario_atributo($nombre_encuesta)
    {
        $datos = array();
        $last_value = kolla_db::consultar_fila('SELECT last_value FROM sge_formulario_atributo_seq');
        $datos['nombre'] = $last_value['last_value'] + 1 . ' - ' . $nombre_encuesta;
        $datos['estado'] = 'A';
        
        $this->get_tabla_formulario_atributo()->set($datos);
    }
    
    function crear_formulario_definicion($encuesta)
    {
        $datos = array();
        $datos['encuesta'] = $encuesta;
        $datos['orden']    = '1';
        
        $this->get_tabla_formulario_definicion()->nueva_fila($datos);
    }
    
    //--------------------------------------
	//-- OPERACION PARA CLONAR UNA ENCUESTA
	//--------------------------------------
	
	function clonar_encuesta($origen, $destino)
	{
		$destino = kolla_db::quote($destino);
		$resultado = toba::consulta_php('consultas_encuestas')->get_encuesta_definicion($origen);

		try {
            $mapeo_de_bloques = array();
			foreach ($resultado as $registro) {
				$registro = kolla_db::quote($registro);
                
                //para cada bloque en la encuesta origen se debe crear una copia
                if (!isset($mapeo_de_bloques[$registro['bloque']]) ) {
                    $id_nuevo_bloque = toba::consulta_php('consultas_encuestas')->clonar_bloque($registro['bloque']);
                    $mapeo_de_bloques[$registro['bloque']] = $id_nuevo_bloque;
                }
                
                $this->modelo_act['encuestas'] = kolla::abm('act_encuestas');
                $this->modelo_act['encuestas']->insertar_encuesta_definicion($destino, $mapeo_de_bloques[$registro['bloque']], $registro['pregunta'], $registro['orden'], $registro['obligatoria']);
			}
		} catch(toba_error $e) {
			toba::notificacion()->agregar('Ocurrió un error al intentar clonar la encuesta.');
			toba::logger()->error($e->getMessage());
			throw $e;
		}
	}
    
	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__pant_bloques__entrada()
	{
        $this->dep('bloques')->resetear();
	}

	function conf__pant_bloques(toba_ei_pantalla $pantalla)
	{
		$this->conf_pantalla($pantalla);
	}

	function conf__pant_atributos(toba_ei_pantalla $pantalla)
	{
		$this->conf_pantalla($pantalla);
	}
	
	function conf_pantalla(toba_ei_pantalla $pantalla)
	{
		if (!$this->encuesta_seleccionada()) {
			return;
		}
		
		if (toba::consulta_php('consultas_encuestas')->es_encuesta_no_editable($this->get_encuesta())) {
        	$pantalla->evento('guardar')->anular();
        }
        
        if (toba::consulta_php('consultas_encuestas')->validar_eliminacion_encuesta($this->get_encuesta())) {
                
            if (toba::consulta_php('consultas_encuestas')->encuesta_incluida_en_formulario_habilitado($this->get_encuesta())) {
                
                //Seteo de la encuesta como implementada
                $this->modelo_act['encuestas'] = kolla::abm('act_encuestas');
                $this->modelo_act['encuestas']->actualizar_implementada_encuesta($this->get_encuesta(), 'S');
                
                //Se anula el evento eliminar
                $pantalla->evento('eliminar')->anular();
            } else {
                
                //Mensaje de confirmación
                $pantalla->evento('eliminar')->set_msg_confirmacion('Esta a punto de eliminar la encuesta junto con los formularios y habilitaciones que la contienen de forma permanente. ¿Desea continuar?');
            }
        } else {
            $pantalla->evento('eliminar')->anular();
        }
        
		if ($pantalla->existe_evento('eliminar')) {
			if (!$pantalla->evento('eliminar')->posee_confirmacion()) {
				$pantalla->evento('eliminar')->set_msg_confirmacion('Esta a punto de eliminar la encuesta de forma permanente. ¿Desea continuar?');
			}
		}
	}

}
?>