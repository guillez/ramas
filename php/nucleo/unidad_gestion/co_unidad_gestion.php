<?php

class co_unidad_gestion 
{
    function get_ug($ug)
    {
        $ug = kolla::db()->quote($ug);
        
        $sql = "SELECT * FROM sge_unidad_gestion WHERE unidad_gestion = $ug";
        
        return kolla::db()->consultar_fila($sql);
         
    }
    
    function get_list()
    {
    	$sql = "SELECT * FROM sge_unidad_gestion ORDER BY nombre";
    	
    	return kolla::db()->consultar($sql);
    }
}
