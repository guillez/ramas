<?php

class co_preguntas 
{
    protected $preguntas = array();
    
    function get_pregunta($pregunta)
    {
        if ( isset($this->preguntas[$pregunta]) ) {
            return $this->preguntas[$pregunta];
        }
        
        $pregunta = kolla_db::quote($pregunta);
        
        $sql = "SELECT  *
				FROM 	sge_pregunta
				WHERE   pregunta = $pregunta";
        
		$res = kolla_db::consultar_fila($sql);
        
        if ( $res ) {
            $this->preguntas[$pregunta] = $res;
        }
        return $res;
    }
    
    function es_pregunta_receptora_cascada($pregunta)
    {
        $pregunta = kolla_db::quote($pregunta);
        
        $sql = "SELECT  COUNT(sge_pregunta_cascada.pregunta_disparadora) AS cant
                FROM    sge_pregunta_cascada
                WHERE   sge_pregunta_cascada.pregunta_receptora = $pregunta";
        
        $res = kolla_db::consultar_fila($sql);
        
        if ($res['cant'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    function es_pregunta_dependiente_encuesta_definicion($encuesta_definicion)
    {
        $encuesta_definicion = kolla_db::quote($encuesta_definicion);
        
        $sql = "SELECT  COUNT(sge_encuesta_definicion.encuesta_definicion) AS cant
                FROM    sge_encuesta_definicion
                WHERE   sge_encuesta_definicion.encuesta_definicion = $encuesta_definicion
                AND     sge_encuesta_definicion.pregunta IN
                (
                    SELECT	sge_pregunta_cascada.pregunta_receptora
                    FROM	sge_pregunta_cascada
                )";
        
        $res = kolla_db::consultar_fila($sql);
        
        if ($res['cant'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    function get_pregunta_dependiente_calculo_anios($pregunta)
    {
        $pregunta = kolla_db::quote($pregunta);
        
        $sql = "SELECT  sge_pregunta.*
				FROM 	sge_pregunta,
                        sge_pregunta_cascada
				WHERE   sge_pregunta_cascada.pregunta_disparadora = $pregunta
                AND     sge_pregunta_cascada.pregunta_receptora = sge_pregunta.pregunta";
        
		return kolla_db::consultar_fila($sql);
    }
    
    function get_pregunta_dependiente_encuesta($encuesta_definicion)
    {
        $encuesta_definicion = kolla_db::quote($encuesta_definicion);

        $sql = "SELECT  receptora.*
                FROM 	sge_encuesta_definicion receptora
                JOIN (	
                    SELECT  sge_encuesta_definicion.encuesta_definicion as definicion_disparadora, 
                            sge_encuesta_definicion.encuesta, 
                            sge_encuesta_definicion.bloque, 
                            sge_encuesta_definicion.pregunta id_disparadora, 
                            sge_encuesta_definicion.orden orden_disparadora,
                            sge_pregunta_cascada.pregunta_disparadora, 
                            sge_pregunta_cascada.pregunta_receptora
                    FROM  sge_pregunta_cascada 
                        INNER JOIN sge_encuesta_definicion ON sge_pregunta_cascada.pregunta_disparadora = sge_encuesta_definicion.pregunta
                    WHERE sge_encuesta_definicion.encuesta_definicion = $encuesta_definicion
                ) as disparadora ON
                    (receptora.pregunta = disparadora.pregunta_receptora 
                    AND receptora.bloque = disparadora.bloque 
                    AND (receptora.orden = disparadora.orden_disparadora +1))";
		return kolla_db::consultar_fila($sql);
    }
    
}
