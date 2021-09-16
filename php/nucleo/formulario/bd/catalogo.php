<?php

/**
 * En vez de generar los metadatos automaticamente hay que configurarlo manualmente.
 * Lo que devuelve es un arreglo posicional de id, si se cachea y expire_time. 
 * Al id se agregan los parametros y un prefijo del proyecto y clase.
 * El que no está y se consulta por catalogo tira error. Esto es para prevenir
 * que por error no se este cacheando (y por eso el 2do param se hace necesario)
 */
abstract class catalogable {

	abstract function getCacheInfo($metodo);

	abstract function getIdClase();
}

class catalogo {

	protected static $cache_memoria;
	protected static $tipo_cache = admin_cache::tipo_memoria;

	const prefijo_memcache = 'klla_';

	//----------------------------------------------------
	// Acceso al CATALOGO
	//----------------------------------------------------

	/**
	 *
	 * @param catalogable $obj
	 * @param string $metodo
	 * @param array $parametros
	 * @param array $opciones. El unico soportado es "cache" que sobreescribe la config actual
	 * @return array
	 */
	static function consultar(Catalogable $obj, $metodo, $parametros = array(), $opciones = array()) {
		$config = $obj->getCacheInfo($metodo);
		$id_met = $config[0];
		$usar_cache = $config[1];
		$time = $config[2];
		kolla::logger()->debug("Catalogo: consultando {$obj->getIdClase()} - $metodo");
		kolla::logger()->var_dump($parametros);

		$id_dato = self::generar_id_cache($obj->getIdClase(), $id_met, $parametros);

		if (isset($opciones['cache']))
			$usar_cache = $opciones['cache'];
		//$c = self::get_cache_memoria();
		if ($usar_cache) {
			$c = admin_cache::instancia(self::$tipo_cache);
			if ($c == null) {
				$dato = call_user_func_array(array($obj, $metodo), $parametros);
				return $dato;
			}

			if ($c->existe($id_dato)) {
				kolla::logger()->debug("obteniendo dato $id_dato del cache");
				$dato = $c->buscar($id_dato);
			} else {
				$dato = call_user_func_array(array($obj, $metodo), $parametros);
				$c->guardar($id_dato, $dato, $time) or kolla::logger()->info('Cuidado- No se puede conectar a memcached');
			}
		} else {
			$dato = call_user_func_array(array($obj, $metodo), $parametros);
		}
		return $dato;
	}

	static function limpiar_cache(Catalogable $obj, $metodo, $parametros = array()) {
		$config = $obj->getCacheInfo($metodo);
		$id_met = $config[0];
		$usar_cache = $config[1];
		//$time = $config[2];

		$id_dato = self::generar_id_cache($obj->getIdClase(), $id_met, $parametros);
		if ($usar_cache) {
			$c = admin_cache::instancia(self::$tipo_cache);
			$c->eliminar($id_dato);
		}
	}

	static private function generar_id_cache($clase, $metodo, $parametros) {
		$separador = '__';
		$separador_array = '_';
		$param = '';
		if (empty($parametros)) {
			$clave = '_nopar_';
		} else {
			ksort($parametros); //ordenar
			$param = implode($separador_array, $parametros);
			$param = base64_encode($param);
			$param = str_replace(array('/', '+', '='), '_', $param);
		}
		$clave = self::prefijo_memcache . $clase . $separador . $metodo . $separador . $param;
		return $clave;
	}

}

?>
