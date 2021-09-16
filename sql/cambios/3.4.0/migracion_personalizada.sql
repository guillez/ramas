
set constraints all deferred;
-- Nota: el menor que XXX es para que el script sea idempotente.

--Muevo encuestas del 5++ al rango 101++
UPDATE kolla_new.sge_encuesta_atributo SET encuesta = encuesta +96 WHERE encuesta >= 5 AND encuesta < 101;

UPDATE kolla_new.sge_encuesta_definicion SET encuesta = encuesta +96 WHERE encuesta >= 5 AND encuesta < 101;

UPDATE kolla_new.sge_formulario_habilitado_detalle SET encuesta = encuesta + 96 WHERE encuesta >= 5 AND encuesta < 101;

UPDATE kolla_new.sge_encuesta_indicador SET encuesta = encuesta + 96 WHERE encuesta >= 5 AND encuesta < 101;


--Los bloques 'oficiales' son hasta el 33, el migrador normaliza todos (del 1 a N). Mover del 34 al 1001
UPDATE kolla_new.sge_bloque SET bloque = bloque + 967 WHERE bloque >= 34 AND bloque < 1001;

UPDATE kolla_new.sge_encuesta_definicion SET bloque = bloque + 967 WHERE bloque >= 34 AND bloque < 1001;


--Preguntas son hasta 396. Migro desde 397 al 10.001
UPDATE kolla_new.sge_pregunta SET pregunta = pregunta + 9604 WHERE pregunta >= 397 AND pregunta < 10001;

UPDATE kolla_new.sge_encuesta_definicion SET pregunta = pregunta + 9604 WHERE pregunta >= 397 AND pregunta < 10001;

UPDATE kolla_new.sge_pregunta_respuesta SET pregunta = pregunta + 9604 WHERE pregunta >= 397 AND pregunta < 10001;

--Respuestas hasta 3077, Migro desde 3078 al 100.001
UPDATE kolla_new.sge_respuesta SET respuesta = respuesta + 96923 WHERE respuesta >= 3078 AND respuesta < 100001;

UPDATE kolla_new.sge_pregunta_respuesta SET respuesta = respuesta + 96923 WHERE respuesta >= 3078 AND respuesta < 100001;

UPDATE kolla_new.sge_respondido_detalle SET respuesta_codigo = respuesta_codigo + 96923 WHERE respuesta_codigo >= 3078 AND respuesta_codigo < 100001;


--Encuesta-def hasta 286, Migro desde 289 a 10.001
UPDATE kolla_new.sge_encuesta_definicion SET encuesta_definicion = encuesta_definicion + 9712
		WHERE encuesta_definicion >= 289 AND encuesta_definicion < 10001;

UPDATE kolla_new.sge_encuesta_indicador SET encuesta_definicion = encuesta_definicion + 9712
		WHERE encuesta_definicion >= 289 AND encuesta_definicion < 10001;

UPDATE kolla_new.sge_formulario_habilitado_indicador SET encuesta_definicion = encuesta_definicion + 9712
		WHERE encuesta_definicion >= 289 AND encuesta_definicion < 10001;

UPDATE kolla_new.sge_respondido_detalle SET encuesta_definicion = encuesta_definicion + 9712
		WHERE encuesta_definicion >= 289 AND encuesta_definicion < 10001;

