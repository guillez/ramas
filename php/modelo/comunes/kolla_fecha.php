<?php
/**
 * Métodos relacionados a la manipulación de fechas.
 * 
 */
class kolla_fecha
{
	/**
	 * Obtiene la fecha actual de la base.
	 *
	 * @param boolean $formato_visual Determina si se desea o no recibir la fecha formateada.
	 */
	static function get_hoy($formato_visual = false)
	{
		if ($formato_visual) {
			$sql = 'SELECT to_char('.kolla_sql::fecha_actual.", '".kolla_sql::formato_fecha_visual."')  AS fecha";
		} else {
			$sql = 'SELECT '.kolla_sql::fecha_actual.' AS fecha';
		}
		
		$result = kolla_db::consultar_fila($sql);
		return $result['fecha'];
	}
	
	/**
	 * Obtiene la fecha y hora actual de la base.
	 *
	 * @param boolean $formato_visual Determina si se desea o no recibir la fecha y hora formateada.
	 */
	static function get_hoy_hora($formato_visual = false)
	{
		if ($formato_visual) {
			$sql = 'SELECT to_char('.kolla_sql::fecha_hora_actual.", '".kolla_sql::formato_fecha_hora_visual_sin_segundos."')  AS fecha_hora";
		} else {
			$sql = 'SELECT '.kolla_sql::fecha_hora_actual.' AS fecha_hora';
		}
		
		$result = kolla_db::consultar_fila($sql);
		return $result['fecha_hora'];
	}
	
	/**
	 * Obtiene una parte de la fecha actual de la base.
	 *
	 * @param str $parte Determina qué parte de la fecha se desea.
	 *					 Los valores pueden ser: 'dia', 'hora', 'anio', 'hora', 'minuto' o 'segundo'.
	 */
	static function get_hoy_parte($parte)
	{
		switch ($parte) {
				case 'dia' :
					$parte = 'day';
					break;
				case 'mes':
					$parte = 'month';
					break;
				case 'anio':
					$parte = 'year';
					break;
				case 'hora':
					$parte = 'hour';
					break;
				case 'minuto':
					$parte = 'minute';
					break;
				case 'segundo':
					$parte = 'second';
					break;
		}
		
		$sql = "SELECT EXTRACT($parte FROM CURRENT_TIMESTAMP)::integer AS parte";
		$rs = kolla_db::consultar_fila($sql);
		return $rs['parte'];
	}
	
	/**
	 *  Retorna el String necesario para crear un objeto de tipo Date en JS con la fecha actual
	 * @return <type>
	 */
	static function get_hoy_js()
	{
		$dia = self::get_hoy_parte('dia');
		$mes = self::get_hoy_parte('mes') - 1;
		$anio = self::get_hoy_parte('anio');
		return "Date($anio, $mes, $dia)";
	}
	
	/**
	 * Retorna los dias de la semana
	 */
	static function get_dias_semana()
	{
		$dias[0]['id'] = '0';
		$dias[0]['desc'] = 'Lunes';
		$dias[1]['id'] = '1';
		$dias[1]['desc'] = 'Martes';
		$dias[2]['id'] = '2';
		$dias[2]['desc'] = 'Miercoles';
		$dias[3]['id'] = '3';
		$dias[3]['desc'] = 'Jueves';
		$dias[4]['id'] = '4';
		$dias[4]['desc'] = 'Viernes';
		$dias[5]['id'] = '5';
		$dias[5]['desc'] = 'Sabado';
		$dias[6]['id'] = '6';
		$dias[6]['desc'] = 'Domingo';
		return $dias;
	}

	/**
	 * Devuelve un dia con el formato que necesita el DAO
	 */
	static function get_dia_semana($dia)
	{
		$dias = self::get_dias_semana();
		$d[0]['desc_dia_semana'] = $dias[$dia]['desc'];
		return $d;
	}

	/**
	 * Retorna las horas del dia
	 */
	static function get_horas_dia()
	{
		for ($a = 0; $a < 24; $a++) {
			$horas[$a]['id'] = $a+1;
			$horas[$a]['desc'] = str_pad($a + 1, 2, 0, STR_PAD_LEFT);
		}
		return $horas;
	}

	static function es_mayor($fecha1, $fecha2, $igual=false, $timestamp=false)
	{
		if (!empty($fecha1) && !empty($fecha2)) {
			$fecha1 = kolla_db::quote($fecha1);
			$fecha2 = kolla_db::quote($fecha2);
			$tipo = $timestamp ? 'TIMESTAMP' : 'DATE';
			$igual ? $operador = ' >= ' : $operador = ' > ';
			$sql = "SELECT ($tipo $fecha1 $operador $tipo $fecha2) AS es_mayor";
			$rs = kolla_db::consultar_fila($sql);
			return $rs['es_mayor'];
		}
	}
	
	static function es_menor_a_fecha_actual($fecha, $igual=false)
	{
		if (!empty($fecha)) {
			$fecha = kolla_db::quote($fecha);
			$igual ? $operador = ' <= ' : $operador = ' < ';
			$sql = "SELECT (DATE $fecha $operador ".kolla_sql::fecha_actual.') AS es_menor';
			$rs = kolla_db::consultar_fila($sql);
			return $rs['es_menor'];
		}
	}
	
	static function es_igual($fecha1, $fecha2)
	{
		if (!empty($fecha1) && !empty($fecha2)) {
			$fecha1 = kolla_db::quote($fecha1);
			$fecha2 = kolla_db::quote($fecha2);
			$sql = "SELECT (DATE $fecha1 = DATE $fecha2) AS es_igual";
			$rs = kolla_db::consultar_fila($sql);
			return $rs['es_igual'];
		}
		return false;
	}

	/**
	 * Por defecto devuelve la fecha de la base.
	 * Opcionalmente se le puede pedir que parte de la fecha se necesita.
	 */
	static function get_parte($fecha, $parte)
	{
		$fecha = kolla_db::quote($fecha);

		switch ($parte) {
			case 'dia':
				$parte = 'day';
				break;
			case 'mes':
				$parte = 'month';
				break;
			case 'anio':
			case 'año':
				$parte = 'year';
				break;
		}

        $sql = "SELECT EXTRACT($parte FROM DATE $fecha)::integer AS parte";
 		$rs = kolla_db::consultar_fila($sql);
		return $rs['parte'];
	}
	
}
?>