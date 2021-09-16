<?php

use SIUToba\rest\lib\rest_error;
use SIUToba\rest\lib\rest_validador;

class act_conceptos 
{
	/**
	 * Crea o actualiza un concepto. Si se pasa id en data, se actualiza el id_externo
	 * @param $id_externo
	 * @param $data
	 * @return int
	 * @throws rest\lib\rest_error
	 */
	function crear_o_actualizar_concepto($id_externo, $data)
    {
        if(!isset($data['descripcion'])){
            throw new rest_error(400,"Falta el campo descripcion para el concepto $id_externo");
        }
	    $descripcion    = kolla_db::quote($data['descripcion']);
	    $id_externo     = kolla_db::quote($id_externo);
	    $id_externo_data = isset($data['concepto_externo'])?  kolla_db::quote($data['concepto_externo']) : $id_externo;
	    $unidad_gestion = kolla_db::quote($data['unidad_gestion']);
	    $sistema        = (int) $data['sistema'];
        
	    if ( $id_externo != $id_externo_data ) {
		    // Modifico el que tiene $id_externo y le seto como id id_externo_data
		    $sql = "UPDATE  sge_concepto
                    SET     descripcion = {$descripcion},
		                    concepto_externo = {$id_externo_data}
					WHERE   concepto_externo = {$id_externo }
					AND 	sistema = {$sistema}
					AND 	unidad_gestion = {$unidad_gestion}";
                            
		    $res = kolla_db::ejecutar($sql);
            
		    if ( $res == 1 ) {
			    $codigo = 1;
			    return $codigo;
		    } else {
			    throw new rest_error(404, 'El concepto no existe. No se puede cambiar el identificador');
		    }
	    } else {
		    $sql = "SELECT  *
                    FROM    sp_upsert_concepto (
                        {$id_externo}::character varying,
                        {$descripcion}::character varying,
                        {$sistema}::integer,
                        {$unidad_gestion}::character varying
                        )
                            AS (id int, codigo int, descrip text)";
                    
		    $res = kolla_db::consultar_fila($sql);
            
		    $codigo = $res['codigo'];
		    if ( isset($res['id']) ) {
			    return $codigo;
		    }
	    }
	    throw new rest_error(400, 'Error al crear/modificar el concepto.');
	}
    
    function eliminar_concepto($id_externo, $unidad_gestion, $sistema)
    {
        $id_externo      = kolla_db::quote($id_externo);
        $unidad_gestion  = kolla_db::quote($unidad_gestion);
        $sistema         = (int) $sistema;
        
        $sql = "DELETE FROM sge_concepto
                WHERE       concepto_externo = $id_externo
				AND 		sistema = $sistema
				AND 		unidad_gestion = $unidad_gestion";
        try{
            kolla_db::ejecutar($sql);
        }
        catch (toba_error_db $e)
        {
           throw new rest_error(500,"No se puede eliminar por integridad de base de datos");
        }
    }
    
}