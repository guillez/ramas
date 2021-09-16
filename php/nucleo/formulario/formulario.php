<?php

/**
 * Maneja al modelo, proces.
 */
class formulario
{
	protected $array_encuestas;
	protected $formulario_habilitado;
	protected $mensajes;
	protected $cmps; //helper para procesar componentes kolla.

	public static function get_id_componente($item, $pregunta_arreglo)
	{
		return 'c'.$item.'_pk_'.$pregunta_arreglo['id_c'];
	}
	
	public static function get_id_label_componente($item, $pregunta_arreglo)
	{
		return 'd'.$item.'_lk_'.$pregunta_arreglo['id_c'];
	}
	
	function __construct($planilla, $id_form_enc = null) 
	{
		if ( empty($planilla) ) {
			throw new Exception('Formulario sin encuestas');
		}
		
		$this->cmps = new repositorio_componentes();
		$this->array_encuestas = array();
        $this->formulario_habilitado  = (isset($planilla[0]['formulario_habilitado'])) ? $planilla[0]['formulario_habilitado'] : null;
		
		foreach ($planilla as $enc) {
			$encuesta = catalogo::consultar(dao_encuestas::instancia(), 'get_modelo_encuesta', array($enc['encuesta']));
			$encuesta['formulario'] = $enc['nombre_f']; //lo pongo todas las filas
			//para no romper el esquema de 1 fila por encuesta solamente.
			$encuesta['form_hab_detalle'] = $enc['fhd'];
			$encuesta['elemento'] = $this->get_datos_elemento($enc['elemento'], $encuesta);
			$this->array_encuestas[] = $encuesta;
		}
		
		if ( $id_form_enc != null ) {
			kolla::logger()->debug('recuperando el formulario: '. $id_form_enc);
			$this->cargar_datos_usuario($id_form_enc);
		}
	}
	
    private function get_datos_elemento($id)
    {
        $array = array();
        if ($id == null) {
            $array['elemento'] = null;
            return $array;
        }
        if (!is_int($id)) { //muestro como texto
            $array['elemento'] = null;
            $array['elemento_descripcion'] = $id;
            return $array;
        }
        $elemento = catalogo::consultar(dao_encuestas::instancia(), 'get_elemento_con_id_interno', array($id));
        return $elemento;
    }

    function get_datos()
    {
        return $this->array_encuestas;
    }

    /**
     * Por ahora se usa para hacer el menu de bloques para el paginado, que
     * se complica ponerlo despues de la encuesta
     */
    public function helper_get_lista_bloques()
    {
        $n = array();
        foreach ($this->array_encuestas as $encuesta) {
            foreach ($encuesta['bloques'] as $bloque) {
                $n[] = $bloque['nombre'];
            }
        }
        return $n;
    }

    //------------------------------------------------------------------------------
    //-- Procesos de grabación y validación
    //------------------------------------------------------------------------------

    function procesar_post()
    {
        foreach ($this->array_encuestas as &$encuesta) {
            $id_enc = $encuesta['encuesta']['id'];
            $elemento = $encuesta['elemento']['elemento'];
            $bloques = &$encuesta['bloques'];
            $this->procesar_post_bloques($id_enc, $elemento, $bloques);
        }
    }

    protected function procesar_post_bloques($encuesta, $elemento, &$bloques)
    {
        foreach ($bloques as &$bloque) { // La encuesta no se usa porque esta en el id de la preg
            foreach ($bloque['preguntas'] as &$pregunta) {
                $this->procesar_pregunta($pregunta, $elemento);
            }
        }
    }

    protected function procesar_pregunta(&$pregunta, $elemento)
    {
        $componente = $pregunta['componente'];
        $id_post = self::get_id_componente($elemento,$pregunta);
        $respuestas = &$pregunta['respuestas'];
        $this->cmps->procesar_post($componente, $id_post, $respuestas);
    }

    function validar()
    {
        $valida = true;
        foreach ($this->array_encuestas as &$encuesta) {
            $bloques = &$encuesta['bloques'];
            foreach ($bloques as &$bloque) {
                foreach ($bloque['preguntas'] as &$pregunta) {
                    $componente = $pregunta['componente'];
                    $componente_html = repositorio_componentes::get_componente($componente);
                    //$oblig = isset($pregunta['obligatoria']);
	                $oblig = false; ///NO SE CONTROLA POR EL PROBLEMA DE PREGUNTAS DESHABILITADAS POR JS
                    $resultado = $componente_html->validar_respuesta($pregunta['respuestas'], $oblig);
                    if ($resultado !== true) {
                        kolla::logger()->debug('Un componente no valida - salteo js');
                        kolla::logger()->var_dump($pregunta);
                        if ($valida)
                            $this->mensajes = array();
                        $valida = false;
                        $this->mensajes[] = $resultado;
                        $pregunta['error'] = $resultado;
                    }
                }
            }
        }
        return $valida;
    }

    /**
     * Guarda las respuestas en un proxy --> No se mandan inmediatamente a la base,
     * pero a los efectos de este metodo, puede asumirse que si... es la idea del Proxy.
     * @param batcher_respuestas $db
     */
    function guardar_respuestas(batcher_respuestas $db)
    {
        foreach ($this->array_encuestas as $encuesta) {
            $fhd = $encuesta['form_hab_detalle'];
            foreach ($encuesta['bloques'] as $bloque) {
                foreach ($bloque['preguntas'] as $pregunta) {
                    $componente = $pregunta['componente'];
                    //if (($componente != "label") && ($componente != "etiqueta_titulo") && ($componente != "etiqueta_texto_enriquecido") ) {
                    if (!repositorio_etiquetas::instance()->es_etiqueta($componente)) {
                        $encuesta_def = $pregunta['encuesta_definicion'];
                        $valor = $this->cmps->get_valor_para_sql($componente, $pregunta['respuestas']);
                        $db->guardar_respuesta($this->cmps->get_tipo_sp($componente), $fhd, $encuesta_def, $valor);
                    }
                }
            }
        }
    }

    function cargar_datos_usuario($id_form_enc)
    {
        $datos = catalogo::consultar(dao_encuestas::instancia(), 'get_respuestas_respondido_formulario', array($id_form_enc, $this->formulario_habilitado));
        $ix_enc = -1;
        $orden_ant = -1;
        
        foreach ($datos as $d) {
            $orden_f = $d['orden']; //de la encuesta en el form
            $fhd = $d['formulario_habilitado_detalle'];
            $bloque = $d['bloque'];
            $pregunta = $d['encuesta_definicion'];
            
            $respuesta = $d['respuesta_codigo'];
            $respuesta_valor = $d['respuesta_valor'];

            if ($orden_ant != $orden_f) { //cambie de encuesta
                $orden_ant = $orden_f;  //estan ordenados implicitamente aca. No usan el nro de orden
                $array_encuesta = &$this->array_encuestas[++$ix_enc];
                
                if ($fhd != $array_encuesta['form_hab_detalle']) {
                    throw new ErrorException('Fallo en el ordenamiento de la encuesta');
                }
                //Si no hubo respuestas para una de las encuestas del formulario, el arreglo $datos
                //traerá un componente al que le falta información: una "pregunta" sin ids
                //en ese caso, no procesar y seguir con el siguiente
                if (is_null($d['respondido_formulario'])) {
                    continue;
                }
                $bloques = &$array_encuesta['bloques']; //los bloques a rellenar
            }
            $respuestas = &$bloques[$bloque]['preguntas'][$pregunta]['respuestas'];

            $la_encontre = false;

            if (is_numeric($respuesta)) { //es tabulada, sino es null
                foreach ($respuestas as &$res) { //marco 1 sola. Si es multiple se marca en la prox fila la otra/s
                    if ($res['respuesta'] == $respuesta) {
                        $res['sel'] = 'S';
                        $la_encontre = true;
                        break;
                    }
                }
            } else {
                $respuestas[0]['respuesta_valor'] = $respuesta_valor;
                $la_encontre = true;
            }
            
            if (!$la_encontre) {
                throw new ErrorException('No se encontro lugar para la respuesta');
            }
        }
    }

    /**
     * NO PUEDE CAMBIAR PORQUE SE USA PARA EL HASH
     */
    final function to_string()
    {
        $str = '';
        foreach ($this->array_encuestas as $encuesta) {
            $elemento = $encuesta['elemento']['elemento'];
            $id_enc = $encuesta['encuesta']['id'];
            $str .= $elemento.$id_enc;
            foreach ($encuesta['bloques'] as $bloque) {
                foreach ($bloque['preguntas'] as $pregunta) {
                    $id = $pregunta['id_c'];
                    $componente = $pregunta['componente'];
                    //if ($componente != 'label') {
                    if (!repositorio_etiquetas::instance()->es_etiqueta($componente)) {
                        $resultado = $this->cmps->to_string($componente, $pregunta['respuestas']);
                        $str .= $id.$resultado;
                    }
                }
            }
        }
        return $str;
    }

}

?>
