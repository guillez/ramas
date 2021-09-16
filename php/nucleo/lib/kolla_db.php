<?php

class kolla_db
{
	public static function consultar($sql, $tipo_fetch=PDO::FETCH_ASSOC)
	{
        $sql = toba::perfil_de_datos()->filtrar($sql);
        return toba::db()->consultar($sql, $tipo_fetch);
	}

	public static function consultar_fila($sql, $tipo_fetch=PDO::FETCH_ASSOC)
	{
		$sql = toba::perfil_de_datos()->filtrar($sql);
		return toba::db()->consultar_fila($sql, $tipo_fetch);
	}

	public static function ejecutar($sql, $retornar_resultado=false, $deshabilitar_transaccion=false, $tipo_fetch=PDO::FETCH_ASSOC)
	{
		$sql = toba::perfil_de_datos()->filtrar($sql);
		if ($retornar_resultado) {
			if (!$deshabilitar_transaccion) {
				toba::db()->abrir_transaccion();
			}
			$resultado = toba::db()->consultar($sql, $tipo_fetch);
			if (!$deshabilitar_transaccion) {
				toba::db()->cerrar_transaccion();
			}
		} else {
			$resultado = toba::db()->ejecutar($sql);
		}
		return $resultado;
	}
    
    public static function quote($dato)
    {
        return toba::db()->quote($dato);
    }

}

?>