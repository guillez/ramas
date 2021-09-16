<?php

class co_respuestas 
{
    protected $respuestas = array();
    
    function get_respuesta($respuesta)
    {
        if ( isset($this->respuestas[$respuesta]) ) {
            return $this->respuestas[$respuesta];
        }
        
        $respuesta = toba::db()->quote($respuesta);
        $sql = "SELECT  *
				FROM 	sge_respuesta
				WHERE   respuesta = $respuesta";
        
		$res = kolla_db::consultar_fila($sql);
        
        if ( $res ) {
            $this->respuestas[$respuesta] = $res;
        }
        return $res;
    }
    
    /**
	 * Retorna los datos de todas las respuestas en una tabla asociada
	 */
	function get_datos_tabla_asociada($tabla, $codigo, $descripcion, $orden_campo, $orden_tipo, $respuesta=null)
    {
		if ($orden_campo == 'codigo') {
			$campo = "t.$codigo";
		} else {
			$campo = "t.$descripcion";
		}
        
        $where = isset($respuesta) ? "t.$codigo = " . toba::db()->quote($respuesta) : 'TRUE';
		
		$sql = "SELECT      t.$codigo       AS respuesta,
                            t.$descripcion  AS valor_tabulado
                FROM        $tabla AS t
                WHERE       $where
                ORDER BY    $campo $orden_tipo";
		
		return kolla::db()->consultar($sql);
	}
    
    function get_encuesta_elemento_respuestas_detalle($habilitacion, $formulario_habilitado_externo, $elemento_externo, $id_bloque ="NULL", $id_pregunta="NULL")
    {
    	$form_hab = kolla_db::quote($formulario_habilitado_externo);
    	$elem = kolla_db::quote($elemento_externo);
    	$where_bloque = ($id_bloque != "NULL" )?' WHERE bloque='.$id_bloque:'';
        $sql = "SELECT * 
                FROM ws_resultados_de_encuesta_detalle($habilitacion, $form_hab, $elem, $id_pregunta) 
                resultados (
                            encuesta_definicion integer,  --0
                            bloque integer, --1
                            pregunta_id integer, --2
                            pregunta_texto character varying, --3
                            componente character varying, --4
                            es_libre text, --5
                            es_multiple text, --6
                            obligatoria char, --7
                            bloque_orden smallint, --8
                            pregunta_orden_bloque smallint, --9
                            orden_en_encuesta text, --10
                            respuesta_id integer, --11
                            respuesta_orden character varying, --12
                            respuesta_valor character varying, --13
                            elegida_cantidad bigint --14
                ) 
                $where_bloque
                ORDER BY bloque_orden, pregunta_orden_bloque, respuesta_id;";
        return kolla_db::consultar($sql);
    }
    
    function get_encuesta_elemento_respuestas_resumen($habilitacion, $formulario_habilitado_externo, $id_encuesta, $elemento_externo = null)
    {        
        //FROM ws_resultados_de_encuesta_resumen($habilitacion, $formulario_habilitado_externo, $elemento_externo) 
        $form_hab = kolla_db::quote($formulario_habilitado_externo);
        $elem = kolla_db::quote($elemento_externo);

        $sql = "SELECT * 
                FROM ws_resultados_de_encuesta_resumen($habilitacion, $form_hab, $id_encuesta, $elem) 
                resultados (
                            encuesta integer, --1
                            nombre character varying, --2
                            descripcion  character varying, --3

                            texto_preliminar text, --4
                            implementada char, --5
                            estado char, --6
                            unidad_gestion  character varying, --7

                            bloque integer, --8
                            bloque_nombre  character varying, --9
                            bloque_descripcion  character varying, --10
                            bloque_orden smallint, --11
                            
                            encuesta_definicion integer, 
                            pregunta_id integer, --12
                            pregunta_texto  character varying, --13
                            componente_numero integer, --14
                            tabla_asociada  character varying, --15
                            tabla_asociada_codigo  character varying, --16
                            tabla_asociada_descripcion  character varying, --17
                            tabla_asociada_orden_campo  character varying, --18
                            tabla_asociada_orden_tipo char(4), --19

                            es_libre text, --20
                            es_multiple text, --21

                            obligatoria char, --22
                            pregunta_orden smallint, --23

                            componente character varying, --24

                            opciones_respuesta_disponible bigint, --25
                            opciones_respuesta_elegidas bigint --26
                )
                ORDER BY bloque_orden, pregunta_orden;";
        toba::logger()->error($sql);
        return kolla_db::consultar($sql);
    }    
        
}
