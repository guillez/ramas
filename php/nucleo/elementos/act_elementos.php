<?php

use SIUToba\rest\lib\rest_error;
use SIUToba\rest\lib\rest_validador;

class act_elementos 
{

    /**
	 * Crea o actualiza un elemento. Si se pasa id en data, se actualiza el id_externo
	 * @param $id_externo
	 * @param $data
	 * @return int
	 * @throws rest\lib\rest_error
	 */
    function crear_o_actualizar_elemento($id_externo, $data)
    {
        $id_externo = kolla_db::quote($id_externo);
        $unidad_gestion  = kolla_db::quote($data['unidad_gestion']);
        $elemento   = toba::consulta_php('consultas_encuestas_externas')->get_elementos(" sge_elemento.elemento_externo = $id_externo AND ug.unidad_gestion = $unidad_gestion ");
        
        $descripcion_elemento = '';
        $url_img_elemento     = 'NULL';
        if (isset($elemento[0])) {
            $descripcion_elemento = kolla_db::quote($elemento[0]['descripcion']);
            $url_img_elemento     = kolla_db::quote($elemento[0]['url_img']);
        } 
        
        $descripcion     = isset($data['descripcion']) ? kolla_db::quote($data['descripcion']) : $descripcion_elemento;
        $id_externo_data = isset($data['elemento_externo']) ? kolla_db::quote($data['elemento_externo']) : $id_externo;
        $url             = isset($data['url_img']) ? kolla_db::quote($data['url_img']) : $url_img_elemento;        
        $sistema         = (int) $data['sistema'];
        
        if ($id_externo != $id_externo_data) {
		    // Modifico el que tiene $id_externo y le seto como id id_externo_data
		    $sql = "UPDATE  sge_elemento
                    SET     descripcion = {$descripcion},
		                    elemento_externo = {$id_externo_data},
		                    url_img = {$url}
					WHERE   elemento_externo = {$id_externo }
					AND     sistema = {$sistema}
					AND     unidad_gestion = {$unidad_gestion}";
		    $res = kolla_db::ejecutar($sql);
            
		    if ($res == 1) {
			    $codigo = 1;
			    return $codigo;
		    } else {
			    throw new rest_error(404, 'El elemento no existe. No se puede cambiar el identificador.');
		    }
	    } else {
            $sql = "SELECT  *
                    FROM    sp_upsert_elemento ({$id_externo}, {$descripcion}, {$url}, {$sistema}, {$unidad_gestion})
                            AS (id int, codigo int, descrip text)";
		    $res = kolla_db::consultar_fila($sql);
            
		    $codigo = $res['codigo'];
		    if (isset($res['id'])) {
			    return $codigo;
		    }
	    }
	    throw new rest_error(400, 'Error al crear/modificar el elemento.');
    }
    
    function eliminar_elemento($id_externo, $unidad_gestion, $sistema)
    {
        $id_externo      = kolla_db::quote($id_externo);
        $unidad_gestion  = kolla_db::quote($unidad_gestion);
        $sistema         = (int) $sistema;
        
        $sql = "DELETE FROM sge_elemento
                WHERE       elemento_externo = $id_externo
				AND         sistema = $sistema
				AND         unidad_gestion = $unidad_gestion";
        try{
            kolla_db::ejecutar($sql);
        }
        catch(toba_error_db $e)
        {
            throw new rest_error(500,"No se puede eliminar por integridad de base de datos");
        }
    }
    
}