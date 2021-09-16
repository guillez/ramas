<?php

class kolla_migracion_3_6_1 extends kolla_migracion
{   
    function negocio__466()
    {
        $sql = "ALTER TABLE sge_pregunta_dependencia
                ADD FOREIGN KEY (encuesta_definicion) REFERENCES sge_encuesta_definicion (encuesta_definicion) ON UPDATE NO ACTION ON DELETE CASCADE;
                
                ALTER TABLE sge_pregunta_dependencia_definicion
                DROP CONSTRAINT IF EXISTS fk_sge_pregunta_dependencia_definicion_sge_encuesta_definicion;

                ALTER TABLE sge_pregunta_dependencia_definicion
                ADD FOREIGN KEY (encuesta_definicion) REFERENCES sge_encuesta_definicion (encuesta_definicion) ON UPDATE NO ACTION ON DELETE CASCADE;
                
                ALTER TABLE sge_pregunta_dependencia_definicion
                DROP CONSTRAINT IF EXISTS fk_sge_pregunta_dependencia_definicion_sge_pregunta_dependencia;
                
                ALTER TABLE sge_pregunta_dependencia_definicion
                ADD FOREIGN KEY (pregunta_dependencia) REFERENCES sge_pregunta_dependencia (pregunta_dependencia) ON UPDATE NO ACTION ON DELETE CASCADE;
            ";
        
        $this->get_db()->ejecutar($sql);
    }
    
    
    /**
     * Bugfix que duplica datos en la tabla sge_log_formulario_definicion_habilitacion
     */
    function negocio__465()
    {
        $sql = "CREATE TABLE sge_log_formulario_definicion_habilitacion_new
                (
                  habilitacion integer NOT NULL,
                  encuesta integer NOT NULL,
                  tipo_elemento integer,
                  orden smallint NOT NULL,
                  CONSTRAINT pk_sge_log_formulario_definicion_habilitacion PRIMARY KEY (habilitacion, encuesta, orden),
                  CONSTRAINT fk_sge_log_formulario_definicion_habilitacion_sge_encuesta_atri FOREIGN KEY (encuesta)
                      REFERENCES sge_encuesta_atributo (encuesta) MATCH SIMPLE
                      ON UPDATE NO ACTION ON DELETE NO ACTION,
                  CONSTRAINT fk_sge_log_formulario_definicion_habilitacion_sge_habilitacion FOREIGN KEY (habilitacion)
                      REFERENCES sge_habilitacion (habilitacion) MATCH SIMPLE
                      ON UPDATE NO ACTION ON DELETE NO ACTION,
                  CONSTRAINT fk_sge_log_formulario_definicion_habilitacion_sge_tipo_elemento FOREIGN KEY (tipo_elemento)
                      REFERENCES sge_tipo_elemento (tipo_elemento) MATCH SIMPLE
                      ON UPDATE NO ACTION ON DELETE NO ACTION
                );

                INSERT INTO sge_log_formulario_definicion_habilitacion_new 
                SELECT DISTINCT habilitacion, encuesta, tipo_elemento, orden
                  FROM sge_log_formulario_definicion_habilitacion;

                DROP TABLE sge_log_formulario_definicion_habilitacion;

                ALTER TABLE sge_log_formulario_definicion_habilitacion_new
                  RENAME TO sge_log_formulario_definicion_habilitacion;
                ";
        
        $this->get_db()->ejecutar($sql);
    }
    
    function negocio__467 () 
    {
        //cubre tickets 468, 469, 470 Y 495 también
        $dir = $this->get_dir_ddl();
        
        $archivos = array(
            $dir.'80_Procesos/100_preguntas_habilitacion.sql',
            $dir.'80_Procesos/110_respuestas_completas_habilitacion.sql',
            $dir.'80_Procesos/120_respuestas_completas_habilitacion_conteo.sql',
            $dir.'80_Procesos/130_sp_upser_tipo_elemento.sql',
            $dir.'80_Procesos/40_preguntas_formulario_habilitado.sql',
            $dir.'80_Procesos/50_respuestas_completas_formulario_habilitado.sql'
        );
        
        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);    
        }
    }
    
    /* Se debe definir un valor para los registros que tengan formulario_habilitado_externo en null 
     * y correspondan a formularios externos.
     */
    function negocio__474() 
    {
        $sql_existe = "SELECT EXISTS (
                        SELECT  1 
                        FROM    information_schema.columns 
                        WHERE   table_schema = 'kolla' 
                        AND     table_name   = 'sge_formulario_habilitado' 
                        AND     column_name  = 'formulario_habilitado_externo'
                        ) AS existe;
                    ";
        
        $existe = $this->get_db()->consultar_fila($sql_existe);
        
        if ($existe['existe'] != 't') {
            $sql_alter = "ALTER TABLE sge_formulario_habilitado ADD COLUMN formulario_habilitado_externo character varying(100);";
            $this->get_db()->ejecutar($sql_alter);
        }
        
        $sql = "UPDATE sge_formulario_habilitado sfh
                SET formulario_habilitado_externo = valores.fhe_concepto
                FROM (SELECT h.habilitacion, fh.formulario_habilitado, fh.concepto, c.concepto_externo AS fhe_concepto
                    FROM sge_habilitacion h 
                        INNER JOIN sge_formulario_habilitado fh ON (h.habilitacion = fh.habilitacion)
                        INNER JOIN sge_concepto c ON (fh.concepto = c.concepto)
                    WHERE fh.formulario_habilitado_externo IS NULL
                    ) as valores
                WHERE sfh.formulario_habilitado = valores.formulario_habilitado";
        
        $this->get_db()->ejecutar($sql);
    }
    
    /**
     * Modificación en la tabla para los Componentes de Preguntas
     */
    function negocio__475()
    {
        $sql = "ALTER TABLE sge_componente_pregunta ADD COLUMN tipo Char(1) NOT NULL DEFAULT 'A';
                ALTER TABLE sge_componente_pregunta ADD CONSTRAINT ck_sge_componente_pregunta_tipo CHECK (tipo IN ('A', 'C', 'E'));
                UPDATE sge_componente_pregunta SET tipo = 'C' WHERE numero = 2;
                UPDATE sge_componente_pregunta SET tipo = 'C' WHERE numero = 3;
                UPDATE sge_componente_pregunta SET tipo = 'C' WHERE numero = 4;
                UPDATE sge_componente_pregunta SET tipo = 'C' WHERE numero = 5;
                UPDATE sge_componente_pregunta SET tipo = 'E' WHERE numero = 7;
                UPDATE sge_componente_pregunta SET tipo = 'E' WHERE numero = 9;
                ";
        
        $this->get_db()->ejecutar($sql);
    }
    
    /**
     * Código incorrecto en respuestas registradas migradas
     */
    function negocio__477()
    {
        //determinar si es una base migrada por un instalador
        $sql = "SELECT EXISTS( SELECT  *
                FROM information_schema.schemata
                WHERE schema_name IN (  'toba_kolla_3.0.0', 'toba_kolla_3.1.0', 
                                        'toba_kolla_3.1.2', 'toba_kolla_3.3.0', 'toba_kolla_3.4.0')
                            ) AS existe;";
        $rs = $this->get_db()->consultar_fila($sql);
        $migrada = ($rs['existe'] == 't');
        
        //determinar si hay tablas asociadas con códigos que no se corresponden con las respuestas
        $sql = "SELECT DISTINCT sp.tabla_asociada AS tabla, sp.tabla_asociada_codigo AS codigo
                FROM kolla.sge_pregunta sp 
                    INNER JOIN kolla.sge_encuesta_definicion sed ON (sed.pregunta = sp.pregunta)
                    INNER JOIN kolla.sge_respondido_detalle srd ON (srd.encuesta_definicion = sed.encuesta_definicion)
                WHERE sp.tabla_asociada <> '';";
        $rs = $this->get_db()->consultar($sql);
        
        $codigo_sin_rta = false;
        foreach ($rs as $tabla) {
            $nombre = $this->get_db()->quote($tabla['tabla']);        
            
            $sql = "SELECT  srd.respuesta_codigo,
                            tabla.".$tabla['codigo'].",
                            srd.respondido_detalle
                    FROM    sge_pregunta sp 
                                INNER JOIN sge_encuesta_definicion sed  ON (sed.pregunta = sp.pregunta)
                                INNER JOIN sge_respondido_detalle srd   ON (srd.encuesta_definicion = sed.encuesta_definicion)
                                LEFT JOIN ".$tabla['tabla']." tabla     ON (tabla.".$tabla['codigo']."::character varying = srd.respuesta_codigo::character varying)
                    WHERE   sp.tabla_asociada = $nombre
                    AND     tabla.".$tabla['codigo']." IS NULL
                    AND     srd.respuesta_codigo > 100000;";
            
            $rs2 = $this->get_db()->consultar_fila($sql);
            if (!$codigo_sin_rta && isset($rs2['respondido_detalle'])) {
                $codigo_sin_rta = true;
            }
        }

        if ($migrada || $codigo_sin_rta) {
            //Si son preguntas con tabla asociada y las respuestas REGISTRADAS tienen códigos mayores a 100.000
            //se debe corregir el valor del código
            $sql = "UPDATE kolla.sge_respondido_detalle srd
                    SET respuesta_codigo = respuesta_codigo - 100000
                    FROM kolla.sge_encuesta_definicion ed, 
                        kolla.sge_pregunta sp 
                    WHERE ed.encuesta_definicion = srd.encuesta_definicion
                        AND srd.respuesta_codigo >= 100000
                        AND ed.pregunta = sp.pregunta
                        AND (sp.tabla_asociada <> '' AND sp.tabla_asociada IS NOT NULL);
                    ";
            $this->get_db()->ejecutar($sql);
        }        
    }
    
    function negocio__479()
    {
        //Se agrega el nuevo campo a la tabla, se carga automáticamente la descripción resumida
        //para las preguntas existentes y luego se agrega la condición de NOT NULL
        
        $sql = "ALTER TABLE sge_pregunta
                ADD COLUMN descripcion_resumida character varying (30);

                UPDATE sge_pregunta
                SET descripcion_resumida = substring(nombre from 1 for 30)
                WHERE descripcion_resumida IS NULL;

                ALTER TABLE sge_pregunta
                ALTER COLUMN descripcion_resumida SET NOT NULL;
            ";
        
        $this->get_db()->ejecutar($sql);
        
        //Se setea la descripción resumida de las preguntas pre-cargadas de Kolla
        $sql = "UPDATE sge_pregunta SET descripcion_resumida = 'Expectativa laboral en 6 meses' WHERE pregunta = 24;
            UPDATE sge_pregunta SET descripcion_resumida = 'Qué valoran los empleadores' WHERE pregunta = 25;
            UPDATE sge_pregunta SET descripcion_resumida = 'Otras capacidades' WHERE pregunta = 26;
            UPDATE sge_pregunta SET descripcion_resumida = 'Posibles fuentes de trabajo' WHERE pregunta = 27;
            UPDATE sge_pregunta SET descripcion_resumida = 'Posibles fuentes de trabajo' WHERE pregunta = 28;
            UPDATE sge_pregunta SET descripcion_resumida = 'Desea info de bolsa de trabajo' WHERE pregunta = 29;
            UPDATE sge_pregunta SET descripcion_resumida = 'Horas semanales de trabajo' WHERE pregunta = 34;
            UPDATE sge_pregunta SET descripcion_resumida = 'Por haberse recibido' WHERE pregunta = 37;
            UPDATE sge_pregunta SET descripcion_resumida = 'Exigencias lugar de trabajo' WHERE pregunta = 41;
            UPDATE sge_pregunta SET descripcion_resumida = 'Motivos para elegir su carrera' WHERE pregunta = 42;
            UPDATE sge_pregunta SET descripcion_resumida = 'Por qué eligió esta Univ.' WHERE pregunta = 43;
            UPDATE sge_pregunta SET descripcion_resumida = '¿Contenidos interesantes?' WHERE pregunta = 44;
            UPDATE sge_pregunta SET descripcion_resumida = 'Contenidos satisfactorios' WHERE pregunta = 45;
            UPDATE sge_pregunta SET descripcion_resumida = 'Nivel de exigencia de carrera' WHERE pregunta = 46;
            UPDATE sge_pregunta SET descripcion_resumida = 'Plan: debe ser más corto' WHERE pregunta = 47;
            UPDATE sge_pregunta SET descripcion_resumida = 'Plan: debe ser más largo' WHERE pregunta = 48;
            UPDATE sge_pregunta SET descripcion_resumida = 'Plan: debe ser más técnico' WHERE pregunta = 49;
            UPDATE sge_pregunta SET descripcion_resumida = 'Plan: debe ser más generalista' WHERE pregunta = 50;
            UPDATE sge_pregunta SET descripcion_resumida = 'Volvería a Universidad Pública' WHERE pregunta = 58;
            UPDATE sge_pregunta SET descripcion_resumida = 'Lo mas apreciado de su carrera' WHERE pregunta = 59;
            UPDATE sge_pregunta SET descripcion_resumida = 'Reconocimiento a la Univ.' WHERE pregunta = 60;
            UPDATE sge_pregunta SET descripcion_resumida = 'Qué desea de esta Universidad' WHERE pregunta = 61;
            UPDATE sge_pregunta SET descripcion_resumida = '¿Otros estudios superiores?' WHERE pregunta = 62;
            UPDATE sge_pregunta SET descripcion_resumida = '¿Conocimientos de informática?' WHERE pregunta = 71;
            UPDATE sge_pregunta SET descripcion_resumida = '¿Qué seguiría estudiando?' WHERE pregunta = 73;
            UPDATE sge_pregunta SET descripcion_resumida = 'Por qué estudiará en otro lado' WHERE pregunta = 75;
            UPDATE sge_pregunta SET descripcion_resumida = 'Le interesa formación contínua' WHERE pregunta = 76;
            UPDATE sge_pregunta SET descripcion_resumida = '¿Dispuesto a otra encuesta?' WHERE pregunta = 77;
            UPDATE sge_pregunta SET descripcion_resumida = '¿Trabaja/ó en su profesión?' WHERE pregunta = 209;
            UPDATE sge_pregunta SET descripcion_resumida = 'No:pasa a otro bloque' WHERE pregunta = 210;
            UPDATE sge_pregunta SET descripcion_resumida = 'Demora a trabajo relacionado' WHERE pregunta = 211;
            UPDATE sge_pregunta SET descripcion_resumida = 'Qué valoran empleadores' WHERE pregunta = 212;
            UPDATE sge_pregunta SET descripcion_resumida = 'Primer trabajo profesional' WHERE pregunta = 213;
            UPDATE sge_pregunta SET descripcion_resumida = 'Cómo logro iniciarse independ.' WHERE pregunta = 214;
            UPDATE sge_pregunta SET descripcion_resumida = 'Cómo obtuvo empleo dependencia' WHERE pregunta = 215;
            UPDATE sge_pregunta SET descripcion_resumida = '¿Adaptación rápida en trabajo?' WHERE pregunta = 217;
            UPDATE sge_pregunta SET descripcion_resumida = 'Habilidades: ¿las aplicó?' WHERE pregunta = 218;
            UPDATE sge_pregunta SET descripcion_resumida = 'Habilidades: ¿suficientes?' WHERE pregunta = 219;
            UPDATE sge_pregunta SET descripcion_resumida = 'Conocimientos: ¿los aplicó?' WHERE pregunta = 220;
            UPDATE sge_pregunta SET descripcion_resumida = 'Conocimientos: ¿suficientes?' WHERE pregunta = 221;
            UPDATE sge_pregunta SET descripcion_resumida = '¿Desarrolló proyectos prof.?' WHERE pregunta = 222;
            UPDATE sge_pregunta SET descripcion_resumida = 'Oportunidades recién egresados' WHERE pregunta = 223;
            UPDATE sge_pregunta SET descripcion_resumida = 'Cuántas ocupaciones tiene' WHERE pregunta = 225;
            UPDATE sge_pregunta SET descripcion_resumida = 'Relación ocupación/profesión' WHERE pregunta = 227;
            UPDATE sge_pregunta SET descripcion_resumida = 'Actividad que realiza' WHERE pregunta = 228;
            UPDATE sge_pregunta SET descripcion_resumida = 'Cantidad de personas de la org' WHERE pregunta = 231;
            UPDATE sge_pregunta SET descripcion_resumida = 'Nivel ingreso promedio mensual' WHERE pregunta = 234;
            UPDATE sge_pregunta SET descripcion_resumida = 'En que localidad trabaja (ARG)' WHERE pregunta = 237;
            UPDATE sge_pregunta SET descripcion_resumida = 'Dónde trabaja (exterior)' WHERE pregunta = 238;
            UPDATE sge_pregunta SET descripcion_resumida = 'Por qué no consigue trabajo' WHERE pregunta = 242;
            UPDATE sge_pregunta SET descripcion_resumida = 'Otros estudios superiores' WHERE pregunta = 243;
            UPDATE sge_pregunta SET descripcion_resumida = 'Realiza estudios de posgrado' WHERE pregunta = 247;
            UPDATE sge_pregunta SET descripcion_resumida = 'Interes en estudio de posgrado' WHERE pregunta = 248;
            UPDATE sge_pregunta SET descripcion_resumida = 'Si: continúe con las preguntas' WHERE pregunta = 249;
            UPDATE sge_pregunta SET descripcion_resumida = 'El posgrado está acreditado' WHERE pregunta = 254;
            UPDATE sge_pregunta SET descripcion_resumida = 'Formación prof. post-egreso' WHERE pregunta = 256;
            UPDATE sge_pregunta SET descripcion_resumida = 'Fortalecer la formación en:' WHERE pregunta = 259;
            UPDATE sge_pregunta SET descripcion_resumida = 'Herramientas informáticas' WHERE pregunta = 262;
            UPDATE sge_pregunta SET descripcion_resumida = 'Fin bloque Rel. Dependencia' WHERE pregunta = 300;
            UPDATE sge_pregunta SET descripcion_resumida = 'Por qué lo considera principal' WHERE pregunta = 307;
            UPDATE sge_pregunta SET descripcion_resumida = 'Relación ocupación/profesión' WHERE pregunta = 309;
            UPDATE sge_pregunta SET descripcion_resumida = 'Actividad en esta ocupación' WHERE pregunta = 310;
            UPDATE sge_pregunta SET descripcion_resumida = 'Otras: Indique cuales' WHERE pregunta = 311;
            UPDATE sge_pregunta SET descripcion_resumida = 'Si es relación de dependencia' WHERE pregunta = 313;
            UPDATE sge_pregunta SET descripcion_resumida = 'Otros: indique cuáles' WHERE pregunta = 315;
            UPDATE sge_pregunta SET descripcion_resumida = 'Público: indique cuáles' WHERE pregunta = 316;
            UPDATE sge_pregunta SET descripcion_resumida = 'Trabajan en la organización' WHERE pregunta = 317;
            UPDATE sge_pregunta SET descripcion_resumida = 'Otro: indique cuál' WHERE pregunta = 319;
            UPDATE sge_pregunta SET descripcion_resumida = '¿Misma categoría ocupacional?' WHERE pregunta = 320;
            UPDATE sge_pregunta SET descripcion_resumida = 'Categoría ocupacional inicial' WHERE pregunta = 321;
            UPDATE sge_pregunta SET descripcion_resumida = 'Otra: ¿cuál?' WHERE pregunta = 323;
            UPDATE sge_pregunta SET descripcion_resumida = 'Ingreso promedio mensual' WHERE pregunta = 324;
            UPDATE sge_pregunta SET descripcion_resumida = 'Horas semanales de trabajo' WHERE pregunta = 325;
            UPDATE sge_pregunta SET descripcion_resumida = 'Trabajo relacionado a prof.' WHERE pregunta = 330;
            UPDATE sge_pregunta SET descripcion_resumida = 'Conocimientos teóricos: aplicó' WHERE pregunta = 331;
            UPDATE sge_pregunta SET descripcion_resumida = 'Carrera con suficiente teoría' WHERE pregunta = 332;
            UPDATE sge_pregunta SET descripcion_resumida = 'Habilidades prácticas: aplicó' WHERE pregunta = 333;
            UPDATE sge_pregunta SET descripcion_resumida = 'Habil. prácticas: suficientes' WHERE pregunta = 334;
            UPDATE sge_pregunta SET descripcion_resumida = '¿Cuántos?' WHERE pregunta = 336;
            UPDATE sge_pregunta SET descripcion_resumida = 'Categoría ocupacional actual' WHERE pregunta = 337;
            UPDATE sge_pregunta SET descripcion_resumida = 'Ingreso actual' WHERE pregunta = 338;
            UPDATE sge_pregunta SET descripcion_resumida = 'Carga horaria actual' WHERE pregunta = 339;
            UPDATE sge_pregunta SET descripcion_resumida = 'Causas de cambio de trabajo' WHERE pregunta = 340;
            UPDATE sge_pregunta SET descripcion_resumida = 'Por qué no consigue trabajo' WHERE pregunta = 342;
            UPDATE sge_pregunta SET descripcion_resumida = 'Por qué no busca trabajo' WHERE pregunta = 343;
            UPDATE sge_pregunta SET descripcion_resumida = 'Cursa/cursó estudios de grado' WHERE pregunta = 344;
            UPDATE sge_pregunta SET descripcion_resumida = 'Si: continúe con las preguntas' WHERE pregunta = 345;
            UPDATE sge_pregunta SET descripcion_resumida = 'Cursa/cursó estudios posgrado' WHERE pregunta = 349;
            UPDATE sge_pregunta SET descripcion_resumida = 'Abandonó: indique los motivos' WHERE pregunta = 355;
            UPDATE sge_pregunta SET descripcion_resumida = 'Carrera acreditada por CONEAU' WHERE pregunta = 357;
            UPDATE sge_pregunta SET descripcion_resumida = 'Como costea estudios posgrado' WHERE pregunta = 358;
            UPDATE sge_pregunta SET descripcion_resumida = 'No: continúe con las preguntas' WHERE pregunta = 359;
            UPDATE sge_pregunta SET descripcion_resumida = 'Área de la carrera' WHERE pregunta = 361;
            UPDATE sge_pregunta SET descripcion_resumida = 'Capacitación o actualización' WHERE pregunta = 362;
            UPDATE sge_pregunta SET descripcion_resumida = 'Realiza o realizó último año' WHERE pregunta = 363;
            UPDATE sge_pregunta SET descripcion_resumida = '¿Actividades de extensión?' WHERE pregunta = 365;
            UPDATE sge_pregunta SET descripcion_resumida = 'Actividad consejo profesional' WHERE pregunta = 366;
            UPDATE sge_pregunta SET descripcion_resumida = 'Participación activa política' WHERE pregunta = 367;
            UPDATE sge_pregunta SET descripcion_resumida = 'Información que desea recibir' WHERE pregunta = 368;
            UPDATE sge_pregunta SET descripcion_resumida = 'Qué modificar o incorporar' WHERE pregunta = 369;
            UPDATE sge_pregunta SET descripcion_resumida = 'Domicilio período de clases' WHERE pregunta = 374;
            UPDATE sge_pregunta SET descripcion_resumida = 'Domicilio de procedencia' WHERE pregunta = 380;
            UPDATE sge_pregunta SET descripcion_resumida = 'Financiamiento de los estudios' WHERE pregunta = 381;
            UPDATE sge_pregunta SET descripcion_resumida = 'Situación laboral (sin becas)' WHERE pregunta = 384;
            UPDATE sge_pregunta SET descripcion_resumida = 'Actividad semana pasada' WHERE pregunta = 385;
            UPDATE sge_pregunta SET descripcion_resumida = 'Maximo nivel estudios cursados' WHERE pregunta = 391;
            UPDATE sge_pregunta SET descripcion_resumida = 'Si no trabaja y no busca' WHERE pregunta = 395;";
        
        $this->get_db()->ejecutar($sql);        
    }
    
}
