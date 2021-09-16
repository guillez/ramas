<?php

class ci_abm_preguntas extends ci_navegacion_por_ug
{
	protected $datos_form_temp;
	protected $s__datos_form;
    
    const TEXTO_NUMERO_EDAD = 13;
    const TIPO_FECHA_CALCULO_ANIOS = 16;
    const LOCALIDAD_Y_CP = 18;
    const COMBO_DINAMICO = 19;
    const TEXTO_ENRIQUECIDO = 22;
	
    //------------------------------------------------------------------------------------
	//---- PANTALLA SELECCION ------------------------------------------------------------
	//------------------------------------------------------------------------------------
    
	function conf__seleccion($pantalla)
    {
        $this->dep('filtro')->columna('nombre')->set_condicion_fija('contiene', true);
    }
    
    //-- CUADRO --
	
	function get_listado()
	{
        $this->set_ug();
        return toba::consulta_php('consultas_encuestas')->get_preguntas($this->get_filtro('p'));
	}
	
	function get_etiquetas_cuadro()
	{
        return array('preguntas');
	}
    
    //------------------------------------------------------------------------------------
	//---- PANTALLA EDICION --------------------------------------------------------------
	//------------------------------------------------------------------------------------
    
    function conf__edicion($pantalla)
    {
        if (isset($this->s__seleccion)) {
            $datos = $this->get_pregunta();
            if (toba::consulta_php('co_preguntas')->es_pregunta_receptora_cascada($datos['pregunta'])) {
                $pantalla->eliminar_evento('eliminar');
            }
        } else {
            $pantalla->eliminar_evento('eliminar');
		}
    }
    
	//-- FORMULARIO

	function conf__formulario(toba_ei_formulario $form)
	{
        if (isset($this->datos_form_temp)) {
            $form->set_datos($this->datos_form_temp);
        } elseif (isset($this->s__seleccion)) {
            $datos = $this->get_pregunta();
            
            if ($datos['componente_numero'] == self::TIPO_FECHA_CALCULO_ANIOS) {
                $datos_pregunta_dep   = $this->get_pregunta_cascada();
                $pregunta_dependiente = toba::consulta_php('co_preguntas')->get_pregunta($datos_pregunta_dep['pregunta_receptora']);
                $datos['pregunta_calculo_anios'] = $pregunta_dependiente['nombre'];
            }

            if ($datos['componente_numero'] == self::TEXTO_ENRIQUECIDO) {
                $datos['texto_enriquecido'] = $datos['nombre'];
            }
            
            $form->set_datos($datos);

            //Determinar si es una pregunta que no se puede modificar
            $es_no_editable = toba::consulta_php('consultas_encuestas')->es_pregunta_no_editable($form->ef('pregunta')->get_estado());

            if ($es_no_editable) {
                $form->set_modo_descripcion(false);
                $form->set_descripcion('Esta pregunta no se puede editar. Solo se podrá cambiar el texto de la descripción resumida que es de uso interno');
                
                if ($this->existe_evento('eliminar')) {
                    $this->evento('eliminar')->anular();
                }
                    
                $form->set_solo_lectura();
                $form->ef('descripcion_resumida')->set_solo_lectura(false);
            }
        } else {
            $datos = array('unidad_gestion' => $this->s__ug);
            $form->set_datos($datos);
        }
	}

	function evt__formulario__modificacion($datos)
	{
        $this->validar_datos($datos);
        
        //Contemplo que en caso de que se este modificando un texto enriquecido tengo que pasarle los datos como si fuese el componente pregunta (nombre)
        if ($datos['componente_numero'] == self::TEXTO_ENRIQUECIDO) {
            $datos['nombre'] = $datos['texto_enriquecido'];
        }
        
        $this->s__datos_form = $datos;

	}
	
	function validar_datos($datos)
	{
        if (isset($datos['tabla_asociada'])) {
            if (!isset($datos['tabla_asociada_codigo'])) {
                throw new toba_error('El campo Código es obligatorio.');
            }

            if (!isset($datos['tabla_asociada_descripcion'])) {
                throw new toba_error('El campo Descripción es obligatorio.');
            }
        }
	}
    
	//-- Eventos
    
    function evt__guardar() 
	{
		$datos = $this->s__datos_form;
		
		if (isset($datos) && is_null($datos['tabla_asociada'])) {
			$datos['tabla_asociada'] = '';
        }
		
		if (isset($this->s__seleccion)) {
			//Chequear si el cambio deja respuestas desvinculadas para eliminar las asociaciones
			$this->garantizar_consistencia_asociaciones($datos);
		}
		
		try {
            toba::db()->abrir_transaccion();
                $this->set_pregunta($datos);
                $datos_pregunta_dep = $this->get_pregunta_cascada();
                
                if ($datos['componente_numero'] == self::TIPO_FECHA_CALCULO_ANIOS) {
                    
                    if (isset($datos_pregunta_dep)) {
                        //Actualiza nombre de la pregunta dependiente
                        $this->actualizar_pregunta_cascada($datos_pregunta_dep['pregunta_receptora'], $datos['pregunta_calculo_anios']);
                    } else {
                        //Se crea la pregunta dependiente y se las relaciona
                        $pregunta_dependiente = $this->insertar_pregunta_cascada($datos['pregunta_calculo_anios'], self::TEXTO_NUMERO_EDAD);
                        $pregunta_cascada = array('pregunta_receptora' => $pregunta_dependiente);
                        $this->set_pregunta_cascada($pregunta_cascada);
                    }
                    
                } elseif ($datos['componente_numero'] == self::LOCALIDAD_Y_CP) {
                    if (!isset($datos_pregunta_dep)) {
                        //Se crea la pregunta dependiente y se las relaciona
                        $tabla_asociada             = 'mug_cod_postales';
                        $tabla_asociada_codigo      = 'id';
                        $tabla_asociada_descripcion	= 'codigo_postal';
                        $tabla_asociada_orden_campo	= 'codigo';
                        $tabla_asociada_orden_tipo  = 'ASC';
                        $pregunta_dependiente = $this->insertar_pregunta_cascada('Código Postal', self::COMBO_DINAMICO, $tabla_asociada, $tabla_asociada_codigo, $tabla_asociada_descripcion, $tabla_asociada_orden_campo, $tabla_asociada_orden_tipo);
                        $pregunta_cascada = array('pregunta_receptora' => $pregunta_dependiente);
                        $this->set_pregunta_cascada($pregunta_cascada);
                    }
                } else {
                    //Se elimina la dependencia y la pregunta dependiente
                    if (isset($datos_pregunta_dep)) {
                        $this->eliminar_pregunta_cascada();
                        $this->eliminar_pregunta_dependiente($datos_pregunta_dep['pregunta_receptora']);
                    }
                }
        
                $this->dep('datos')->sincronizar();
                $this->cancelar();
                $this->dep('cuadro')->set_pagina_actual($this->dep('cuadro')->get_cantidad_paginas());
            toba::db()->cerrar_transaccion();
		} catch(toba_error $e) {
            toba::db()->abortar_transaccion();
			toba::notificacion()->agregar('Ocurrió un error al dar de alta la pregunta');
			toba::logger()->error($e->getMessage());
			$this->datos_form_temp = $datos;
		}
        
		$this->dep('cuadro')->set_pagina_actual($this->dep('cuadro')->get_cantidad_paginas());
	}
    
    function evt__eliminar()
	{
		if (isset($this->s__seleccion)) {
			try {
                toba::db()->abrir_transaccion();
                $datos = $this->get_pregunta();
                
                if ($datos['componente_numero'] == self::TIPO_FECHA_CALCULO_ANIOS || $datos['componente_numero'] == self::LOCALIDAD_Y_CP) {
                    $datos_pregunta_dep = $this->get_pregunta_cascada();
                    $this->dep('datos')->eliminar_todo();
                    
                    //Se elimina la pregunta dependiente para que no quede huerfana
                    $this->eliminar_pregunta_dependiente($datos_pregunta_dep['pregunta_receptora']);
                } else {
                    $this->dep('datos')->eliminar_todo();
                }
				
                $this->cancelar();
                toba::notificacion()->agregar($this->get_mensaje('eliminar_ok'), 'info');
                toba::db()->cerrar_transaccion();
			} catch(toba_error $e) {
                toba::db()->abortar_transaccion();
                $this->dep('datos')->cargar($this->s__seleccion);
				throw new toba_error($e->getMessage());
			}
		}
	}

	//-- Auxiliares
	
	function garantizar_consistencia_asociaciones($datos) 
	{
		$tabla = $this->get_pregunta();
        if (isset($tabla) && !empty($tabla)) {
			$id_pregunta = $tabla['pregunta'];
			if ($this->es_de_respuestas_asociadas($tabla) && !$this->es_de_respuestas_asociadas($datos)) {
				$sql = "DELETE FROM sge_pregunta_respuesta WHERE pregunta = $id_pregunta";
				kolla_db::ejecutar($sql);
			}
		}
	}
	
	function es_de_respuestas_asociadas($preg)
	{
		return ((($preg['componente_numero'] == 2) ||
				 ($preg['componente_numero'] == 3) ||
				 ($preg['componente_numero'] == 4) ||
				 ($preg['componente_numero'] == 5) ||
                 ($preg['componente_numero'] == 17)) &&
				 ($preg['tabla_asociada'] == ''));
	}
	
	function get_campos_tabla_codigo($tabla) 
	{
		$columnas = toba::db()->get_definicion_columnas($tabla);
		
        //Se filtran columnas con tipos distintos de integer ya que no se permiten códigos distintos de este tipo
		foreach ($columnas as $key => $columna) {
			if ($columna['tipo_sql'] != 'integer') {
				unset($columnas[$key]);
			}
		}
        
		return $columnas;
	}
	
	function get_campos_tabla_descripcion($tabla)
	{
		return toba::db()->get_definicion_columnas($tabla);
	}
	
	function get_orden_tipo()
	{
		return array(array('orden' => 'ASC ', 'descripcion' => 'Ascendente'),
					 array('orden' => 'DESC', 'descripcion' => 'Descendente'));
	}
    
    function insertar_pregunta_cascada($nombre, $componente_numero, $tabla_asociada = null, $tabla_asociada_codigo = null, $tabla_asociada_descripcion = null, $tabla_asociada_orden_campo = null, $tabla_asociada_orden_tipo = null)
    {
        $unidad_gestion       = $this->s__datos_form['unidad_gestion'];
        $descripcion_resumida = substr(trim($nombre), 0, 30);
        $modelo_act_encuestas = kolla::abm('act_encuestas');
        $pregunta = $modelo_act_encuestas->insertar_pregunta($nombre, $componente_numero, $unidad_gestion, $descripcion_resumida, 'S', $tabla_asociada, $tabla_asociada_codigo, $tabla_asociada_descripcion, $tabla_asociada_orden_campo, $tabla_asociada_orden_tipo);
        
        //Retorna el identificador de la pregunta creada
        return $pregunta;
    }
    
    function actualizar_pregunta_cascada($pregunta, $nombre)
    {
        $modelo_act_encuestas = kolla::abm('act_encuestas');
        $modelo_act_encuestas->actualizar_pregunta($pregunta, $nombre);
    }
    
    function eliminar_pregunta_dependiente($pregunta)
    {
        $modelo_act_encuestas = kolla::abm('act_encuestas');
        $modelo_act_encuestas->eliminar_pregunta($pregunta);
    }
    
    function get_lista_tablas()
    {
        return toba::consulta_php('consultas_encuestas')->get_lista_tablas($this->s__ug);
    }
    
    //-- Auxiliares - Datos Tabla
    
    function get_pregunta()
    {
        return $this->dep('datos')->tabla('pregunta')->get();
    }
    
    function get_pregunta_cascada()
    {
        return $this->dep('datos')->tabla('pregunta_cascada')->get();
    }
    
    function set_pregunta($datos)
    {
        $this->dep('datos')->tabla('pregunta')->set($datos);
    }
    
    function set_pregunta_cascada($datos)
    {
        $this->dep('datos')->tabla('pregunta_cascada')->set($datos);
    }
    
    function eliminar_pregunta_cascada()
    {
        $this->dep('datos')->tabla('pregunta_cascada')->eliminar();
    }

}
?>