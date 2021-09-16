<?php

class abm
{
	static protected function get_valor_sql($valor)
	{
		if ($valor === null) {
			return 'NULL';
		} elseif (is_bool($valor)) {
			return $valor ? 'true' : 'false';
		}
		return quote($valor);
	}
	
	/**
	 * Alta de datos en una tabla.
	 */
	static function alta($tabla, $datos, $esquema='')
	{
		$campos = implode(', ', array_keys($datos));
		$valores = array();
		
		foreach ($datos as $clave => $valor) {
			$valores[$clave] = self::get_valor_sql($valor);
		}
		
		$valores = implode(', ', $valores);
		$esquema = $esquema != '' ? "$esquema." : '';
		$sql = "INSERT INTO $esquema$tabla ($campos) VALUES ($valores)";
		kolla_db::ejecutar($sql);
	}
	
	/**
	 * Baja de datos en una tabla.
	 */
	static function baja($tabla, $clave)
	{		
		$where = self::get_where($clave);
		$sql = "DELETE FROM $tabla WHERE $where";

		kolla_db::ejecutar($sql);
	}
	
	/**
	 * Modificación de datos en una tabla de acuerdo a su clave. Opcionalmente se
	 * puede enviar el esquema de la base de datos en donde realizar la consulta.
	 */
	static function modificacion($tabla, $datos, $clave, $esquema='')
	{
		$where = self::get_where($clave);
		$valores = array();
		
		foreach ($datos as $campo => $valor) {
			$valores[] = $campo . ' = '. self::get_valor_sql($valor);
		}
		
		$valores = implode(', ', $valores);
		$sql  = $esquema != '' ? "SET search_path TO $esquema;" : '';
		$sql .= "UPDATE $tabla SET $valores WHERE $where;";
		kolla_db::ejecutar($sql);
	}
	
	/**
	 * Determina si el nuevo set de datos difiere del actualmente cargado en base,
	 * esto evita que se haga un update y se actualize una vigencia por ejemplo.
	 */
	function hubo_cambios_tabla($tabla, $clave, $datos_nuevos) 
	{
		$campos = array_keys($datos_nuevos);
		$where = self::get_where($clave);
		$sql = 'SELECT '.implode(', ', $campos)." FROM $tabla WHERE $where";
		$rs = kolla_db::consultar_fila($sql);
		
		if (empty($rs)) {
			return true;
		} else {
			foreach($datos_nuevos as $campo => $valor) {
				if (is_bool($rs[$campo])) {	//Caso particular campos boolean en base
					$rs[$campo] = ($rs[$campo]) ? 1 : 0;
				}
				if (is_bool($valor)) {		//Caso particular campos boolean en php
					$valor = ($valor) ? 1 : 0;
				}
				if (trim($rs[$campo]) != trim($valor)) {
					return true;
				}
			}
			return false;
		}
	}

	/**
	 * Devuelve true/false si existe al menos 1 registro en la tabla
	 * @param type $tabla
	 * @param type $clave arreglo columna=> valor
	 * @param string $esquema
	 * @return boolean
	 */
	static function existen_registros($tabla, $clave, $esquema='')
	{
		$esquema = $esquema != '' ? "$esquema." : '';
		$where = self::get_where($clave);
		$sql = "SELECT EXISTS (SELECT 1 FROM $esquema$tabla WHERE $where) AS rta";
		$tiene_rtas = kolla_db::consultar_fila($sql);
		return $tiene_rtas['rta'];
	}
	
	static function get_where($clave) 
	{
		$where = 'TRUE';
		if (!empty($clave)) {
			$where = array();
			foreach ($clave as $campo => $valor) {
                if (($valor || 0 == $valor) && !is_null($valor)) {
                    $valor = kolla_db::quote($valor);
                    $where[] = $campo.' = '.$valor;
                } else {
                    $where[] = $campo.' IS NULL ';
                }
			}
			$where = implode(' AND ', $where);
		}
		return $where;
	}
	
}

?>