<?php

class consultas_ug
{
	function get_listado($where = null)
	{
		$where .= $where ? '' : ' TRUE ';
		
		$sql = "SELECT		sge_unidad_gestion.unidad_gestion,
							sge_unidad_gestion.nombre
	            FROM		sge_unidad_gestion
				WHERE		$where
				ORDER BY	sge_unidad_gestion.nombre
				";
        
        return kolla_db::consultar($sql);
    }
    
    /*
     * Retorna la lista de Unidades de Gestión para el combo
     */
    function get_unidad_gestion_combo()
    {
    	$sql = "SELECT		sge_unidad_gestion.unidad_gestion,
        					sge_unidad_gestion.nombre
	            FROM		sge_unidad_gestion
	            ORDER BY	sge_unidad_gestion.nombre
	        	";
        
        return kolla_db::consultar($sql);
    }
    
    /*
     * Retorna la lista de Unidades de Gestión para el combo,
     * que sean distintas a la UG dada somo parámetro
     */
    function get_unidad_gestion_destino_combo($unidad_gestion)
    {
        $unidad_gestion = kolla_db::quote($unidad_gestion);
		$where = isset($unidad_gestion) ? " WHERE sge_unidad_gestion.unidad_gestion <> $unidad_gestion " : '';
        
    	$sql = "SELECT		sge_unidad_gestion.unidad_gestion,
        					sge_unidad_gestion.nombre
	            FROM		sge_unidad_gestion
                $where
	            ORDER BY	sge_unidad_gestion.nombre
	        	";
        
        return kolla_db::consultar($sql);
    }
    
	/*
	 *  Valida si un nombre de Unidad de Gestión se puede usar o no.
     */
	function validar_nombre_unidad_gestion($nombre, $unidad_gestion=null)
	{
		if (!isset($nombre)) {
			return false;
		}
		
		$nombre = kolla_db::quote($nombre);
		$sql = 'SELECT 	COUNT(sge_unidad_gestion.unidad_gestion) AS cant 	
                FROM 	sge_unidad_gestion
				WHERE 	'.kolla_sql::armar_condicion_compara_cadenas('sge_unidad_gestion.nombre', $nombre);
		
		if (!is_null($unidad_gestion)) {
			$unidad_gestion = kolla_db::quote($unidad_gestion);
			$sql .= " AND sge_unidad_gestion.unidad_gestion <> $unidad_gestion ";
		}
		
		$res = kolla_db::consultar_fila($sql);
		return ($res['cant'] == 0);
	}
    
	/*
	 *	Retorna el nombre de una unidad de gestión dado su id
	 */
	function get_nombre($id = null)
	{
		if (!is_null($id)) {
			$id = toba::db()->quote($id);
		   	$sql = "SELECT	sge_unidad_gestion.nombre
		            FROM	sge_unidad_gestion
					WHERE 	sge_unidad_gestion.unidad_gestion = $id
					";
			$resultado = kolla_db::consultar_fila($sql);
			return $resultado['nombre'];
		}
		
		return '';
	}
}
?>