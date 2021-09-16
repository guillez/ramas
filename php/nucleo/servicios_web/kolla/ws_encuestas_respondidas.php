<?php

class ws_encuestas_respondidas
{
	private $sistema;
	
	private $respuestas_a_marcar;
	private $respuestas_inexistentes;

	//CONSTANTES DE ESTADO DE MI RESPUESTA
	const STATUS_PENDIENTE = 'PEND'; //DEFAULT EN BD
	const STATUS_ENVIADO = 'OK'; //
	const STATUS_ERROR = 'ERR'; //Guaraní no me la pidio. Es un error.
	

	//CONSTANTES PARA NOTIFICAR A GUARANI EN LA SINCRONIZACION
	const SYNC_OK = 1;
	const SYNC_ERROR = -1;//El resultado esta en guarani pero no en kolla.


	
	function __construct($params, $sistema)
	{
		$this->sistema = $sistema;
		$this->respuestas_a_marcar = $params['respuestas_ya_marcadas'];
		$this->respuestas_inexistentes = $params['respuestas_inexistentes'];
		toba::logger()->debug('Procesando sincronizacion del sistema '. $sistema);

	}

	function ejecutar()
	{
		$procesadas = $this->sincronizar_respuestas($this->respuestas_a_marcar);
		toba::logger()->debug(var_export($this->respuestas_a_marcar, true));
		$errores = $this->registrar_respuestas_inexistentes($this->respuestas_inexistentes);
		$no_enviadas = $this->obtener_respuestas_no_enviadas();
		$resultado = array(
			'respuestas_sincronizadas' => $procesadas,
			'errores_registrados' => $errores,	
			'respuestas_nuevas' => $no_enviadas
		);
		return $resultado;
	}

	/**
	 * Indica si las respusetas a marcar 
	 * @return las respuestas con el estado de la sincronizacion
	 */
	private function sincronizar_respuestas(&$respuestas_a_marcar){
		if(!is_array($respuestas_a_marcar) || empty($respuestas_a_marcar)) //por ws no viajan bien los arreglos vacios
			return array();

		foreach($respuestas_a_marcar as &$rta){
			$habilitacion = (int)$rta[0];
			$cui = quote($rta[1]);
			$formulario = quote($rta[2]);
			$id_rta = $this->get_respuesta($habilitacion, $cui, $formulario);
			if(is_null($id_rta)){
				$rta[3] = self::SYNC_ERROR; //guarani me la paso, pero no la tengo yo.
				continue;
			}
			$ok = $this->actualizar_estado_respuesta($id_rta, self::STATUS_ENVIADO);
			if($ok){ //devuelve true si marca algo
					$rta[3] = self::SYNC_OK;
			}else {
				$rta[3] = self::SYNC_ERROR;
			}
		}
		return $respuestas_a_marcar;
	}
	
	/**
	 * Registra respuestas que se enviaron al sistema externo, pero el sistema
	 * externo no reconoce.
	 * @param unknown $respuestas_inexistentes
	 */
	private function registrar_respuestas_inexistentes($respuestas_a_marcar){
		if(!is_array($respuestas_a_marcar) || empty($respuestas_a_marcar)) //por ws no viajan bien los arreglos vacios
			return array();
		
		foreach($respuestas_a_marcar as &$rta){
			$habilitacion = (int)$rta[0];
			$cui = quote($rta[1]);
			$formulario = quote($rta[2]);
			$id_rta = $this->get_respuesta($habilitacion, $cui, $formulario);
			if(is_null($id_rta)){
				$rta[3] = self::SYNC_ERROR; //guarani me la paso, pero no la tengo yo.
				continue;
			}
			$ok = $this->actualizar_estado_respuesta($id_rta, self::STATUS_ERROR);
			if($ok){ //devuelve true si marca algo
				$rta[3] = self::SYNC_OK;
			}else {
				$rta[3] = self::SYNC_ERROR;
			}
		}
		return $respuestas_a_marcar;
	}
	
	private function obtener_respuestas_no_enviadas(){
		$estado = quote(self::STATUS_PENDIENTE);
		$sql = "SELECT 
				e.habilitacion,
				e.codigo_externo, 
				c.concepto_externo
			  FROM 
				sge_encuestas_externas e
				INNER JOIN sge_concepto c ON 
				c.concepto = e.concepto
			  WHERE
				e.estado = $estado AND
				e.sistema = {$this->sistema}";
		return kolla_db::consultar($sql, toba_db_fetch_num);
	}

	private function get_respuesta($habilitacion, $cui, $formulario){
		$stat = self::STATUS_PENDIENTE;
		//cambiar a select exists
		$sql = "SELECT 
				e.encuesta_externa as id
			  FROM 
				sge_encuestas_externas e
				INNER JOIN sge_concepto c ON 
				c.concepto = e.concepto
			  WHERE
				e.sistema = {$this->sistema} AND
				e.habilitacion = $habilitacion AND
				e.codigo_externo = $cui AND
				c.concepto_externo = $formulario";
		$res = kolla_db::consultar($sql);
		if(isset($res[0]))
			return $res[0]['id'];
		else return NULL;
	}
	
	/**
	 * Marca como enviada una respuesta, indica verdadero si se realizo algun cambio
	 * Si da falso, y la respuesta existe, es porque ya estaba marcada enviada.
	 */
	private function actualizar_estado_respuesta($id_encuesta_externa, $estado){
		$id = (int)$id_encuesta_externa;
		$state = quote($estado);
		$sql = "UPDATE sge_encuestas_externas 
					SET estado = $state
			    WHERE
				sistema = {$this->sistema} AND
				encuesta_externa = $id";
		return count(kolla_db::ejecutar($sql)) == 1;
	}
}

?>
