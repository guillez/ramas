<?php

class co_preguntas_dependientes 
{
    function get_dependencias_definicion($pregunta_dependencia)
    {
        $pregunta_dependencia = kolla_db::quote($pregunta_dependencia);
        
        //SE SEPARA LA CONSULTA EN DOS PARTES PORQUE EL FILTRO DE PERFIL DE DATOS 
        //NO PUEDE MANEJAR CONSULTAS CON TABLAS REPETIDAS
        
        //SQL PARA PARTE 1 (PREGUNTA CON DEPENDIENTES)
        //SE AGREGA EL UNION PARA QUE LAS DEPENDENCIAS SE APLIQUEN TAMBIÉN A PREGUNTAS EN CASCADA
        $sql_1 = "  SELECT  dep.pregunta_dependencia,
                            def.dependencia_definicion AS dependencia_definicion, 
                            enc.encuesta, 
                            enc.bloque, 
                            enc.pregunta, 
                            def.condicion, 
                            def.valor, 
                            def.accion, 
                            def.encuesta_definicion AS encuesta_definicion_accion,
                            def.bloque AS bloque_accion, 
                            def.pregunta AS pregunta_accion
                    FROM    sge_pregunta_dependencia_definicion AS def
                                JOIN sge_pregunta_dependencia AS dep ON (def.pregunta_dependencia = dep.pregunta_dependencia AND dep.pregunta_dependencia = $pregunta_dependencia)
                                JOIN sge_encuesta_definicion AS enc ON (dep.encuesta_definicion = enc.encuesta_definicion)
                                JOIN sge_bloque ON (enc.bloque = sge_bloque.bloque)
                                JOIN sge_pregunta ON (enc.pregunta = sge_pregunta.pregunta)
                    WHERE   def.pregunta IS NOT NULL           
                    UNION    
                    SELECT  dep.pregunta_dependencia,
                                def.dependencia_definicion AS dependencia_definicion, 
                                enc.encuesta, 
                                enc.bloque, 
                                enc.pregunta, 
                                def.condicion, 
                                def.valor, 
                                def.accion, 
                                def.encuesta_definicion AS encuesta_definicion_accion,
                                def.bloque AS bloque_accion, 
                                spc.pregunta_receptora as pregunta_accion
                        FROM    sge_pregunta_dependencia_definicion AS def
                                    JOIN sge_pregunta_dependencia AS dep ON (def.pregunta_dependencia = dep.pregunta_dependencia AND dep.pregunta_dependencia = $pregunta_dependencia)
                                    JOIN sge_encuesta_definicion AS enc ON (dep.encuesta_definicion = enc.encuesta_definicion)
                                    JOIN sge_bloque ON (enc.bloque = sge_bloque.bloque)
                                    JOIN sge_pregunta ON (enc.pregunta = sge_pregunta.pregunta)
                                    INNER join sge_pregunta_cascada spc on (spc.pregunta_disparadora = def.pregunta)
                        WHERE   def.pregunta IS NOT NULL                    
                    ORDER BY pregunta_dependencia, dependencia_definicion;";
        
        $res_sql_1 = kolla_db::consultar($sql_1);
        
        //SQL PARA PARTE 2 (PREGUNTAS DEPENDIENTES - ACCIONES)
        //SE AGREGA EL UNION PARA QUE LAS DEPENDENCIAS SE APLIQUEN TAMBIÉN A PREGUNTAS EN CASCADA
        $sql_2 = "  SELECT  dep.pregunta_dependencia, 
                            def.dependencia_definicion AS dependencia_definicion, 
                            def.condicion, 
                            def.valor, 
                            def.accion, 
                            def.encuesta_definicion AS encuesta_definicion_accion, 
                            def.bloque AS bloque_accion, 
                            def.pregunta AS pregunta_accion,
                            CASE WHEN enc_accion.obligatoria = 'S' THEN TRUE ELSE FALSE END AS obligatoria, 
                            sge_componente_pregunta.componente AS componente
                    FROM    sge_pregunta_dependencia_definicion AS def
                                JOIN sge_pregunta_dependencia AS dep ON (def.pregunta_dependencia = dep.pregunta_dependencia AND dep.pregunta_dependencia = $pregunta_dependencia)
                                JOIN sge_encuesta_definicion AS enc_accion ON (def.encuesta_definicion = enc_accion.encuesta_definicion)
                                JOIN sge_pregunta AS def_accion ON (def.pregunta = def_accion.pregunta)
                                JOIN sge_componente_pregunta ON (def_accion.componente_numero = sge_componente_pregunta.numero)
                    UNION
                    SELECT dep.pregunta_dependencia, 
                            def.dependencia_definicion AS dependencia_definicion, 
                            def.condicion, 
                            def.valor, 
                            def.accion, 
                            dependencia_calculada.encuesta_definicion AS encuesta_definicion_accion, 
                            def.bloque AS bloque_accion, 
                            spc.pregunta_receptora as pregunta_accion,
                            CASE WHEN dependencia_calculada.obligatoria = 'S' THEN TRUE ELSE FALSE END AS obligatoria, 
                            sge_componente_pregunta.componente AS componente
                    FROM    sge_pregunta_dependencia_definicion AS def
                            inner join sge_pregunta_cascada spc on (spc.pregunta_disparadora = def.pregunta)
                            JOIN sge_pregunta_dependencia AS dep ON (def.pregunta_dependencia = dep.pregunta_dependencia AND dep.pregunta_dependencia = $pregunta_dependencia)
                            JOIN sge_encuesta_definicion AS dependencia_calculada ON (spc.pregunta_receptora = dependencia_calculada.pregunta 
                                                AND def.bloque = dependencia_calculada.bloque 
                                                AND dependencia_calculada.orden = (SELECT orden FROM sge_encuesta_definicion dependencia_cargada WHERE dependencia_cargada.encuesta_definicion = def.encuesta_definicion)+1
                                                )
                            JOIN sge_pregunta AS def_accion ON (spc.pregunta_receptora = def_accion.pregunta)
                            JOIN sge_componente_pregunta ON (def_accion.componente_numero = sge_componente_pregunta.numero)
                    ORDER BY pregunta_dependencia, dependencia_definicion;";
        
        $res_sql_2 = kolla_db::consultar($sql_2);
        
        //SE REUNEN LOS RESULTADOS DE CADA CONSULTA POR REGISTRO
        $resultados = array();

        if (count($res_sql_2) > 0) {
            $cant_deps = count($res_sql_1);
            for ($i = 0; $i < $cant_deps; $i++) {
                array_push($resultados, array_merge($res_sql_1[$i], $res_sql_2[$i]));
            }
        }

        //POR ULTIMO SE UNE CON LO QUE ERA LA SEGUNDA PARTE DE LA CONSULTA ORIGINAL
        //SQL PARA PARTE 3 (PREGUNTAS BLOQUES DEPENDIENTES)
        $sql_3 = "  SELECT  dep.pregunta_dependencia, 
                            def.dependencia_definicion AS dependencia_definicion, 
                            enc.encuesta, 
                            enc.bloque, 
                            enc.pregunta, 
                            def.condicion, 
                            def.valor, 
                            def.accion, 
                            NULL AS encuesta_definicion_accion, 
                            def.bloque AS bloque_accion, 
                            NULL AS pregunta_accion, 
                            FALSE AS obligatoria, 
                            NULL AS componente
                    FROM    sge_pregunta_dependencia_definicion AS def
                                JOIN sge_pregunta_dependencia AS dep ON (def.pregunta_dependencia = dep.pregunta_dependencia AND dep.pregunta_dependencia = $pregunta_dependencia)
                                JOIN sge_encuesta_definicion AS enc ON (dep.encuesta_definicion = enc.encuesta_definicion)
                    WHERE   def.pregunta ISNULL
                    ORDER BY pregunta_dependencia, dependencia_definicion;";
        
        $res_sql_3 = kolla_db::consultar($sql_3);
        
        foreach ($res_sql_3 as $r) {
            array_push($resultados, $r);
        }
        
        //se explotan las dependencias sobre bloques para obtener una acción sobre cada pregunta del bloque
        $sql_4 = "  SELECT  dep.pregunta_dependencia, 
                            def.dependencia_definicion AS dependencia_definicion, 
                            enc.encuesta, 
                            enc.bloque, 
                            enc.pregunta, 
                            def.condicion, 
                            def.valor, 
                            def.accion, 
                            enc.encuesta_definicion AS encuesta_definicion_accion,
                            def.bloque AS bloque_accion, 
                            enc.pregunta AS pregunta_accion,
                            CASE WHEN enc.obligatoria = 'S' THEN TRUE ELSE FALSE END AS obligatoria,
                            scp.componente AS componente
                    FROM    sge_pregunta_dependencia_definicion AS def
                                JOIN sge_pregunta_dependencia AS dep ON (def.pregunta_dependencia = dep.pregunta_dependencia AND dep.pregunta_dependencia =  $pregunta_dependencia)
                                JOIN sge_encuesta_definicion AS enc ON (def.bloque = enc.bloque)
                                JOIN sge_bloque sb ON (enc.bloque = sb.bloque)
                                JOIN sge_pregunta sp ON (enc.pregunta = sp.pregunta)
                                JOIN sge_componente_pregunta scp ON (sp.componente_numero = scp.numero)
                    WHERE   def.pregunta ISNULL
                    ORDER BY pregunta_dependencia, dependencia_definicion;";
        
        $res_sql_4 = kolla_db::consultar($sql_4);
        
        foreach ($res_sql_4 as $r) {
            array_push($resultados, $r);
        }
        return $resultados;
    }
    
    function get_dependencias($encuesta)
    {
        $encuesta = kolla_db::quote($encuesta);
        
        $sql = "SELECT  dep.pregunta_dependencia,
                        dep.encuesta_definicion,
                        enc.encuesta,
                        enc.bloque,
                        enc.pregunta,
                        sge_componente_pregunta.componente,
                        sge_bloque.nombre AS nombre_bloque,
                        sge_pregunta.nombre AS nombre_pregunta
                FROM    sge_pregunta_dependencia AS dep
                        JOIN sge_encuesta_definicion AS enc ON (dep.encuesta_definicion = enc.encuesta_definicion)
                        JOIN sge_bloque ON (enc.bloque = sge_bloque.bloque)
                        JOIN sge_pregunta ON (enc.pregunta = sge_pregunta.pregunta)
                        JOIN sge_componente_pregunta ON (sge_pregunta.componente_numero = sge_componente_pregunta.numero)
                WHERE   encuesta = $encuesta
        ";

        return kolla_db::consultar($sql);
    }
    
    function get_pregunta($componente)
    {
        switch ($componente) {
            case 'combo':
            case 'localidad':
            case 'localidad_y_cp':
            case 'combo_dinamico':
            case 'combo_autocompletado':
                $pregunta = new pregunta_opciones();
                break;
            case 'radio':
                $pregunta = new pregunta_radio();
                break;
            case 'list':
                $pregunta = new pregunta_opciones_lista();
                break;
            case 'texto_numeroentero':
            case 'texto_numerodecimal':
            case 'texto_numeroedad':
            case 'texto_numeroanio':
                $pregunta = new pregunta_numero();
                break;
            case 'check' :
                $pregunta = new pregunta_booleano();
                break;
            case 'texto_fecha':
            case 'fecha_calculo_anios':
                $pregunta = new pregunta_fecha();
                break;
            case 'texto':
            case 'textarea':
                $pregunta = new pregunta_cadena();
                break;
        }
        
        return $pregunta;
    }
    
    function get_condiciones($componente)
    {
        $pregunta = $this->get_pregunta($componente);
        return $pregunta->get_condiciones_combo();
    }
    
    function get_acciones()
    {
        return array(
            array(
                'accion'    => 'mostrar',
                'etiqueta'  => 'mostrar'
            ),
            array(
                'accion'    => 'ocultar',
                'etiqueta'  => 'ocultar'
            ),
            array(
                'accion'    => 'habilitar',
                'etiqueta'  => 'habilitar'
            ),
            array(
                'accion'    => 'deshabilitar',
                'etiqueta'  => 'deshabilitar'
            ),
        );
    }
    
    function get_bloques_dependientes($encuesta, $orden)
    {
        $encuesta = kolla_db::quote($encuesta);
        $orden    = kolla_db::quote($orden);
        
        $sql = "SELECT      sge_bloque.*
                FROM        sge_bloque
                            JOIN sge_encuesta_definicion ON (sge_encuesta_definicion.bloque = sge_bloque.bloque AND
                                                             sge_encuesta_definicion.encuesta = $encuesta)
                WHERE       sge_bloque.orden >= $orden
                ORDER BY    sge_bloque.orden ASC";
        
        return kolla_db::consultar($sql);
    }
    
    /*
     * Dado el id de encuesta definicion retorna true en caso de que la pregunta haya sido
     * definida desde la operación de Preguntas Dependientes, y false en caso contrario.
     * Es decir, que retornará verdadero tanto si corresponde a la pregunta disparadora asi
     * como también si se corresponde con alguna de todas las dependencias que la misma
     * pueda tener definidas.
     */
    function es_pregunta_dependiente($encuesta_definicion)
    {
        $encuesta_definicion = kolla_db::quote($encuesta_definicion);
        
        $sql_pregunta = "   SELECT  COUNT(sge_pregunta_dependencia_definicion.dependencia_definicion) AS cant
                            FROM    sge_pregunta_dependencia_definicion
                            WHERE   sge_pregunta_dependencia_definicion.encuesta_definicion = $encuesta_definicion";
        
        $pregunta = kolla_db::consultar_fila($sql_pregunta);
        
        if ($pregunta['cant'] > 0) {
            return true;
        }
        
        $sql_dependencias = "   SELECT  COUNT(sge_pregunta_dependencia.pregunta_dependencia) AS cant
                                FROM    sge_pregunta_dependencia
                                WHERE   sge_pregunta_dependencia.encuesta_definicion = $encuesta_definicion";
        
        $dependencias = kolla_db::consultar_fila($sql_dependencias);
        
        if ($dependencias['cant'] > 0) {
            return true;
        }
        
        return false;
    }
    
}
