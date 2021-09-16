
set constraints all deferred;

--Las encuestas 1 y 2, si están insertadas en las nuevas tablas es porque tuvieron habilitaciones 
--1-Recien graduado
--2-Año de graduado
--3-5 años de graduado (aparece en 3.1.0)
--4-Relevamiento ingenierias (aparece en 3.1.2)
--Las encuestas >2 si las hay, son locales al usuario
--Las encuestas 1 y 2 se insertarán con sus nuevos ids en un script posterior. En este punto se migra todo lo que haya

--Se mueven todas las encuestas al rango >= 101
UPDATE kolla_new.sge_encuesta_atributo SET encuesta = encuesta +100;
UPDATE kolla_new.sge_encuesta_definicion SET encuesta = encuesta +100;
UPDATE kolla_new.sge_formulario_habilitado_detalle SET encuesta = encuesta +100;
UPDATE kolla_new.sge_log_formulario_definicion_habilitacion  SET encuesta = encuesta +100;
UPDATE kolla_new.sge_encuesta_indicador SET encuesta = encuesta +100;

--Los bloques se mueven todos al rango >= 1001
UPDATE kolla_new.sge_bloque SET bloque = bloque + 1000;
UPDATE kolla_new.sge_encuesta_definicion SET bloque = bloque + 1000;

--Las preguntas se mueven todas al rango >= 10.001
UPDATE kolla_new.sge_pregunta SET pregunta = pregunta + 10000;
UPDATE kolla_new.sge_encuesta_definicion SET pregunta = pregunta + 10000;
UPDATE kolla_new.sge_pregunta_respuesta SET pregunta = pregunta + 10000;

--Las respuestas se mueven todas al rango >= 100.001
UPDATE kolla_new.sge_respuesta SET respuesta = respuesta + 100000;
UPDATE kolla_new.sge_pregunta_respuesta SET respuesta = respuesta + 100000;
UPDATE kolla_new.sge_respondido_detalle srd SET respuesta_codigo = respuesta_codigo + 100000
                                            FROM kolla_new.sge_encuesta_definicion ed, 
                                                kolla_new.sge_pregunta sp 
                                            WHERE ed.encuesta_definicion = srd.encuesta_definicion
                                                AND (respuesta_codigo IS NOT NULL AND srd.respuesta_codigo >= 100000)
                                                AND ed.pregunta = sp.pregunta
                                                AND (sp.tabla_asociada <> '' AND sp.tabla_asociada IS NOT NULL);

--encuesta_definición toma valores desde 10.001
UPDATE kolla_new.sge_encuesta_definicion SET encuesta_definicion = encuesta_definicion + 10000;
UPDATE kolla_new.sge_respondido_detalle SET encuesta_definicion = encuesta_definicion + 10000;

--Se actualizan estos valores en las tablas que registran respondidas y reportes
UPDATE kolla_new.sge_formulario_definicion SET encuesta = encuesta +100;

UPDATE kolla_new.sge_reporte_exportado SET encuesta = encuesta +100;