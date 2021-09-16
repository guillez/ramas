<?php

use SIUToba\rest\rest;
use SIUToba\rest\lib\rest_error;
use SIUToba\rest\lib\rest_filtro_sql;
use SIUToba\rest\lib\rest_hidratador;
use SIUToba\rest\lib\rest_validador;

class rest_habilitaciones extends rest_base
{

    /**
	 * @var co_habilitaciones
	 */
	protected $modelo;
    
    /**
	 * @var act_habilitaciones
	 */
	protected $modelo_act;
	protected $cache_enc;
	protected $cache_elemento;
    protected $cache_tipo_elemento;
	protected $cache_concepto;
    protected $cache_habilitacion;

	function __construct()
	{
        $this->modelo     = kolla::co('co_habilitaciones');
        $this->modelo_act = kolla::abm('act_habilitaciones');
    }
      
    function get($id_habilitacion)
    {
	    $filtro = new rest_filtro_sql();

	     $this->filtrar_ug_sistema($filtro);

		$filtro->agregar_campo_simple_local('habilitacion', 'habilitacion = %s', $id_habilitacion);

		$rs = $this->modelo->get_listado_rest($filtro->get_sql_where());
        
        if ( !$rs ) {
            throw new rest_error(404, 'La habilitación no existe');
        }
        
        $campos = $this->get_modelo('Habilitacion');

		return rest_hidratador::hidratar_fila($campos, $rs[0]);
    }
    
    function get_list()
    {
        $filtro = new rest_filtro_sql();
	    $this->filtrar_ug_sistema($filtro);

        $order = $filtro->get_sql_order_by('+sge_habilitacion.habilitacion');
		$where = $filtro->get_sql_where();

		$rs = $this->modelo->get_listado_rest($where, $order);

		return rest_hidratador::hidratar($this->get_modelo('Habilitacion'), $rs);
    }
    
    function put($id_habilitacion, $data)
	{
		$unidad_gestion = $this->_get_ug();
		$sistema = $this->_get_sistema();
        
        $habilitacion = $this->get($id_habilitacion); // Valido que exista

		$spec = $this->get_modelo('Habilitacion');

		rest_validador::validar($data, $spec, true);
		$modelo = rest_hidratador::deshidratar_fila($data, $spec);

        $modelo['sistema'] = $sistema;
		$modelo['unidad_gestion'] = $unidad_gestion;

		return $this->modelo_act->actualizar_habilitacion($id_habilitacion, $modelo);
	}
    
    function post($data)
    {
	    $unidad_gestion = $this->_get_ug();
	    $sistema = $this->_get_sistema();
            
	    $spec = $this->get_modelo('Habilitacion');
	    rest_validador::validar($data, $spec, false);
	    $modelo = rest_hidratador::deshidratar_fila($data, $spec);

	    $modelo['sistema'] = $sistema;
	    $modelo['unidad_gestion'] = $unidad_gestion;

            return $this->modelo_act->crear_habilitacion($modelo);
    }
    
    function put_formulario($id_habilitacion, $formulario_externo, $data)
    {
	    $unidad_gestion = $this->_get_ug();
	    $sistema = $this->_get_sistema();
	    $habilitacion = $this->get($id_habilitacion); // Valido que exista
		$spec =  $this->get_modelo('Formulario');
	    $relajar_ocultos = true;

	    $modelo = $this->validar_formulario($data, $sistema, $unidad_gestion, $spec, $relajar_ocultos);

        return $this->modelo_act->upsert_formulario_habilitado($id_habilitacion, $formulario_externo, $modelo, $sistema);
    }

	public function put_formularios_masivo($id_habilitacion, $formularios)
	{
		$unidad_gestion = $this->_get_ug();
		$sistema = $this->_get_sistema();
		$habilitacion = $this->get($id_habilitacion); // Valido que exista
		$spec = $this->get_modelo('Formulario');
		$relajar_ocultos = false;

		$resultados = array();
		foreach ($formularios as $indice => $formulario) {
			try{
				$modelo = $this->validar_formulario($formulario, $sistema, $unidad_gestion, $spec, $relajar_ocultos);
				$formulario_externo = $modelo['formulario_habilitado_externo'];
				$this->modelo_act->upsert_formulario_habilitado($id_habilitacion, $formulario_externo, $modelo, $sistema);
			}catch(rest_error $e){
				$resultados[] = array('indice' => $indice, 'error' => $e->getMessage(), 'detalle' => $e->get_datalle());
			}
		}
		return $resultados;
	}

    function delete_formulario($id_habilitacion, $formulario_externo)
    {
        /**
         * @todo validamos que de la UG?
         */
        $unidad_gestion = $this->_get_ug();
		$sistema = $this->_get_sistema();
        $f = kolla::co('co_habilitaciones')->get_formulario_externo($id_habilitacion, $formulario_externo, $sistema);
		if ( !$f ) {
            throw new rest_error(400, "El formulario $formulario_externo no existe");
        }
		$this->modelo_act->eliminar_formulario_externo($id_habilitacion, $formulario_externo);
    }


	public function get_formularios_list($id_habilitacion, $id_form = null)
	{
		$filtro = new rest_filtro_sql();
        $this->filtrar_sistema($filtro, 'sistema', 'sge_habilitacion.sistema');
	    $this->filtrar_ug($filtro, 'unidad_gestion', 'sge_habilitacion.unidad_gestion');

		$filtro->agregar_campo_simple_local('habilitacion', 'sge_habilitacion.habilitacion = %s', $id_habilitacion);

		if(!is_null($id_form)){
			$filtro->agregar_campo_simple_local('formulario', 'sge_formulario_habilitado.formulario_habilitado_externo = %s', $id_form);
		}

		//$limit = $filtro->get_sql_limit(20);
        $order = $filtro->get_sql_order_by('
        +sge_formulario_habilitado.formulario_habilitado_externo,
        +sge_formulario_habilitado_detalle.orden');
		$where = $filtro->get_sql_where();
        
		$forms = $this->modelo->get_listado_formularios_rest($where, $order);
        
        $rs = array();
        foreach ($forms as $form) {
            if ( isset($rs[$form['formulario_habilitado']] ) ) {
                $rs[$form['formulario_habilitado']]['detalle'][] = array(
                    'encuesta' => $form['encuesta'],
                    'elemento' => $form['elemento_externo'],
                    'orden'    => $form['orden']
                );
            } else {
                $rs[$form['formulario_habilitado']] = $form;
                $rs[$form['formulario_habilitado']]['detalle'] = array(
                    array(
                        'encuesta' => $form['encuesta'],
                        'elemento' => $form['elemento_externo'],
                        'orden'    => $form['orden']
                    )
                );
            }
        }
		return rest_hidratador::hidratar($this->get_modelo('Formulario'), $rs);
	}

	public function get_formularios($id_habilitacion, $id_formulario)
	{
		return current($this->get_formularios_list($id_habilitacion, $id_formulario));
	}
    
    public function get_formulario_respuestas($id_habilitacion, $id_formulario)
    {
        $codigo_externo = rest::request()->get('codigo_externo', null);
        if ( $codigo_externo == null ) {
            throw new rest_error(400, 'El código externo es requerido.');
        }
        $habilitacion = $this->get($id_habilitacion); // Valido que exista
        
        $anonima = $habilitacion['anonima'] == 'S';
        $gen_cod = $habilitacion['generar_codigo_recuperacion'] == 'S';
        
        if ( $anonima && $gen_cod ) {
            $codigo_recuperacion = rest::request()->get('codigo_recuperacion', null);
            if ( $codigo_recuperacion == null ) {
                throw new rest_error(400, 'El código de recuperación es requerido.');
            }
        } elseif ( $anonima && !$gen_cod ) {
            throw new rest_error(400, 'No es posible recuperar respuestas de una habilitación anónima que no genera código de recuperación.');
        }
        
        $unidad_gestion = $this->_get_ug();
        $sistema        = $this->_get_sistema();
        
        return $this->modelo->get_formulario_respuestas($id_habilitacion, $id_formulario, $codigo_externo, $sistema);
        
        return array();
    }
    
    public function get_formulario_encuesta_elemento_respuestas_detalle($id_habilitacion, $id_formulario_habilitado_externo, $id_encuesta, $id_elemento_externo, $id_bloque="NULL",$id_pregunta="NULL") 
    {        
        $unidad_gestion = $this->_get_ug();        
        $sistema = $this->_get_sistema();
        
        $this->validar_encuesta($id_encuesta);
        $this->validar_elemento($sistema, $unidad_gestion, array('elemento'=>$id_elemento_externo));
        $this->validar_habilitacion($sistema, $unidad_gestion, $id_habilitacion);
        //restaría validar form
        
        $encuesta = $this->modelo->get_encuesta_formulario_habilitado($id_habilitacion, $id_formulario_habilitado_externo, $id_encuesta, $id_elemento_externo, $unidad_gestion);
        if ( empty($encuesta) ) {
			throw new rest_error(400, "No existen datos para la encuesta $id_encuesta con elemento "
                            . "$id_elemento_externo en la habilitación $id_habilitacion y formulario "
                            . "$id_formulario_habilitado_externo (Unidad de gestión $unidad_gestion).");
		}

        $datos = kolla::co('co_respuestas')->get_encuesta_elemento_respuestas_detalle($id_habilitacion, $id_formulario_habilitado_externo, $id_elemento_externo, $id_bloque, $id_pregunta);
        
        $detalle = array();
        foreach ($datos as $fila) {
            if (!isset($detalle[$fila['encuesta_definicion']])) {
                //pregunta no cargada
                $detalle[$fila['encuesta_definicion']] = array();
                $detalle[$fila['encuesta_definicion']]['id_pregunta_bloque_encuesta'] = $fila['encuesta_definicion'];
                $detalle[$fila['encuesta_definicion']]['bloque'] = $fila['bloque'];
                $detalle[$fila['encuesta_definicion']]['pregunta_id'] = $fila['pregunta_id'];
                $detalle[$fila['encuesta_definicion']]['pregunta_texto'] = $fila['pregunta_texto'];
                $detalle[$fila['encuesta_definicion']]['componente'] = $fila['componente'];
                $detalle[$fila['encuesta_definicion']]['es_libre'] = $fila['es_libre'];
                $detalle[$fila['encuesta_definicion']]['es_multiple'] = $fila['es_multiple'];
                $detalle[$fila['encuesta_definicion']]['obligatoria'] = $fila['obligatoria'];
                $detalle[$fila['encuesta_definicion']]['pregunta_orden_bloque'] = $fila['pregunta_orden_bloque'];
                $detalle[$fila['encuesta_definicion']]['respuestas'] = array();
                
            }
           /* $detalle[$fila['encuesta_definicion']]['respuestas'][] = array(
                'respuesta_id' => $fila['respuesta_id'],
                'respuesta_valor' => $fila['respuesta_valor'],
                'elegida_veces' => $fila['elegida_cantidad']
            );*/
        }
        $limite =  rest::request()->get('limite',0);
        foreach ($detalle as $definicion => $valores)
        {
        	if ( ($detalle[$definicion]['es_libre'] == 'S' || $detalle[$definicion]['componente'] == 'localidad' )) //Si es libre o es de tipo LOCALIDAD entrego solo las seleccionadas
        	{
        		$detalle[$definicion]['respuestas'] = $this->incorporar_respuestas( $datos, $definicion);
        	}
        	if ($detalle[$definicion]['es_libre'] == 'N' &&  $detalle[$definicion]['componente'] != 'localidad' ) // Si es cerrada
        	{
        		$cantidad_respuestas = $this->contabilizar_respuestas($datos,$definicion);
        		if ( $limite == 0 || $limite <= $cantidad_respuestas )
        		{
        			$detalle[$definicion]['respuestas'] = $this->incorporar_respuestas($datos, $definicion);
        		}
        		else {
        			$detalle[$definicion]['respuestas'] = $this->incorporar_respuestas($datos, $definicion, true);
        		}
        	}
        	//$detalle[$definicion]['respuestas'] = $this->incorporar_respuestas($definicion, $datos);
        }
        
        $res['encuesta'] = $encuesta['encuesta'];
        $res['nombre'] = $encuesta['nombre'];
        $res['descripcion'] = $encuesta['descripcion'];
        $res['elemento'] = $encuesta['elemento_externo'];
        $res['orden'] = $encuesta['orden'];
        $res['detalle'] = array_values($detalle);
               
        return $res;
    }
    
    
    public function get_formulario_encuesta_elemento_respuestas_resumen($id_habilitacion, $id_formulario_habilitado_externo, $id_encuesta, $id_elemento_externo) 
    {        
        $unidad_gestion = $this->_get_ug();        
        $sistema = $this->_get_sistema();
        
        $this->validar_encuesta($id_encuesta);
        $this->validar_elemento($sistema, $unidad_gestion, array('elemento'=>$id_elemento_externo));
        $this->validar_habilitacion($sistema, $unidad_gestion, $id_habilitacion);
        //restaría validar form
        
        $encuesta = $this->modelo->get_encuesta_formulario_habilitado($id_habilitacion, $id_formulario_habilitado_externo, $id_encuesta, $id_elemento_externo, $unidad_gestion);
        if ( empty($encuesta) ) {
			throw new rest_error(400, "No existen datos para la encuesta $id_encuesta con elemento "
                            . "$id_elemento_externo en la habilitación $id_habilitacion y formulario "
                            . "$id_formulario_habilitado_externo (Unidad de gestión $unidad_gestion).");
		}

        $datos = kolla::co('co_respuestas')->get_encuesta_elemento_respuestas_resumen($id_habilitacion, $id_formulario_habilitado_externo, $id_encuesta, $id_elemento_externo);

        $detalle = array();
        foreach ($datos as $fila) {
            if (!isset($detalle[$fila['encuesta_definicion']])) {
                //pregunta no cargada
                $detalle[$fila['encuesta_definicion']] = array();
                $detalle[$fila['encuesta_definicion']]['bloque'] = $fila['bloque'];
                $detalle[$fila['encuesta_definicion']]['pregunta_id'] = $fila['pregunta_id'];
                $detalle[$fila['encuesta_definicion']]['pregunta_texto'] = $fila['pregunta_texto'];
                $detalle[$fila['encuesta_definicion']]['componente'] = $fila['componente'];
                $detalle[$fila['encuesta_definicion']]['es_libre'] = $fila['es_libre'];
                $detalle[$fila['encuesta_definicion']]['es_multiple'] = $fila['es_multiple'];
                $detalle[$fila['encuesta_definicion']]['obligatoria'] = $fila['obligatoria'];
                $detalle[$fila['encuesta_definicion']]['bloque_orden'] = $fila['bloque_orden'];
                $detalle[$fila['encuesta_definicion']]['pregunta_orden_bloque'] = $fila['pregunta_orden'];
                $detalle[$fila['encuesta_definicion']]['orden_en_encuesta'] = $fila['bloque_orden']."_".$fila['pregunta_orden'];
                $detalle[$fila['encuesta_definicion']]['opciones_respuesta'] = $fila['opciones_respuesta_disponible'];
                $detalle[$fila['encuesta_definicion']]['elegidas_cantidad'] = $fila['opciones_respuesta_elegidas'];
            }
        }

        $res['encuesta'] = $encuesta['encuesta'];
        $res['nombre'] = $encuesta['nombre'];
        $res['descripcion'] = $encuesta['descripcion'];
        $res['elemento'] = $encuesta['elemento_externo'];
        $res['orden'] = $encuesta['orden'];
        $res['detalle'] = array_values($detalle);
               
        return $res;        
    }
        
    
    protected function get_modelo($nombre)
	{
		$modelos = recurso_habilitaciones::_get_modelos();
		return $modelos[$nombre];
	}

	protected function validar_formulario($data, $sistema, $unidad_gestion, $spec, $relajar_ocultos)
	{
		if(!isset($data['estado'])){
			$data['estado'] = 'A';
		}
		if ( !isset($data['nombre']) ){
			$data['nombre'] = '';
		}

		rest_validador::validar($data, $spec, $relajar_ocultos);
		$modelo = rest_hidratador::deshidratar_fila($data, $spec);

		$concepto = $this->validar_concepto($sistema, $unidad_gestion, $data);
		$modelo['concepto'] = $concepto['concepto'];

		foreach ($modelo['detalle'] as $id => $form) {
			// Transformo a la representación interna del elemento
			$elemento      = $this->validar_elemento($sistema, $unidad_gestion, $form);
            $tipo_elemento = $this->validar_tipo_elemento($sistema, $unidad_gestion, $form);
			$encuesta      = $this->validar_encuesta($form['encuesta']);

            $modelo['detalle'][$id]['encuesta']      = $encuesta;
			$modelo['detalle'][$id]['elemento']      = $elemento['elemento'];
            $modelo['detalle'][$id]['tipo_elemento'] = $tipo_elemento['tipo_elemento'];
		}
		return $modelo;
	}

	protected function validar_concepto($sistema, $unidad_gestion, $data)
    {
		if ( !isset($data['concepto']) || empty($data['concepto']) ) {
			return null;
		}
		$id_concepto = $data['concepto'];
		if ( !isset($this->cache_concepto[$id_concepto]) ) {
			$c = kolla::co('co_conceptos')->get_concepto_id_externo($sistema, $id_concepto, $unidad_gestion);
			if ( !$c ) {
				throw new rest_error(400, "El concepto $id_concepto no existe");
			}
			$this->cache_concepto[$id_concepto] = $c;
		}
		return $this->cache_concepto[$id_concepto];
	}

	protected function validar_elemento($sistema, $unidad_gestion, $data)
    {
        
		if ( !isset($data['elemento']) || empty($data['elemento']) || ($data['elemento']=='null') ) {
			return null;
		}
		$id_elemento = $data['elemento'];
		if ( !isset($this->cache_elemento[$id_elemento]) ) {
			$e = kolla::co('co_elementos')->get_elemento_id_externo($sistema, $id_elemento, $unidad_gestion);
			if ( !$e ) {
				throw new rest_error(400, "El elemento $id_elemento no existe");
			}
			$this->cache_elemento[$id_elemento] = $e;
		}
		return $this->cache_elemento[$id_elemento];
	}
    
    protected function validar_tipo_elemento($sistema, $unidad_gestion, $data)
    {
		if ( !isset($data['tipo_elemento']) || empty($data['tipo_elemento']) ) {
			return null;
		}
		$id_tipo_elemento = $data['tipo_elemento'];
		if ( !isset($this->cache_tipo_elemento[$id_tipo_elemento]) ) {
			$e = kolla::co('co_tipo_elementos')->get_tipo_elemento_id_externo($sistema, $id_tipo_elemento, $unidad_gestion);
			if ( !$e ) {
				throw new rest_error(400, "El tipo de elemento $id_tipo_elemento no existe");
			}
			$this->cache_tipo_elemento[$id_tipo_elemento] = $e;
		}
		return $this->cache_tipo_elemento[$id_tipo_elemento];
	}

	/**
	 * Obtiene la encuesta correspondiente al parametro si es valida. Sino lanza un error.
	 */
	protected function validar_encuesta($encuesta)
	{
		if ( isset($this->cache_enc[$encuesta]) ) {
            return $this->cache_enc[$encuesta];
        }

		if ( !is_numeric($encuesta) ) {
			throw new rest_error(400, "La encuesta $encuesta no es valida");
		}
        
        $ug = kolla_db::quote($this->_get_ug());
        
		$sql = "SELECT      estado,
                            implementada,
                            nombre,
                            unidad_gestion
				FROM        sge_encuesta_atributo
				WHERE       encuesta = $encuesta
                        AND unidad_gestion = $ug";

		$res = kolla_db::consultar_fila($sql);

		if ( empty($res) ) {
			throw new rest_error(400, "La encuesta $encuesta no existe");
		}
		if ( $res['estado'] != 'A' ) {
			throw new rest_error(400, "La encuesta $encuesta no está activa");
		}

		$this->cache_enc[$encuesta] = $encuesta;
		return $this->cache_enc[$encuesta];
	}

    protected function validar_habilitacion($sistema, $unidad_gestion, $habilitacion)
    {
        $unidad_gestion = kolla::db()->quote($unidad_gestion);
        
        if ( !is_numeric($habilitacion) ) {
			throw new rest_error(400, "La encuesta $habilitacion no es valida");
		}
               
		$sql = "SELECT      habilitacion,
                            externa,
                            sistema,
                            descripcion,
                            texto_preliminar,
                            unidad_gestion
				FROM        sge_habilitacion
				WHERE       habilitacion = $habilitacion
                        AND sistema = $sistema
                        AND unidad_gestion = $unidad_gestion
                        AND externa = 'S' ";

		$res = kolla_db::consultar_fila($sql);

		if ( empty($res) ) {
			throw new rest_error(400, "La encuesta $habilitacion no existe");
		}
        $this->cache_habilitacion[$habilitacion] = $habilitacion;
		return $this->cache_habilitacion[$habilitacion];
    }
    
    /**
     * A partir de una conjuntos de datos y un identificador de encuesta se obtiene todas las respuestas para 
     * dicha pregunta. En caso de estar condicionado por el limite SOLO se retornan aquellas que fuerons 
     * elegidas más de una vez.
     * 
     * @param array $datos Arreglo con los datos de la encuesta.
     * @param string $definicion Identificador de la definición de una encuesta.
     * @param boolean $condicionado determina si se retornan todas las respuestas o solo las que han sido elegidas al menos una vez.
     * @return array $respuestas conjunto de respuestas.
     */
    private function incorporar_respuestas($datos, $definicion, $condicionado = false)
    {
    	$respuestas = array();
    	foreach ($datos as $fila)
    	{
    		if ( $fila['encuesta_definicion'] == $definicion && $fila['respuesta_valor'] != "" && (!$condicionado || ($condicionado && $fila['elegida_cantidad'] > 0))) //Verifico que la respuestas que se obtengan correspondan a la pregunta en cuestion
    		{
    			$respuesta = array( 'respuesta_id' => $fila['respuesta_id'],
    					'respuesta_valor' => $fila['respuesta_valor'],
    					'elegida_veces' => $fila['elegida_cantidad']
    			);
    			array_push($respuestas, $respuesta);
    		}
    	}
    	return $respuestas;
    }
    
    /**
     * Función que realiza la sumatoria de todas las respuestas obtenidas para una dada pregunta
     * @param array $datos conjunto de datos de la encuestas por la cual se consulta.
     * @param string $definicion identificador de la definición de una encuesta.
     * @return number totalidad de respuestas obtenidas para una encuesta.
     */
    private function contabilizar_respuestas($datos, $definicion)
    {
    	$respuestas_por_preguntas= 0 ;
    	 
    	//Inicializo los contadores segun la encuesta de definición
    	foreach ($datos as $fila) {
    		if ($fila['encuesta_definicion'] == $definicion && $fila['elegida_cantidad']>0) { // Si es la pregunta y fue elegida al menos una vez
    			$respuestas_por_preguntas ++; //Aumento en uno la cantidad de respuestas distintas
    		}
    	}
    	return $respuestas_por_preguntas;
    	 
    }
    
    public function get_estilos()
    {
        $sql = "SELECT  sge_encuesta_estilo.estilo,
                        sge_encuesta_estilo.descripcion
				FROM    sge_encuesta_estilo
                ";
        
		$res = kolla_db::consultar($sql);
        
		if (empty($res)) {
			throw new rest_error(400, "No existen estilos disponibles");
		}
        
        return $res;
    }

    public function get_habilitaciones_completas($id_habilitacion)
    {
        $filtro = new rest_filtro_sql();
        $this->filtrar_ug_sistema($filtro);
        $filtro->agregar_campo_simple_local('habilitacion', 'habilitacion = %s', $id_habilitacion);

        $rs = $this->modelo->get_listado_rest($filtro->get_sql_where());

        if ( !$rs )
        {
            throw new rest_error(404, 'La Unidad de Gestión o Habilitación no existe');
        }

        // En caso positivo, obtengo el modelo e hidrato la respuesta
        $campos = $this->get_modelo('Habilitacion');
        $habilitacion_hidratada = rest_hidratador::hidratar_fila($campos, $rs[0]);

        // Obtengo los formularios
        $ug = $this->_get_ug();
        $datos_formularios_habilitados = $this->modelo->get_formularios_habilitados($id_habilitacion, $ug);

        // Agrego un campo para formularios y lo lleno con datos
        $habilitacion_hidratada['formularios'] = array();
        foreach ($datos_formularios_habilitados as $formulario)
        {
            $habilitacion_hidratada['formularios'][] = array (
                'formulario_habilitado' => $formulario['formulario_habilitado'],
                'formulario_habilitado_nombre' => $formulario['nombre'],
                'formulario_habilitado_estado' => $formulario['estado'],
                'formulario_habilitado_externo' => $formulario['formulario_habilitado_externo'],
                'formulario_habilitado_concepto' => $formulario['concepto_desc'],
                'formulario_habilitado_concepto_externo' => $formulario['concepto_externo'],
                'formulario_habilitado_detalle' => $this->obtener_arreglo_formulario_detalle($id_habilitacion)
            );
        }

        return $habilitacion_hidratada;
    }

    protected function obtener_arreglo_formulario_detalle($habilitacion)
    {
        $datos = $this->modelo->get_formularios_habilitados_detalle($habilitacion);
        $salida = array();

        foreach ($datos as $formulario_detalle)
        {
            $salida[] = array(
                'formulario_detalle' => $formulario_detalle['formulario_habilitado_detalle'],
                'formulario_detalle_orden' => $formulario_detalle['orden'],
                'formulario_detalle_grupo' => $formulario_detalle['grupo'],
                'formulario_detalle_tipo_elemento' => $formulario_detalle['tipo_elemento_desc'],
                'formulario_detalle_elemento' => $formulario_detalle['descripcion'],
                'formulario_detalle_elemento_externo' => $formulario_detalle['elemento_externo'],
                'formulario_detalle_encuesta' => $formulario_detalle['encuesta'],
                'encuesta' => $this->obtener_arreglo_encuesta($formulario_detalle['encuesta'])
            );
        }

        return $salida;
    }

    protected function obtener_arreglo_encuesta($encuesta) {
        $datos = $this->modelo->get_encuesta($encuesta);
        $datos['preguntas'] = $this->modelo->get_encuesta_definicion_con_preguntas($encuesta);

        return $datos;
    }
}