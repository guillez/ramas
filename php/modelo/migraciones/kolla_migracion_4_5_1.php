<?php

class kolla_migracion_4_5_1 extends kolla_migracion
{
    function negocio__34303()
    {
        $sql = "SELECT      sge_respuesta.respuesta,
                            sge_respuesta.valor_tabulado
				FROM        sge_respuesta
                WHERE       sge_respuesta.respuesta BETWEEN 3078 AND 3082
                ORDER BY    sge_respuesta.respuesta";

        $respuestas = $this->get_db()->consultar($sql);
        
        if (!empty($respuestas)) {
            $id_3078_preinscripcion = $respuestas[0]['valor_tabulado'] == 'Condición Psicosocial';
            $id_3079_preinscripcion = $respuestas[1]['valor_tabulado'] == 'Sí, mucha dificultad';
            $id_3080_preinscripcion = $respuestas[2]['valor_tabulado'] == 'No puedo hacerlo en absoluto';
            $id_3081_preinscripcion = $respuestas[3]['valor_tabulado'] == 'Auditiva';
            $id_3082_preinscripcion = $respuestas[4]['valor_tabulado'] == 'No';
            
            if ($id_3078_preinscripcion && $id_3079_preinscripcion && $id_3080_preinscripcion && $id_3081_preinscripcion && $id_3082_preinscripcion) {
                
                //Se difieren las constraints
                $this->set_constraints_deferred();
                
                //Se eliminan estos 5 registros porque originalmente pertenecian a una encuesta precargada: rango [3078, 3082]
                $this->delete_registros_preinscripcion();
                
                //Se insertan nuevamente a continuación de los ultimos insertados: estos es el rango [3094, 3098]
                $this->insert_registros_preinscripcion();
                
                //Se modifican los IDs en los registros que los apuntaban: tablas sge_pregunta_respuesta y sge_pregunta_dependencia_definicion
                $this->update_ids_preinscripcion();
                
                //Se setean las constraints como inmediatas
                $this->set_constraints_immediate();
            }
        }
    }
    
    function delete_registros_preinscripcion()
    {
        $sql = "DELETE FROM sge_respuesta
                WHERE       sge_respuesta.respuesta BETWEEN 3078 AND 3082";
        
        $this->get_db()->ejecutar($sql);
    }
    
    function insert_registros_preinscripcion()
    {
        $sql = "INSERT INTO sge_respuesta (valor_tabulado, respuesta, unidad_gestion) VALUES ('Condición Psicosocial', '3094', 0);
                INSERT INTO sge_respuesta (valor_tabulado, respuesta, unidad_gestion) VALUES ('Sí, mucha dificultad', '3095', 0);
                INSERT INTO sge_respuesta (valor_tabulado, respuesta, unidad_gestion) VALUES ('No puedo hacerlo en absoluto', '3096', 0);
                INSERT INTO sge_respuesta (valor_tabulado, respuesta, unidad_gestion) VALUES ('Auditiva', '3097', 0);
                INSERT INTO sge_respuesta (valor_tabulado, respuesta, unidad_gestion) VALUES ('No', '3098', 0);";
        
        $this->get_db()->ejecutar($sql);
    }
    
    function update_ids_preinscripcion()
    {
        $ids_viejos = array('3078', '3079', '3080', '3081', '3082');
        $ids_nuevos = array('3094', '3095', '3096', '3097', '3098');
        
        foreach ($ids_viejos as $clave => $valor) {
            
            $sql = "UPDATE  sge_pregunta_respuesta
                    SET     respuesta = ".$ids_nuevos[$clave]."
                    WHERE   respuesta = $valor;
                    ";
            
            $this->get_db()->ejecutar($sql);
            
            $sql = "UPDATE  sge_pregunta_dependencia_definicion
                    SET     valor = '".$ids_nuevos[$clave]."'
                    WHERE   valor = '$valor'
                    AND     pregunta_dependencia IN (
                                SELECT  sge_pregunta_dependencia.pregunta_dependencia
                                FROM    sge_pregunta_dependencia,
                                        sge_encuesta_definicion
                                WHERE   sge_pregunta_dependencia.encuesta_definicion = sge_encuesta_definicion.encuesta_definicion
                                AND     sge_encuesta_definicion.encuesta = 12
                                );
                    ";
            
            $this->get_db()->ejecutar($sql);
        }
    }
    
    function set_constraints_deferred()
    {
        $sql = "SET CONSTRAINTS ALL DEFERRED;";
        $this->get_db()->ejecutar($sql);
    }
    
    function set_constraints_immediate()
    {
        $sql = "SET CONSTRAINTS ALL IMMEDIATE;";
        $this->get_db()->ejecutar($sql);
    }
    
}
