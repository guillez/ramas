<?php

class co_elementos 
{

    function get_listado_rest($where = '', $order='', $limit = '', $filtro = array())
    {
		$from = '';
        
        $sql = "
            SELECT
                sge_elemento.*
            FROM
                sge_elemento
            WHERE
                $where
                $order
                $limit
        ";
        
        return kolla_db::consultar($sql);
    }

	function get_elemento_id_externo($sistema, $id_externo, $unidad_gestion)
	{
		$id_externo     = kolla_db::quote($id_externo);
		$unidad_gestion = kolla_db::quote($unidad_gestion);
		$sistema        = (int) $sistema;
        
		$sql = "SELECT  sge_elemento.*
                FROM    sge_elemento
                WHERE   elemento_externo = {$id_externo}
                        AND sistema = {$sistema}
                        AND unidad_gestion = {$unidad_gestion}";
                        
		return kolla_db::consultar_fila($sql);
	}
}
