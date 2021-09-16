<?php
/**
 * Métodos relacionados a la manipulación de arreglos y matrices.
 * 
 */
class kolla_arreglos
{
	/**
     * Toma una matriz y lo aplana a una sola dimension conteniendo los valores de un campo dado. Si no se especifica 
     * un campo, se elige el primero de los recordsets componentes de la matriz. Si un recordset dado contiene un valor
     * nulo para ese campo, entonces dicho valor no se agrega al resultado aplanado.
     * Util para aplanar recordset de consultas o de filas de un datos tabla.
     * Ej: array(0 => array('campo' => 'cero', 'c2' => ...), 1 => array('campo' => 'uno', 'c2' => ...), 
     * 			 2 => array('campo' => null  , 'c2' => ...))  (return) --->  array('cero', 'uno')
     */
	static function aplanar_matriz_sin_nulos($matriz, $campo = null)
	{
		$aplanado = array();
		foreach ($matriz as $clave => $arreglo) {
			//Compara igualdad de valores y de tipos.
			if ($campo === null && !is_null(current($arreglo))) {
				$aplanado[$clave] = current($arreglo);
			} elseif (isset($arreglo[$campo]) && !is_null($arreglo[$campo])) {
				$aplanado[$clave] = $arreglo[$campo];
			}
		}
		return $aplanado;
	}
	
	function promedio_longitud_campo_matriz($matriz, $campo)
	{
		foreach ($matriz as $clave => $arreglo) {
			$matriz[$clave]['longitud'] = strlen($arreglo[$campo]);
		}
		return array_sum(self::aplanar_matriz_sin_nulos($matriz, 'longitud'))/count($matriz);
	}
	
}
?>