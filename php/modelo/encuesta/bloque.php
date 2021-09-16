<?php

class bloque 
{

	static function get_max_orden($encuesta)
	{
		$encuesta = kolla_db::quote($encuesta);
		
		$sql = "
			SELECT
				COALESCE(MAX(b.orden), 0) + 1 AS max_orden
			FROM 
				sge_bloque AS b,
				sge_encuesta_definicion AS d
			WHERE
				b.bloque = d.bloque AND
				d.encuesta = $encuesta
		";
		
		$datos = kolla_db::consultar_fila($sql);
		
		return $datos['max_orden'];
	}
	
	static function get($bloque)
	{
		$bloque = kolla_db::quote($bloque);
		
		$sql = "
			SELECT
				*
			FROM
				sge_bloque
			WHERE
				bloque = $bloque
		";
		
		return kolla_db::consultar_fila($sql);
	}
	
}
?>
