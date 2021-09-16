<?php 
class ws_habilitar_parametros
{
	private $hab_p;
	public $es_debug;

	function __construct()
	{
	}
	
	function cargar_parametros($params)
	{
		$this->cargar_habilitacion($params);
		$this->cargar_url_fotos($params);
		$this->cargar_fechas($params);
		$this->validar_fechas();
		$this->cargar_estilo($params);
		$this->cargar_ug($params);
		$this->cargar_paginado($params);
		$this->cargar_anonima($params);
		$this->cargar_debug($params);
		$this->cargar_elementos($params);
		
		return $this->hab_p;
	}
		
	/**
	 *Obtiene la encuesta correspondiente al parametro si es valida. Sino lanza
	 * un error.
	 * @param type $encuesta el id de la encusta
	 * @return type el id de la encuesta (si es valida).
	*/
	public function obtener_encuesta($encuesta)
	{
		if (isset($this->cache_enc[$encuesta])) return $this->cache_enc[$encuesta];
		$ug = $this->hab_p['unidad_gestion'];
		
		if (!is_numeric($encuesta)) {
			ws_habilitar::throw_error('error_formato_id_encuesta');
		}
		$sql = "SELECT 
					estado, implementada, nombre, unidad_gestion
				FROM 
					sge_encuesta_atributo
				WHERE 
					encuesta = $encuesta";
		
		$res = kolla_db::consultar($sql);
		if (empty($res)) {
			ws_habilitar::throw_error('encuesta_inexistente', $encuesta);
		}
		if ($res[0]['estado'] != 'A') {
			ws_habilitar::throw_error('encuesta_no_activa');
		}
		if ($res[0]['implementada'] != 'S') {
			ws_habilitar::throw_error('encuesta_no_implementada');
		}
		
		//Si existe la unidad de gestión y es distinta a la pasada por parámetro: Error
		if (!empty($res[0]['unidad_gestion']) && $res[0]['unidad_gestion'] != $ug) {
			ws_habilitar::throw_error('encuesta_no_ug');
		}
		
		$this->cache_enc[$encuesta] = (int)$encuesta;
		return $this->cache_enc[$encuesta];
	}

	private function cargar_debug($params)
	{
		if (isset($params['debug'])) {
			if (($params['debug'] == 'S')) {
				ws_habilitar::log_info('debug', debug_log::codigo_habilitado, '', 'S');
				$this->es_debug = true; //para los tests unitarios. Evita que se escriba en la base
			} else if (($params['debug'] == 'N')) {
				ws_habilitar::log_info('debug', debug_log::codigo_deshabilitado, '', 'N');
				$this->es_debug = false;
			} else {
				$this->es_debug = true;
				ws_habilitar::log_info('debug', debug_log::codigo_habilitado, '{no se reconoce parametro}', 'S');
			}
		} else {
			$this->es_debug = false;
			ws_habilitar::log_info('debug', debug_log::codigo_defecto, '{deshabilitado por omision}', 'N');
		}
	}

	private function cargar_elementos($params)
	{
		if (isset($params['elementos']) && is_array($params['elementos'])) {
			$items = array();
			foreach ($params['elementos'] as &$item) {
				$item['id_ex'] = (isset($item['id_ex']))? $item['id_ex']:('');
				$item['dsc'] = (isset($item['dsc']))? $item['dsc']:'';
				$item['url'] = (isset($item['url']))? $item['url']:'';
                                
				$items[] = $item; //falta validar!
			}
			$this->hab_p['elementos'] = $items;
		}
			
		if (isset($params['formularios']) && is_array($params['formularios'])) {
			$this->hab_p['formularios'] = $params['formularios'];
		}
		return;
	}

	private function cargar_paginado($params)
	{
		$paginado = $this->obtener_si_no('paginado', $params);
		$this->hab_p['paginado'] = $paginado;
	}

	private function cargar_anonima($params)
	{
		$anonima = $this->obtener_si_no('anonima', $params);
		$this->hab_p['anonima'] = $anonima;
	}

	private function cargar_habilitacion($params)
	{
		if (isset($params['habilitacion'])) {
			if (!is_numeric($params['habilitacion'])) {
				ws_habilitar::throw_error('error_formato_id_habilitacion');
			}
			$this->hab_p['habilitacion'] = $params['habilitacion'];
		} else {
			$this->hab_p['habilitacion'] = -1;
		}
	}
	
	private function cargar_url_fotos($params)
	{
		if (isset($params['url_imagenes_base'])) {
			$this->hab_p['url_imagenes_base'] = $params['url_imagenes_base'];
		} else {
			$this->hab_p['url_imagenes_base'] = null;
		}
	}

	private function cargar_fechas($params)
	{
		if (!isset($params['fecha_desde'])) {
			ws_habilitar::throw_error('fecha_nula');
		}
		$desde = $this->validar_fecha($params['fecha_desde']);

		if (!isset($params['fecha_hasta'])) {
			ws_habilitar::throw_error('fecha_nula');
		}
		$hasta = $this->validar_fecha($params['fecha_hasta']);

		$this->hab_p['fecha_desde'] = $desde;
		$this->hab_p['fecha_hasta'] = $hasta;
	}

	private function validar_fecha($fecha)
	{
		list($y, $m, $d) = explode('-', $fecha);
		if (!checkdate($m, $d, $y)) {
			ws_habilitar::throw_error('error_formato_fecha');
		}
		return $fecha;
	}

	private $cache_enc = array();
	
	private function cargar_estilo($params)
	{
		if (isset($params['estilo'])) {
			$estilo = $this->obtener_estilo($params['estilo']);
		} else {
			$estilo = $this->obtener_estilo_defecto();
		}
		$this->hab_p['estilo'] = $estilo;
	}
	
	private function cargar_ug($params)
	{
		if (isset($params['unidad_gestion'])) {
			$ug = $params['unidad_gestion'];
			$qug = kolla_db::quote($params['unidad_gestion']);
			$sql = "SELECT unidad_gestion, nombre FROM sge_unidad_gestion WHERE unidad_gestion = $qug";
			$res = kolla_db::consultar($sql);
			if (empty($res)) {
				ws_habilitar::throw_error('ug_inexsistente');
			}
		} else {
			$ug = 0;
		}
		$this->hab_p['unidad_gestion'] = $ug;
	}

	/**
	 * Obtiene el estilo correspondiente al parametro si es valido, sino
	 * devuelve uno por defecto.
	 */
	private function obtener_estilo($estilo)
	{
		if (is_numeric($estilo)) {
			$sql = "SELECT nombre FROM sge_encuesta_estilo WHERE estilo = $estilo;";
			$res = kolla_db::consultar($sql);
			if (empty($res)) {
				ws_habilitar::throw_error('estilo_inexsistente');
			}
			ws_habilitar::log_info('estilo', estilo_log::codigo_seleccionado, $res[0]['nombre'], $estilo);
			return $estilo;
		}
		return $this->obtener_estilo_defecto();
	}
	private function obtener_estilo_defecto()
	{
		$defecto = 1;
		ws_habilitar::log_info('estilo', estilo_log::codigo_defecto, '', $defecto);
		return $defecto;
	}

	/**
	 * Determina si el parametro es 'S' o 'N'. Por defecto es 'N'
	 * @param type $str clave en el arreglo params
	 * @param type $params el arreglo que contendria el parametro
	 * @return string 'S', 'N'
	 */
	private function obtener_si_no($str, $params)
	{
		//uso las constantes de paginado, solo funciona para los que tengan la misma
		if (isset($params[$str])) {
			if ($params[$str] == 'S') {
				ws_habilitar::log_info($str, paginado_log::codigo_habilitado, '', 'S');
				return 'S';
			} else if ($params[$str] == 'N') {
				ws_habilitar::log_info($str, paginado_log::codigo_deshabilitado, '', 'N');
				return 'N';
			}
		}
		ws_habilitar::log_info($str, paginado_log::codigo_defecto, '', 'N');
		return 'N';
	}

	function validar_fechas()
	{
		$fecha_desde = $this->hab_p['fecha_desde'];
		$fecha_hasta = $this->hab_p['fecha_hasta'];
		//$encuesta = $this->hab_p['encuesta'];
		//$sistema = $this->sistema;
		//valido que la fecha de finalizacion no sea inferior a la de comienzo
		if ($fecha_hasta < $fecha_desde) {
			ws_habilitar::throw_error('error_orden_fechas');
		}
	}

}
?>