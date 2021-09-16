<?php
require_once 'reportes_base.php';

class reportes_forms extends reportes_base {
	
	//protected $reporte;
	protected $resultados;
	protected $datos_preguntas;
	protected $mostrar_codigo = false;
	protected $mostrar_simples = false;
	protected $mostrar_multiples = false;
	
	protected $matriz = array();

    //------------------------------------------------------------------------------
	//-- Generación de reportes.
	//------------------------------------------------------------------------------
    //------------------------------------------------------------------------------
	//-- POR ENCUESTADO - SOLO PREGUNTAS DE RESPUESTA MULTIPLE
	//------------------------------------------------------------------------------	
	function reporte_por_encuestado_solo_multiples($filtro, toba_ei_cuadro $cuadro){
		return $this->reporte_por_encuestado_cuadro($filtro, $cuadro, false, true);
	}		
	//------------------------------------------------------------------------------
	//-- (TXT) POR ENCUESTADO - SOLO PREGUNTAS DE RESPUESTA MULTIPLE
	//------------------------------------------------------------------------------
	function reporte_por_encuestado_solo_multiples_txt($filtro, $log_file_path){
		return $this->reporte_por_encuestado_txt($filtro, false, true);
	}			
	//------------------------------------------------------------------------------
	//-- POR ENCUESTADO - CON PREGUNTAS DE RESPUESTA MULTIPLE
	//------------------------------------------------------------------------------
	function reporte_por_encuestado_con_multiples($filtro, toba_ei_cuadro $cuadro) {
		return $this->reporte_por_encuestado_cuadro($filtro, $cuadro, true, true);
	}	
	//------------------------------------------------------------------------------
	//-- (TXT) POR ENCUESTADO - CON PREGUNTAS DE RESPUESTA MULTIPLE
	//------------------------------------------------------------------------------	
	function reporte_por_encuestado_con_multiples_txt($filtro, $log_file_path){
		return $this->reporte_por_encuestado_txt($filtro, true, true);
	}	
	//------------------------------------------------------------------------------
	//-- POR ENCUESTADO - SIN PREGUNTAS DE RESPUESTA MULTIPLE
	//------------------------------------------------------------------------------
	function reporte_por_encuestado_sin_multiples($filtro, toba_ei_cuadro $cuadro) {
		return $this->reporte_por_encuestado_cuadro($filtro, $cuadro, true, false);
	}
	//------------------------------------------------------------------------------
	//-- (TXT) POR ENCUESTADO - SIN PREGUNTAS DE RESPUESTA MULTIPLE
	//------------------------------------------------------------------------------	
	function reporte_por_encuestado_sin_multiples_txt($filtro, $log_file_path) {
		return $this->reporte_por_encuestado_txt($filtro, true, false);
	}	
	
	
		
	function reporte_por_encuestado_cuadro($filtro, toba_ei_cuadro $cuadro, $con_simples, $con_multiples){
		//toba::logger()->debug('inicio reporte');
		//$this->reporte = new reportes_base();
		$this->reporte_por_encuestado_($filtro, $con_simples, $con_multiples);

		$matriz = $this->get_data();
		if(isset($matriz)){
			$this->agregar_columnas_cuadro($cuadro);
			//unset($matriz[0]); //saco el encabezado que ya esta en el cuadro
		//	toba::logger()->debug('fin reporte');
			return $matriz;
		}
		
	}
	
	function reporte_por_encuestado_txt($filtro, $con_simples, $con_multiples){
		//$this->reporte = new reportes_base();
		$this->reporte_por_encuestado_($filtro, $con_simples, $con_multiples);
		$matriz = $this->get_data();
		if(isset($matriz)){
			return $this->obtener_reporte_texto();
		}
	}
	
	
	/**
	 *Genera una matriz que contiene el reporte.
	 * @param string $filtro inconclusas, encuesta, codigos,
	 * @param type $con_simples mostrar respuestas simples
	 * @param type $con_multiples mostrar respuestas multiples
	 * @return array 
	 */
	protected function reporte_por_encuestado_($filtro, $con_simples, $con_multiples){
		if (isset($filtro)) {
			$mostrar_codigos= isset($filtro['codigos']) && ($filtro['codigos']==1)?true:false;	
			$this->mostrar_codigo = $mostrar_codigos;
			$this->mostrar_simples = $con_simples;
			$this->mostrar_multiples = $con_multiples;
			//$this->mostrar_elementos = false; //$this->es_habilitacion_multiple($resultados);

			$this->planilla = toba::consulta_php('consultas_reportes')->get_planilla_reporte($filtro['habilitacion'], $filtro['concepto']);
			$filtro['inconclusas'] = 1; //no se guardan en enc-terminada. Pongo siempre 1 para que se pueda reutilizar la consulta
			//habria que refactorizar esto para evitar un copy-paste al separarlo
			
			$this->agregar_columna ('usuario', 'Usuario');//$fila['usuario']= 'Usuario';
			foreach ($this->planilla as $fila){
				//var_dump($fila);
				$id_encuesta = $fila['encuesta'];
				$filtro['encuesta'] = $id_encuesta;
				$mostrar_codigos= isset($filtro['codigos']) && ($filtro['codigos']==1)?true:false;
				$resultados = toba::consulta_php('consultas_reportes')->resultados_por_encuestado($filtro);
				
				$filtro['etiquetas'] = 'false';
				$datos_preguntas = toba::consulta_php('consultas_encuestas')->get_preguntas_encuesta_reporte($id_encuesta, $filtro);
				//esta consulta se puede cachear. No es tan grave igual
			
				$this->generar_encabezado($fila, $datos_preguntas);
				
				$this->cargar_valores_reporte($fila, $resultados);
			}
			$this->agregar_columna ('-1', 'Fecha Inicio Encuesta');
			$this->agregar_columna ('-2', 'Fecha Fin Encuesta');
			$this->set_data($this->matriz);
		}
	}

	/**
	 * Carga los datos_preguntas de este objeto en una fila. Tipicamente matriz[0]
	 * @param array $fila la fila donde se cargan los encabezados
	 * @param type $id_encuesta para obtener datos de las preguntas multiples
	 */
	private function generar_encabezado($fila, $datos_preguntas){
		$id_encuesta = $fila['encuesta'];
		$prefijo = $this->get_prefijo_fila($fila);
		$this->agregar_columna ($prefijo.'encuesta', 'Cod Encuesta');
		$this->agregar_columna ($prefijo.'elemento', 'Cod Elemento');
		$this->agregar_columna ($prefijo.'elemento_desc', 'Elemento');
				
		//agregar alguna columna referida a la encuesta/elemento
		$cant_preguntas = count($datos_preguntas);
		$nro_preg = 0;
		$ultima_preg = -1;
		for ( $i = 0 ; $i < $cant_preguntas ; $i++ ){
			$esta_preg = $datos_preguntas[$i]['pregunta'];
			if($esta_preg == $ultima_preg)	{
					$nro_preg++;
			}else{ 
				$nro_preg = 0;
			}
			$ultima_preg = $esta_preg;
			if($this->is_multiple($datos_preguntas[$i]['componente'])){
				if($this->mostrar_multiples)
					$this->agregar_columna_multiple($id_encuesta, $datos_preguntas[$i], $nro_preg+1, $prefijo);
			}
			else if($this->mostrar_simples){
					$this->agregar_columna_pregunta($datos_preguntas[$i], $prefijo);
			}
		}

		//$fila['-1'] = 'Fecha Inicio Encuesta';
		//$fila['-2'] = 'Fecha Fin Encuesta';
	}


	/**
	 * Carga los valores de $resultados en una matriz, a partir de la fila 0
	 * @param type $matriz matriz a llenar (va por referencia)
	 * @param type $id_encuesta
	 * @return type la matriz, es igual al primer parámetro.
	 */
	private function cargar_valores_reporte($fila, $resultados){
		//$id_encuesta = $fila['encuesta'];
		$prefijo = $this->get_prefijo_fila($fila);
		$matriz = &$this->matriz;
		//cargo los valores segun su clave
		$cant_resultados = count($resultados);
		//$j=0;//fila
		$encabezado = -1;
		for ( $i = 0 ; $i < $cant_resultados ; $i++ ){
			//Si cambia el encabezado de respuesta entonces cambio de fila, cargo de nuevo el usuario y el encabezado
			if ($resultados[$i]['encuesta_encabezado'] != $encabezado) {
				$encabezado = $resultados[$i]['encuesta_encabezado'];
			//	Cambio de fila en la matriz y seteo valores "especiales"
				$linea_en_construccion = &$matriz[$resultados[$i]['formulario_encabezado']];
				$linea_en_construccion['encuesta_encabezado'] = $encabezado;
				$linea_en_construccion['usuario'] = $resultados[$i]['usuario'];
				
				$linea_en_construccion[$prefijo.'encuesta'] = $fila['encuesta'];
				$linea_en_construccion[$prefijo.'elemento'] = $fila['elemento_externo'];
				$linea_en_construccion[$prefijo.'elemento_desc'] = $fila['elemento_desc'];
				
				$linea_en_construccion[-1] = $resultados[$i]['fecha_inicio'];
				$linea_en_construccion[-2] = $resultados[$i]['fecha_fin'];
//				if ($this->mostrar_elementos){	$linea_en_construccion['elemento'] = $resultados[$i]['elemento'];}
			}
			//cargo la respuesta en la columna correspondiente y el codigo de la respuesta en otra columna
			if( $this->is_multiple($resultados[$i]['componente'])){
				if($this->mostrar_multiples){
					$this->cargar_respuesta_multiple($linea_en_construccion, $resultados[$i], $prefijo);
				}
			}else 
				if($this->mostrar_simples){
					$this->cargar_respuesta_simple($linea_en_construccion, $resultados[$i], $prefijo);
				}
		}

		return $matriz;
	}

	/**
	 *Escribe en la fila del parametro (no la copia!)
	 * @param array $fila
	 * @param type $pregunta 
	 */
	private function agregar_columna_multiple($id_encuesta, $pregunta, $index, $prefijo = ''){
		$pregunta_id = $pregunta['pregunta'];
		$numero_p = $pregunta['numero'];
		//para cada pregunta obtener las posibles respuestas y agregar las respectivas columnas
		if ($pregunta['tabla_asociada'] == '') {				
			$respuestas_posibles[0] = $pregunta ;
			$titulo = $pregunta['pregunta_nombre'].' - Opción '.($index);
			$numero_r = $pregunta['respuesta'];
			$clave = $prefijo.$numero_p.'-'.$numero_r;
			//$fila[$clave] = $titulo;
			$this->agregar_columna_simple($clave,  $titulo);
		} else {
			$respuestas_posibles = toba::consulta_php('consultas_encuestas')->
					get_opciones_respuesta_pregunta_multiple_tabla_asociada($id_encuesta, $pregunta_id, $pregunta['tabla_asociada']);
			$cant_respuestas = count($respuestas_posibles);
			for ($rp=0; $rp<$cant_respuestas; $rp++) {
				$titulo = $pregunta['pregunta_nombre'].' - Opción '.($rp+1);
				$numero_r = $respuestas_posibles[$rp]['respuesta'];
				$clave = $prefijo.$numero_p.'-'.$numero_r;
				$this->agregar_columna_simple($clave,  $titulo);
			}
		}
	}
	
	//encuestado
	private function cargar_respuesta_multiple(&$fila, $pregunta){
		$numero_r = $pregunta['respuesta'];
		$numero_p = $pregunta['numero'];
		$valor ='';
		if ($pregunta['tabla_asociada']!='') {
			$nombre = $this->get_valor_tabla_asociada($pregunta);
			if (!is_null($nombre)) {
				$valor = $nombre;
			} 
		} else {
			$valor = $pregunta['respuesta_valor'];
		}
		$fila[$numero_p.'-'.$numero_r] = $valor;
		if ($this->mostrar_codigo) {
			$fila[$numero_p.'-'.$numero_r.'-cod'] = $numero_r;
		}
	}


	private function cargar_respuesta_simple(&$fila, $pregunta, $prefijo = ''){
		$numero_p = $prefijo.$pregunta['numero'];	
		if ($pregunta['tabla_asociada']!='') {
			$nombre = $this->get_valor_tabla_asociada($pregunta);
			if (!is_null($nombre)) {
				$fila[$numero_p] = $nombre;
			} 
		} else {
			$fila[$numero_p] = $pregunta['respuesta_valor'];
		}
		if ($this->mostrar_codigo) {
			$fila[$numero_p.'-cod'] = $pregunta['respuesta'];
		}
	}

		//encuestado
	private function agregar_columna_pregunta($pregunta, $prefijo = ''){
		$this->agregar_columna_simple($prefijo.$pregunta['numero'], $pregunta['pregunta_nombre']);	
	}
	
	
			//encuestado
	protected function agregar_columna_simple($id, $titulo, $prefijo = ''){
		if ($this->mostrar_codigo) {
			$this->agregar_columna( $prefijo.$id."-cod", $titulo." - código");
		}
		$this->agregar_columna( $prefijo.$id, $titulo);
	}
	
	protected function get_prefijo_fila($fila){
		return $fila['orden']."_";
	}

}
?>
