<?php

class dao_encuestas extends catalogable
{
	protected static $instancia;
	const default_time = 30;
	
	static function instancia()
	{
		if (!isset(self::$instancia)) {
			self::$instancia = new dao_encuestas();
		}
		
		return self::$instancia;
	}
	
	public function getCacheInfo($metodo)
	{
		return $this->catalogo_info[$metodo];
	}
	
	public function getIdClase()
	{
		return 'mEc';
	}
	
	private $catalogo_info = array(
		'get_respuestas_respondido_formulario'	=> array(50, false, 0),
		'get_respondido_formulario' 			=> array(51, false, 0),
		'get_ya_respondio_externo' 				=> array(52, false, 0),
		'get_modelo_encuesta' 					=> array(2, true, self::default_time),
		'get_elemento_con_id_interno' 			=> array(4, true, self::default_time),
		'get_datos_habilitacion' 				=> array(5, true, self::default_time),
		'get_formulario_habilitado' 			=> array(6, false, self::default_time), //ver si se puede cachear!!
		'get_elemento_concepto_hab' 			=> array(7, true, self::default_time),
		'get_datos_respuestas_asociadas' 		=> array(8, true, self::default_time),
		'get_paises' 							=> array(9, true, self::default_time),
		'get_provincias' 						=> array(10, true, self::default_time),
		'get_departamentos'						=> array(11, true, self::default_time),
		'get_localidades' 						=> array(12, true, self::default_time),
		'get_localidad' 						=> array(13, true, self::default_time),
		'get_planilla_id' 						=> array(15, true, self::default_time),
		'get_formulario_habilitado_legacy' 		=> array(16, false, self::default_time), 
		);
	
	/////////////////////////////////////////////////////////////
	///////CONSULTAS
	/////////////////////////////////////////////////////////////

	////////////////////////////////////////////
	///// no cacheables
	////////////////////////////////////////////

	function get_respuestas_respondido_formulario($respondido_formulario, $formulario_habilitado=2)
    {
        $respondido_formulario = kolla::db()->quote($respondido_formulario);
        $formulario_habilitado = kolla::db()->quote($formulario_habilitado);
        
        $sql = "SELECT      fhd.formulario_habilitado,
                            fhd.formulario_habilitado_detalle,
                            fhd.orden,
                            rtas.respondido_formulario,
                            rtas.encuesta_definicion,
                            rtas.bloque,
                            rtas.respuesta_codigo,
                            rtas.respuesta_valor
                FROM        sge_formulario_habilitado_detalle fhd 
                            LEFT JOIN ( SELECT  rf.respondido_formulario,
                                                rf.formulario_habilitado,
                                                re.respondido_encuesta,
                                                re.formulario_habilitado_detalle,
                                                rd.respondido_detalle,
                                                rd.encuesta_definicion,
                                                ed.bloque,
                                                rd.respuesta_codigo,
                                                rd.respuesta_valor
                                        FROM    sge_respondido_formulario rf
                                                INNER JOIN sge_respondido_encuesta re ON (re.respondido_formulario = rf.respondido_formulario)
                                                INNER JOIN sge_respondido_detalle rd ON (re.respondido_encuesta = rd.respondido_encuesta)
                                                INNER JOIN sge_encuesta_definicion ed ON (ed.encuesta_definicion = rd.encuesta_definicion)
                                        WHERE   rf.respondido_formulario = $respondido_formulario
                                    ) rtas  ON (    fhd.formulario_habilitado = rtas.formulario_habilitado
                                                AND fhd.formulario_habilitado_detalle = rtas.formulario_habilitado_detalle)
                WHERE       fhd.formulario_habilitado = $formulario_habilitado
                ORDER BY    fhd.orden ASC";
        
		return kolla::db()->consultar($sql);
	}
	
	function get_respondido_formulario($id)
    {
		$id = kolla::db()->quote($id);
        
		$sql = "
            SELECT
                respondido_formulario,
				formulario_habilitado,
				codigo_recuperacion, 
				version_digest,
                terminado,
                fecha_terminado
            FROM
                sge_respondido_formulario
            WHERE
                respondido_formulario = $id
        ";
        
		return kolla::db()->consultar($sql);
	}
	
	/**
	 * @param type $formulario_habilitado id tabla
	 * @param type $codigo_externo cui.
	 */
	function get_ya_respondio_externo($formulario_habilitado, $codigo_externo)
	{
		return abm::existen_registros('sge_respondido_encuestado', array('codigo_externo' => $codigo_externo, 'formulario_habilitado' => $formulario_habilitado));
	}
	
	////////////////////////////////////////////
	///// cacheables
	////////////////////////////////////////////	
	
	function get_modelo_encuesta($id_encuesta)
	{
     	$array_encuesta = array();
		$this->cargar_datos_encuesta($id_encuesta, $array_encuesta);
		$this->cargar_datos_preguntas($id_encuesta, $array_encuesta);
		$this->cargar_datos_respuestas($id_encuesta, $array_encuesta);
		return $array_encuesta;
	}
	
	function get_elemento_con_id_interno($id_elemento)
	{
		$sql = "SELECT
					el.elemento			AS elemento,
					el.descripcion 		AS elemento_descripcion,			
					el.elemento_externo AS elemento_externo,
					el.url_img			AS elemento_img
				FROM 
					sge_elemento el 
				WHERE el.elemento = ".kolla::db()->quote($id_elemento)
				;
		
		$res = kolla::db()->consultar($sql);
		return $res[0];
	}
	
	function get_datos_habilitacion($id_habilitacion)
    {
		$id_habilitacion = kolla::db()->quote($id_habilitacion);
		$sql = "SELECT  eh.habilitacion,
                        eh.fecha_desde,
                        eh.fecha_hasta,
                        eh.paginado,
                        eh.externa,
                        eh.anonima,
                        eh.estilo,
                        eh.mostrar_progreso,
                        eh.sistema,
                        eh.password_se,
                        eh.url_imagenes_base,
                        eh.generar_cod_recuperacion,
                        eh.texto_preliminar,
                        eh.imprimir_respuestas_completas,
                        eh.publica,
                        sis.estado as estado_sistema,
                        encu.encuestado as encuestado
				FROM    sge_habilitacion eh
                        LEFT JOIN sge_sistema_externo sis ON sis.sistema = eh.sistema
                        LEFT JOIN sge_encuestado encu ON encu.usuario = sis.usuario
				WHERE 	eh.habilitacion = $id_habilitacion";
        
		return kolla::db()->consultar($sql);
	}
	
	/**
	 * Se usa para detectar si el usuario ya respondio. Si no hay resultados no respondio.
	 * Sino se usa el concepto para mostrar en el ticket.
	 * @param int $hab
	 * @param string $formulario_externo
	 * @return array
	 */
	function get_formulario_habilitado($hab, $formulario_externo)
    {
        $hab        = kolla::db()->quote($hab);
		$formulario = kolla::db()->quote($formulario_externo);
        
		$sql = "SELECT  fh.formulario_habilitado, 
                        fh.nombre
				FROM    sge_formulario_habilitado fh 
				WHERE		fh.habilitacion = $hab
                        AND fh.formulario_habilitado_externo =  $formulario";
		
		return kolla::db()->consultar($sql);
	}

	/**
	 * Idem get_formulario_habilitado 
	 * pero para implementaciones en las que el formulario_habilitado_externo no existía
	 * el dato que llega es el id de concepto_externo
	 * @param int $hab
	 * @param string $concepto_externo
	 * @return array
	 */
	function get_formulario_habilitado_legacy($hab, $concepto_externo)
	{
	    $hab = kolla::db()->quote($hab);
		$concepto = kolla::db()->quote($concepto_externo);
		$sql = "SELECT 
					fh.formulario_habilitado, 
					fh.nombre
				FROM 
				    sge_formulario_habilitado fh 
			              INNER JOIN sge_concepto c ON (fh.concepto = c.concepto)
				WHERE
					fh.habilitacion = $hab AND 
					c.concepto_externo =  $concepto";
		
		$res = kolla::db()->consultar($sql);
		return $res;
	}

	/**
	 * Retorna los datos de todas las respuestas en una tabla asociada
	 */
	static function get_datos_respuestas_asociadas($tabla_asociada, $codigo, $descripcion, $orden_campo, $orden_tipo)
    {
		if ( $orden_campo == 'codigo' ) {
			$campo = "t.$codigo";
		} else {
			$campo = "t.$descripcion";
		}
		
		$sql = "SELECT      t.$codigo AS respuesta,
                            t.$descripcion AS respuesta_valor
                FROM        $tabla_asociada AS t
                ORDER BY    $campo $orden_tipo";
		
		return kolla::db()->consultar($sql);
	}
	
	/**
	 * Validacion externa ya que no controlo el where
	 * @param type $where
	 * @return type
	 */
	function get_paises($where=null) 
	{
		$where = isset($where) ? " WHERE ".$where : '';
		$sql = "SELECT 
					pais		as clave,
					nombre		as nombre
				FROM
					mug_paises
				$where
				ORDER BY nombre
				;";
		$res = kolla::db()->consultar($sql);
		return $res;
	}

	function get_provincias($pais=null) 
	{
		$where = isset($pais) ? " WHERE pais= ". (int)$pais : '';
		$sql = "SELECT 
					p.provincia		as clave,
					p.nombre		as nombre
				FROM
					mug_provincias	as p
				$where
				ORDER BY nombre
		";

		$res = kolla::db()->consultar($sql);
		return $res;	
	}

	function get_departamentos($provincia=null) 
	{
		$where = isset($provincia) ? " WHERE provincia= ".(int)$provincia : '';
		$sql = "SELECT 
					dp.dpto_partido		as clave,
					dp.nombre			as nombre
				FROM
					mug_dptos_partidos as dp
				$where
				ORDER BY nombre
		";
		return kolla::db()->consultar($sql);	
	}	

	function get_localidades($departamento=null) 
	{
		$where = isset($departamento) ? " WHERE dpto_partido= ".(int)$departamento : '';
		$sql = "SELECT 
					l.localidad			as clave,
					l.nombre			as nombre
				FROM
					mug_localidades		as l
				$where
				ORDER BY nombre
		";
		return kolla::db()->consultar($sql);	
	}
	
	function get_localidad($localidad=null) 
	{
		$where = isset($localidad) ? " WHERE localidad= ".(int)$localidad : '';
		$sql = "SELECT 
					l.localidad			as clave,
					l.nombre			as nombre
				FROM
					mug_localidades		as l
				$where
				ORDER BY nombre
		";
		return kolla::db()->consultar($sql);	
	}
	

	/**
	 * Obtiene una planilla en base al id del formulario_habilitado
	 * @param type $form_hab
	 * @return type
	 */
	function get_planilla_id($form_hab, $filtrar_activos = true)
	{
		$form_hab = kolla::db()->quote($form_hab);
		$where = '';
        
        if ($filtrar_activos) {
            $where = "AND fh.estado = 'A'";
        }
        
		$sql = "SELECT 	fh.formulario_habilitado,
						fh.nombre 						  AS nombre_f,
						el.descripcion 					  AS elemento_descr,
						fhd.formulario_habilitado_detalle AS fhd,
						fhd.encuesta,
						fhd.elemento,
						fhd.orden
				FROM	sge_formulario_habilitado_detalle AS fhd
							INNER JOIN sge_formulario_habilitado AS fh ON fhd.formulario_habilitado = fh.formulario_habilitado
							LEFT JOIN sge_elemento AS el ON fhd.elemento = el.elemento
				WHERE	fh.formulario_habilitado = $form_hab
                        $where
				ORDER BY orden ASC
				";
		
		return kolla::db()->consultar($sql);	
	}
		
	///////////////////////////////////////////////////////////////////////////
	///////PRIVADOS helpers internos, no tienen uso externo- Se cachean ///////
    ///////en la llamada a las funciones que los usan                   ///////
	///////////////////////////////////////////////////////////////////////////
	
	private function get_elemento_concepto($elemento_externo, $concepto)
	{
		$sql = 'SELECT 	sge_elemento_concepto.elemento_concepto AS id_elemento_alc
				FROM 	sge_concepto,
						sge_elemento,
						sge_elemento_concepto
				WHERE 	sge_elemento_concepto.concepto = sge_concepto.concepto
				AND		sge_elemento_concepto.elemento = sge_elemento.elemento
				AND		sge_elemento.elemento_externo = '.quote($elemento_externo).'
				AND		sge_elemento_concepto.concepto = '.(int)$concepto;
                
		$res = kolla::db()->consultar($sql);
		return empty($res) ? null : $res[0]['id_elemento_alc'];
	}
	
	private function cargar_datos_preguntas($id, &$array_encuesta)
	{
		$array_encuesta['bloques']= array();
		$bloques = &$array_encuesta['bloques'];
		
		$datos_preguntas = $this->get_datos_preguntas($id);
		$bloque = -1;
		
		foreach ($datos_preguntas as &$pregunta) {
			if ($bloque != $pregunta['bloque']) {
				$bloque = $pregunta['bloque'];
				$bloques[$bloque] = array('bloque'=>$bloque, 'nombre'=> $pregunta['bloque_nombre']);
				$bloques[$bloque]['preguntas'] = array();
			}
			unset($pregunta['encuesta']);
			unset($pregunta['bloque']);
			unset($pregunta['bloque_nombre']);
			if ($pregunta['obligatoria'] == 'N') {
				unset($pregunta['obligatoria']);
			}
			$componenteid = $pregunta['encuesta_definicion'];
			$pregunta['id_c'] = $componenteid;
			$bloques[$bloque]['preguntas'][$pregunta['encuesta_definicion']] = $pregunta;
		}
	}
	
	private function cargar_datos_respuestas($id, &$array_encuesta)
    {
		$bloques = &$array_encuesta['bloques']; //los bloques a rellenar
		$datos_preguntas_respuestas = $this->get_datos_preguntas_respuestas_kolla($id);
			
		foreach ($datos_preguntas_respuestas as &$respuesta) {
			$r_bloque = $respuesta['bloque'];
			$r_pregunta_nro = $respuesta['encuesta_definicion'];
			if ( !isset($bloques[$r_bloque]['preguntas'][$r_pregunta_nro]) ) {
				throw  new Exception("Que paso al armar la encuesta?");
			}
			
			$pregunta = &$bloques[$r_bloque]['preguntas'][$r_pregunta_nro];

			if ($respuesta['tabla_asociada'] != '') { //cargo de la tabla, no estan en el arreglo
				//-- Se cachea
				$respuestas = catalogo::consultar(self::instancia(),'get_datos_respuestas_asociadas', 
						array($respuesta['tabla_asociada'], 
							$respuesta['tabla_asociada_codigo'],
							$respuesta['tabla_asociada_descripcion'],
							$respuesta['tabla_asociada_orden_campo'], 
							$respuesta['tabla_asociada_orden_tipo']));
				
				$indice = 0;
				$pregunta['respuestas'] = array();
				foreach($respuestas as $rta_tabla) {
					$pregunta['respuestas'][$indice++] = $rta_tabla;
				}
			}else{
				$indice =  isset($respuesta['respuesta_orden'])? $respuesta['respuesta_orden']: 0;
				
				unset($respuesta['tabla_asociada']);
				unset($respuesta['tabla_asociada_codigo']);
				unset($respuesta['tabla_asociada_descripcion']);
				unset($respuesta['tabla_asociada_orden_campo']);
				unset($respuesta['tabla_asociada_orden_tipo']);

				unset($respuesta['encuesta_definicion']);
				unset($respuesta['bloque']);
				unset($respuesta['pregunta']); 
				unset($respuesta['respuesta_orden']);
			
				$pregunta['respuestas'][$indice]  = $respuesta;
			}
		}
	}
	
	private function cargar_datos_encuesta($id, &$array_encuesta)
	{
		$sql = "SELECT	encuesta,
						nombre,
						descripcion,
						estado,
						texto_preliminar
				FROM 	sge_encuesta_atributo
				WHERE 	encuesta = ".(int)$id;
		
		$res = kolla::db()->consultar($sql);
		$array_encuesta['encuesta']['id'] = (int)$id;
		$array_encuesta['encuesta']['nombre'] = $res[0]['nombre'];
		$array_encuesta['encuesta']['texto_preliminar'] = $res[0]['texto_preliminar']; 
	}
	
	/**
	 Retorna los datos de todas las preguntas de la encuesta dada
	 ordenados por bloque y numero de pregunta
	 */
	private function get_datos_preguntas($encuesta)
	{
		$sql = "SELECT
		            ed.encuesta_definicion,
		            ed.bloque,
					ed.pregunta,
					b.nombre AS bloque_nombre,
		            p.nombre AS pregunta_nombre,
		            cp.componente as componente,
		            ed.obligatoria as obligatoria,
					p.ayuda as ayuda
				FROM
					sge_encuesta_definicion ed 
						INNER JOIN sge_pregunta p ON (ed.pregunta = p.pregunta)
						INNER JOIN sge_bloque b ON (ed.bloque = b.bloque)
						INNER JOIN sge_componente_pregunta cp ON (cp.numero = p.componente_numero)
				WHERE 
					ed.encuesta = ". (int)($encuesta)." 
				ORDER BY b.orden, ed.orden;";
		return kolla::db()->consultar($sql);
	}
	

	
	/**
	 Retorna los datos de todas las respuestas posibles a preguntas de la encuesta dada
	 */
	private function get_datos_preguntas_respuestas_kolla($encuesta) {
		//Las respuestas tabuladas UNION las de valor.
		$sql = "
			SELECT 
				ed.encuesta_definicion,
				ed.bloque,
				ed.pregunta,

				r.respuesta,
				CASE WHEN r.valor_tabulado IS NULL THEN ''
						ELSE r.valor_tabulado
				   END,
				r.valor_tabulado AS respuesta_valor,	
				pr.orden AS respuesta_orden,
				-- Bloque de tabla asociada	
				p.tabla_asociada,
				p.tabla_asociada_codigo,
				p.tabla_asociada_descripcion,
				p.tabla_asociada_orden_campo,
				p.tabla_asociada_orden_tipo
			FROM 
				sge_encuesta_definicion ed 
				INNER JOIN sge_pregunta p ON (ed.pregunta = p.pregunta)
				INNER JOIN sge_componente_pregunta cp ON (p.componente_numero = cp.numero)
				LEFT JOIN sge_pregunta_respuesta pr ON (ed.pregunta = pr.pregunta)
				LEFT JOIN sge_respuesta r ON (r.respuesta = pr.respuesta)
			WHERE
				ed.encuesta = ".(int)($encuesta) ." 
                        ORDER BY ed.orden, pr.orden;";
		return kolla::db()->consultar($sql);
	}

}
?>
