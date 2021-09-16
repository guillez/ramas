<?php
//MUG = Módulo de Ubicación Geográfica
class consultas_mug
{
	function get_paises($where=null) 
	{
		$where = isset($where) ? " WHERE ".$where : '';
		$sql = "SELECT 
					pais		as pais,
					nombre		as nombre,
					codigo_iso 	as codigo_iso
				FROM
					mug_paises
				$where
				ORDER BY nombre
		;";
		$res = consultar_fuente($sql);
		return $res;
	}
	
	function get_provincias($where=null) 
	{
		$where = isset($where) ? " WHERE pais= ".$where : '';
		$sql = "SELECT 
					p.provincia		as provincia,
					p.nombre		as nombre,
					p.pais			as pais
				FROM
					mug_provincias	as p
				$where
				ORDER BY nombre
		";
		$res = consultar_fuente($sql);
		return $res;	
	}

	function get_departamentos($where=null) 
	{
		$where = isset($where) ? " WHERE provincia= ".$where : '';
		$sql = "SELECT 
					dp.dpto_partido		as dpto_partido,
					dp.provincia		as provincia,
					dp.nombre			as nombre,
					dp.estado			as estado
				FROM
					mug_dptos_partidos as dp
				$where
				ORDER BY nombre
		";
		return consultar_fuente($sql);	
	}	

	function get_localidades($where=null) 
	{
		$where = isset($where) ? " WHERE localidad= ".$where : '';
		$sql = "SELECT 
					l.localidad			as localidad,
					l.dpto_partido		as dpto_partido,
					l.nombre			as nombre,
					l.nombre_abreviado	as nombre_abreviado,
					l.ddn				as ddn
				FROM
					mug_localidades		as l
				$where
				ORDER BY nombre
		";
		return consultar_fuente($sql);	
	}
	
	function get_localidades_por_partido($where=null) 
	{
		$where = isset($where) ? " WHERE dpto_partido= ".$where : '';
		$sql = "SELECT 
					l.localidad			as localidad,
					l.dpto_partido		as dpto_partido,
					l.nombre			as nombre,
					l.nombre_abreviado	as nombre_abreviado,
					l.ddn				as ddn
				FROM
					mug_localidades		as l
				$where
				ORDER BY nombre
		";
		return consultar_fuente($sql);	
	}
    
    function get_codigo_postal($localidad)
	{
        $localidad = kolla_db::quote($localidad);
        
		$sql = "SELECT  mug_cod_postales.id,
                        mug_cod_postales.codigo_postal
				FROM    mug_cod_postales
				WHERE   mug_cod_postales.localidad = $localidad
				";
		
        return kolla_db::consultar($sql);
	}

}

?>