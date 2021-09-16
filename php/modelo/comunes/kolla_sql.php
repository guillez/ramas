<?php
/**
 * Mיtodos relacionados al armado de consultas sql.
 * 
 */
class kolla_sql
{
	const fecha_actual 		= 'CURRENT_DATE';
	const fecha_hora_actual = 'CURRENT_TIMESTAMP';
	const hora_actual 		= 'CURRENT_TIME';
	
	const formato_fecha_visual = 'DD/MM/YYYY';
	const formato_fecha_visual_sin_anio = 'DD/MM';
	const formato_fecha_hora_visual = 'DD/MM/YYYY HH24:MI:SS';
	const formato_fecha_hora_visual_sin_segundos = 'DD/MM/YYYY HH24:MI';
	const formato_hora_visual = 'HH24:MI';
	
	/*
	 * Arma una condiciףn de ILIKE para comparar un campo de la base con un texto que se quiere ingresar.
	 * Lo que hace es quitarle los tildes a ambos y simplificar los espacios
	 */
	static function armar_condicion_compara_cadenas($campo, $valor)
	{
		return self::preparar_comparacion($campo).' ILIKE '.kolla_texto::preparar_comparacion($valor);
	}
	
	static function preparar_comparacion($campo)
	{
		return 'trim('.self::simplificar_espacios(self::limpiar_acentos($campo)).')';
	}
	
	static function simplificar_espacios($texto)
	{
		return "regexp_replace($texto, '[ ]{2,}', ' ','g')";
	}
	
	static function limpiar_acentos($texto)
	{
		$buscar = 'ְֱֲֳִֵאבגדהוׂ׃װױײ״עףפץצרָֹÊֻטיךכּֽ־ֿלםמןÙÚÛÜשתûüÿ';
		$reemplazar = 'AAAAAAaaaaaaOOOOOOooooooEEEEeeeeIIIIiiiiUUUUuuuuy';
		return "translate($texto, '$buscar', '$reemplazar')";
	}
    
    /**
	 * Convierte un array asociativo en una sentencia de INSERT si es que el registro no existe
	 * @param array $datos Array asociativo con el formato 'columna' => 'valor'
	 */
	static function sql_array_a_insert_if_not_exists($tabla, $datos, $db = null)
	{
		if (!is_array($datos)) {
			throw new toba_error("Los datos tienen que ser un array");	
		}
        
		if (!$db) {
			$db = toba::db();
		}
        
        if (!empty($datos)) {
			$where = limpiar_array_sql($datos, $db);
			$wheres = array();
			foreach ($where as $k => $v) {
				$wheres[] = "$k = $v";
			}
			$w = implode(' AND ', $wheres);
		}
        
		foreach (array_keys($datos) as $columna) {
			if (is_null( $datos[$columna] )) {
				$datos[$columna] = "NULL";
			} else {
				if (is_resource($datos[$columna])) {
					$datos[$columna] = stream_get_contents($datos[$columna]);
				}
                
				if (is_bool($datos[$columna])) {
					$datos[$columna] = ($datos[$columna]) ? 'TRUE' : 'FALSE';
				} else {
					$datos[$columna] = $db->quote($datos[$columna]);
				}
			}
		}
        
		$sql =  "INSERT INTO $tabla (".implode(", ",array_keys($datos)).") ". 
                "SELECT ".implode(", ", $datos)." ".
                "WHERE NOT EXISTS (SELECT * FROM $tabla WHERE $w);";
        
		return $sql;	
	}
	
}
?>