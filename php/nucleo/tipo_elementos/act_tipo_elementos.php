<?php

use SIUToba\rest\lib\rest_error;
use SIUToba\rest\lib\rest_validador;

class act_tipo_elementos 
{

    /**
	 * Crea o actualiza un elemento. Si se pasa id en data, se actualiza el id_externo
	 * @param $id_externo
	 * @param $data
	 * @return int
	 * @throws rest\lib\rest_error
	 */
    function crear_o_actualizar_tipo_elemento($id_externo, $data)
    {
        if (!isset($data['descripcion'])){
            throw new rest_error(400,'El tipo elemento $id_externo no contiene una descripcion');
        }
        $descripcion     = kolla_db::quote($data['descripcion']);
        $id_externo      = kolla_db::quote($id_externo);
        $id_externo_data = isset($data['tipo_elemento_externo']) ? kolla_db::quote($data['tipo_elemento_externo']) : $id_externo;
        $unidad_gestion  = kolla_db::quote($data['unidad_gestion']);
        $sistema         = (int) $data['sistema'];
        
        if ( $id_externo != $id_externo_data ) {
		    // Modifico el que tiene $id_externo y le seto como id id_externo_data
		    $sql = "UPDATE  sge_tipo_elemento
                    SET     descripcion = {$descripcion},
		                    tipo_elemento_externo = {$id_externo_data}
					WHERE   tipo_elemento_externo = {$id_externo }
							AND sistema = {$sistema}
						    AND unidad_gestion = {$unidad_gestion}";
                            
		    $res = kolla_db::ejecutar($sql);
            
		    if ( $res == 1 ) {
			    $codigo = 1;
			    return $codigo;
		    } else {
			    throw new rest_error(404, 'El tipo de elemento no existe. No se puede cambiar el identificador.');
		    }
	    } else {
		    $sql = "SELECT  *
                    FROM    sp_upsert_tipo_elemento ({$id_externo}, {$descripcion}, {$sistema}, {$unidad_gestion})
                            AS (id int, codigo int, descrip text)";
                    
		    $res = kolla_db::consultar_fila($sql);
            
		    $codigo = $res['codigo'];
		    if ( isset($res['id']) ) {
			    return $codigo;
		    }
	    }
	    throw new rest_error(400, 'Error al crear/modificar el tipo de elemento.');
    }
    
    function eliminar_tipo_elemento($id_externo, $unidad_gestion, $sistema)
    {
        $id_externo      = kolla_db::quote($id_externo);
        $unidad_gestion  = kolla_db::quote($unidad_gestion);
        $sistema         = (int) $sistema;
        
        $sql = "DELETE FROM sge_tipo_elemento
                WHERE       tipo_elemento_externo = $id_externo
							AND sistema = $sistema
						    AND unidad_gestion = $unidad_gestion";
        try{
            kolla_db::ejecutar($sql);
        }
        catch(toba_error_db $e)
        {
            throw new rest_error(500,"No se puede eliminar el tipo elemento por cuestiones de integridad");
        }
    }
    
}