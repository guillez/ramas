<?php
/**
 * Mיtodos relacionados a la manipulaciףn de cadenas de texto.
 * 
 */
class kolla_texto
{
	const chars_maximo_combo = 88;
	
	static function armar_lista($array, $separador=', ', $separador_final=null)
	{
		if (!empty($array)) {
			if (count($array) == 1 || !$separador_final) return implode($separador, $array);
			$todos_menos_el_ultimo = array_slice($array, 0, count($array) - 1 );
			$ultimo_elemento = implode('', array_slice($array, - 1, 1 ));
			if ($separador_final == ' y ' && (substr($ultimo_elemento, 0, 1) == 'i' || substr($ultimo_elemento, 0, 2) == 'hi')) {
				$separador_final = ' e ';
			}
			return implode($separador, $todos_menos_el_ultimo).$separador_final.$ultimo_elemento;
		}
	}

	static function preparar_comparacion($texto)
	{
		return trim(self::simplificar_espacios(self::limpiar_acentos($texto)));
	}
	
	static function simplificar_espacios($texto)
	{
		$buscar = '  ';
		$reemplazar = ' ';
		while (strpos($texto, '  ') !== false) {
			$texto = str_replace($buscar, $reemplazar, $texto);
		}
		return $texto;
	}
	
	static function limpiar_acentos($texto)
	{
		$buscar = 'ְֱֲֳִֵאבגדהוׂ׃װױײ״עףפץצרָֹÊֻטיךכּֽ־ֿלםמןÙÚÛÜשתûüÿ';
		$reemplazar = 'AAAAAAaaaaaaOOOOOOooooooEEEEeeeeIIIIiiiiUUUUuuuuy';
		return(strtr($texto, $buscar, $reemplazar));
	}
	
}
?>