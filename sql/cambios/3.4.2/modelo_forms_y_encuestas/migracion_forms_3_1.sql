/* Se agrega la inserción de datos en la tabla sge_ws_conexion en este script */
INSERT INTO	kolla_new.sge_ws_conexion (conexion, conexion_nombre, ws_url, ws_user, ws_clave, activa)
SELECT 		conexion_id, conexion_nombre, ws_url, ws_user, ws_clave, activa
FROM 		kolla.sge_ws_conexiones;

----------------------------------------------------------------
------------ MIGRACION - COMUN ---------------------------------
----------------------------------------------------------------
/*
//copiar encuestas de kolla con habilitaciones - esto es por si sufrieron modificaciones 
//migrar encuestas que hayan sido definidas por el usuario
*/
INSERT INTO kolla_new.sge_encuesta_atributo	(encuesta, nombre, descripcion, implementada, estado, texto_preliminar)	
SELECT DISTINCT encuesta as encuesta, nombre, descripcion, implementada, estado, texto_preliminar
FROM kolla.sge_encuestas_atributos
WHERE (encuesta <=3  AND encuesta in (SELECT encuesta FROM kolla.sge_encuesta_habilitada seh)) 
    OR (encuesta > 3);

/*
//copiar preguntas de kolla que participaron de una habilitacion - esto es por si sufrieron modificaciones
//migrar preguntas que hayan sido definidas por el usuario
*/
INSERT INTO kolla_new.sge_pregunta
	(pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo,
	tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo)
SELECT DISTINCT sp.pregunta as pregunta, sp.nombre,          sp.numero, tabla_asociada, 
    CASE WHEN tabla_asociada = '' OR tabla_asociada IS NULL THEN NULL
         ELSE 'codigo'
    END,
    CASE WHEN tabla_asociada = '' OR tabla_asociada IS NULL THEN NULL
         ELSE 'nombre'
    END,
    CASE WHEN tabla_asociada = '' OR tabla_asociada IS NULL THEN NULL
         ELSE 'codigo'
    END,
    CASE WHEN tabla_asociada = '' OR tabla_asociada IS NULL THEN NULL
         ELSE 'ASC'
    END
FROM kolla.sge_preguntas sp 
	INNER JOIN kolla.sge_encuesta_definicion sed ON (sp.pregunta = sed.pregunta)
WHERE (encuesta <=3  AND encuesta in (SELECT encuesta FROM kolla.sge_encuesta_habilitada seh)) 
    OR (encuesta > 3);


UPDATE kolla_new.sge_pregunta
   SET tabla_asociada='sge_institucion'
 WHERE tabla_asociada='sge_instituciones';

/*
//copiar respuestas de kolla que participaron de una habilitacion - esto es por si sufrieron modificaciones
//migrar respuestas que hayan sido definidas por el usuario
*/
INSERT INTO kolla_new.sge_respuesta
	(respuesta, valor_tabulado)
SELECT DISTINCT sr.respuesta, sr.valor_tabulado
  FROM kolla.sge_respuestas sr
	INNER JOIN kolla.sge_preguntas_respuestas spr ON (sr.respuesta = spr.respuesta)
	INNER JOIN kolla.sge_encuesta_definicion sed ON (spr.pregunta = sed.pregunta)
WHERE (encuesta <=3  AND encuesta in (SELECT encuesta FROM kolla.sge_encuesta_habilitada seh)) 
        OR (encuesta > 3)
ORDER BY sr.respuesta;

/*
//copiar asociaciones entre preguntas y respuestas de kolla que participaron de una habilitacion - esto es por si sufrieron modificaciones
//migrar asociaciones entre preguntas y respuestas que hayan sido definidas por el usuario
*/
INSERT INTO kolla_new.sge_pregunta_respuesta(respuesta, pregunta, orden)
SELECT DISTINCT spr.respuesta as respuesta, spr.pregunta as pregunta, spr.orden
  FROM kolla.sge_preguntas_respuestas spr
	INNER JOIN kolla.sge_preguntas sp ON (spr.pregunta = sp.pregunta)
	INNER JOIN kolla.sge_encuesta_definicion sed ON (sp.pregunta = sed.pregunta)
WHERE (encuesta <=3  AND encuesta in (SELECT encuesta FROM kolla.sge_encuesta_habilitada seh)) 
        OR (encuesta > 3);

-- Function: sp_migrar_bloques(integer, integer, integer[])

/**
Inserta las preguntas migradas en temp_sge_encuesta_definicion y en kolla_new.sge_encuesta_definicion
para que se pueda asociar el encuesta, bloque, numero, pregunta con el id nuevo (ya 
que en la tabla nueva, se borran y/o modifican esas columnas).
*/
/*
Esto se hace para las encuestas del usuario y para las encuestas de kolla que tengan alguna habilitacion
*/
-- DROP FUNCTION sp_migrar_bloques(integer, integer, integer[]);

--drop function sp_migrar_bloques();
CREATE OR REPLACE FUNCTION sp_migrar_bloques()
  RETURNS int AS
$BODY$
DECLARE 

x record;


nuevo_bloq int;
nuevo_ord_preg int;
nuevo_ord_bloq int;

nuevo_id_preg int;

ult_bloq int := -11548;
ult_enc int := -15118;

BEGIN

RAISE NOTICE 'Comienzo';


-- CREO TABLA TEMPORAL PARA GENERAR LOS NUEVOS IDS
CREATE TEMP TABLE temp_sge_encuesta_definicion
(
  encuesta_definicion int NOT NULL,
  encuesta int4 NOT NULL,
  bloque int4 NOT NULL,
  numero int4 NOT NULL,
  pregunta int4 NOT NULL,
  obligatoria char(1) NOT NULL
);


FOR x IN 
	SELECT 
	  ed.encuesta, 
	  ed.pregunta,
      ed.numero, 
      ed.obligatoria,
	  b.bloque, 
	  b.descripcion,
	  b.nombre
	FROM 
	  kolla.sge_encuesta_definicion ed 
	INNER JOIN kolla.sge_bloques b 
		ON ed.bloque = b.bloque
    WHERE (encuesta <=3  AND encuesta in (SELECT encuesta FROM kolla.sge_encuesta_habilitada seh)) 
        OR (encuesta > 3)
	ORDER BY ed.encuesta, ed.numero 
	
	LOOP
		IF x.encuesta <> ult_enc THEN
			RAISE NOTICE 'Procesando encuesta %', x.encuesta;
			nuevo_ord_preg := 0;
			nuevo_ord_bloq := 0;
			ult_bloq := -1898; --quiero que no coincida el prox bloque 
			ult_enc := x.encuesta;
		END IF;

		IF x.bloque <> ult_bloq THEN
			ult_bloq := x.bloque;
			RAISE NOTICE 'Generando nuevo bloque %', x.descripcion;
			INSERT INTO kolla_new.sge_bloque
				(nombre, descripcion, orden)
			VALUES (x.nombre, x.descripcion, nuevo_ord_bloq)
			RETURNING bloque INTO nuevo_bloq;
			
			
			nuevo_ord_bloq := nuevo_ord_bloq +1;
		END IF;

		RAISE NOTICE 'Procesando fila %', x;

		INSERT INTO kolla_new.sge_encuesta_definicion
			(  encuesta,     bloque,    pregunta,    orden,   obligatoria)
		VALUES   (x.encuesta, nuevo_bloq, x.pregunta, nuevo_ord_preg, x.obligatoria)
		RETURNING encuesta_definicion INTO nuevo_id_preg;

		-- Copio la tabla, con el nuevo id para que se puedan migrar las respuestas
		INSERT INTO temp_sge_encuesta_definicion
				(encuesta_definicion, encuesta, bloque, numero, pregunta, obligatoria)
		VALUES (nuevo_id_preg,   x.encuesta, x.bloque, x.numero, x.pregunta, x.obligatoria);


		nuevo_ord_preg:= nuevo_ord_preg + 1;
	END LOOP; 

RAISE NOTICE 'Fin';
RETURN (0);
END
$BODY$
  LANGUAGE 'plpgsql' VOLATILE;
ALTER FUNCTION sp_migrar_bloques() OWNER TO postgres;

select * from sp_migrar_bloques();
drop function sp_migrar_bloques(); --NO LO NECESITO MAS
--ME DEJA LA TABLA temp_sge_encuesta_definicion PARA MIGRAR RESPUESTAS


---------------------------------------------------------------+
------------ MIGRACION - COMUN ---------------------------------
----------------------------------------------------------------


CREATE OR REPLACE VIEW kolla.respuestas_view AS
SELECT 
  sge_encuestas_realizada.encuesta_encabezado AS encuesta_encabezado, 
  sge_encuestas_realizada.encuesta AS encuesta, 
  sge_encuestas_realizada.bloque AS bloque,
  sge_encuestas_realizada.numero AS numero , 
  sge_encuestas_realizada.pregunta AS pregunta, 
  sge_encuestas_realizada.respuesta AS respuesta, 
  NULL AS respuesta_valor
FROM 
  kolla.sge_encuestas_realizada 

UNION 
SELECT 
  sge_encuestas_realizada_valores.encuesta_encabezado, 
  sge_encuestas_realizada_valores.encuesta, 
  sge_encuestas_realizada_valores.bloque,
  sge_encuestas_realizada_valores.numero, 
  sge_encuestas_realizada_valores.pregunta,
  NULL,
  sge_encuestas_realizada_valores.valor
FROM 
  kolla.sge_encuestas_realizada_valores ;


---------------------------------------------------------------+
------------ MIGRACION      ------------------------------------
----------------------------------------------------------------

----------------------------------------------------------------
--Creacion de un formulario por cada encuesta.
-- Uso el id de encuesta para el formulario porque el mapeo es 1 a 1
----------------------------------------------------------------

INSERT INTO kolla_new.sge_formulario_atributo
    (formulario, nombre, descripcion, texto_preliminar, estado)
SELECT e.encuesta, e.nombre, e.descripcion, e.texto_preliminar, 'A'
FROM kolla.sge_encuestas_atributos e
WHERE (encuesta <=3  AND encuesta in (SELECT encuesta FROM kolla.sge_encuesta_habilitada seh)) 
        OR (encuesta > 3)
;

INSERT INTO kolla_new.sge_formulario_definicion 
    (formulario_definicion, formulario,         encuesta,   tipo_elemento, orden)
SELECT f.formulario as fd, f.formulario as f, f.formulario as e,   NULL,    0
FROM kolla_new.sge_formulario_atributo f;

----------------------------------------------------------------
-- Copio la tabla de hablitacion.
----------------------------------------------------------------
INSERT INTO kolla_new.sge_habilitacion
	(habilitacion, fecha_desde, fecha_hasta, paginado, estilo, externa, anonima, 
    descripcion, texto_preliminar, sistema, password_se, url_imagenes_base, generar_cod_recuperacion)
SELECT habilitacion, fecha_desde, fecha_hasta, paginado, estilo, 'N', 'N', ea.nombre, ea.texto_preliminar, NULL, NULL, NULL, 'N' 
FROM kolla.sge_encuesta_habilitada eh 
INNER JOIN kolla.sge_encuestas_atributos ea ON	eh.encuesta = ea.encuesta;

--se actualiza el estilo porque se cambiaron ids
--primero se utilizan ids de estilo temporales porque hay switchs de ids
UPDATE kolla_new.sge_habilitacion SET estilo = 1003 WHERE estilo = 1;
UPDATE kolla_new.sge_habilitacion SET estilo = 1001 WHERE estilo = 2;
UPDATE kolla_new.sge_habilitacion SET estilo = 1002 WHERE estilo = 3;
--por ultimo se actualiza con los definitivos
UPDATE kolla_new.sge_habilitacion SET estilo = 1 WHERE estilo = 1001;
UPDATE kolla_new.sge_habilitacion SET estilo = 2 WHERE estilo = 1002;
UPDATE kolla_new.sge_habilitacion SET estilo = 3 WHERE estilo = 1003;

----------------------------------------------------------------
--Creacion de un formulario_habilitado por cada habilitacion.
-- Uso el id de habilitacion como id_formulario_habilitado ya que es 1 a 1 el mapeo
----------------------------------------------------------------
INSERT INTO kolla_new.sge_formulario_habilitado (formulario_habilitado, nombre, habilitacion, concepto, estado)
SELECT h.habilitacion as fh,   e.nombre, h.habilitacion, NULL, 'A'
FROM kolla.sge_encuesta_habilitada h 
INNER JOIN kolla.sge_encuestas_atributos e ON e.encuesta = h.encuesta;

----------------------------------------------------------------
--Creacion del log en sge_log_formulario_definicion_habilitacion 
-- Uso los datos de la habilitacion, ya que hay solo un formulario habilitado y solo un log por cada habilitacion
----------------------------------------------------------------
INSERT INTO kolla_new.sge_log_formulario_definicion_habilitacion 
    (habilitacion, encuesta, tipo_elemento, orden)
SELECT h.habilitacion, e.encuesta, NULL, 0
FROM kolla.sge_encuesta_habilitada h 
INNER JOIN kolla.sge_encuestas_atributos e
	ON e.encuesta = h.encuesta;
----------------------------------------------------------------
--Creacion del detalle de los formularios, por cada habilitacion.
--Id fh = habilitacion, y como tiene 1 solo detalle, tambien uso la habilitacion.
----------------------------------------------------------------
INSERT INTO kolla_new.sge_formulario_habilitado_detalle  
	(formulario_habilitado_detalle, formulario_habilitado, encuesta, elemento, orden, tipo_elemento) 
SELECT   h.habilitacion as h1,   h.habilitacion,   e.encuesta, NULL,   0, NULL
FROM kolla.sge_encuesta_habilitada h 
INNER JOIN kolla.sge_encuestas_atributos e
	ON e.encuesta = h.encuesta;

----------------------------------------------------------------
--Actualizacion de los grupos de encuestados.
----------------------------------------------------------------
--Cada habilitacion pasa a tener un formulario habilitado con id = habilitacion
INSERT INTO kolla_new.sge_grupo_habilitado
    (grupo, formulario_habilitado)
SELECT  grupo_encuestado, habilitacion
	FROM kolla.sge_grupos_encuestas;

-----------------------------------------
--- Migracion de las respuestas
-----------------------------------------

----------------------------------------------------------------
--Pongo en el encabezado de formulario al encabezado de encuesta. Uso el mismo id de encabezado, ya que es 1 a 1 el mapeo.
----------------------------------------------------------------
INSERT INTO kolla_new.sge_respondido_formulario	(respondido_formulario, formulario_habilitado, ingreso, fecha, 
    codigo_recuperacion,version_digest, 
    terminado, 
    fecha_terminado)
SELECT ee.encuesta_encabezado, ee.habilitacion,  ee.ingreso, ee.fecha, 
    0               , ''           , 
    CASE WHEN et.encuesta_encabezado IS NULL THEN 'N' ELSE 'S' END AS terminado , 
	et.fecha
  FROM kolla.sge_encuestas_realizada_encabezado ee 
	LEFT JOIN kolla.sge_encuestas_terminada et ON (et.encuesta_encabezado = ee.encuesta_encabezado)
ORDER BY ee.encuesta_encabezado;

----------------------------------------------------------------
--Hago un encabezado encuesta por cada encuesta encabezado. Uso el id de encabezado ya que cada form tiene 1 encuesta.
----------------------------------------------------------------
INSERT INTO kolla_new.sge_respondido_encuesta
      (respondido_encuesta, respondido_formulario,    formulario_habilitado_detalle)
SELECT ee.encuesta_encabezado, ee.encuesta_encabezado, ee.habilitacion
 FROM kolla.sge_encuestas_realizada_encabezado ee 
ORDER BY encuesta_encabezado; 

--Hay que cargarle el respuestas_view!!
INSERT INTO kolla_new.sge_respondido_detalle
	(respondido_encuesta, encuesta_definicion, respuesta_codigo, respuesta_valor, moderada)
SELECT  rv.encuesta_encabezado, ed.encuesta_definicion, rv.respuesta , rv.respuesta_valor, 'N'                   
FROM kolla.respuestas_view rv
--JOINEO CON LA TABLA NUEVA QUE TIENE LOS IDS RECIEN GENERADOS
INNER JOIN temp_sge_encuesta_definicion ed 
	ON ed.encuesta = rv.encuesta AND
	ed.bloque = rv.bloque AND
	ed.pregunta = rv.pregunta AND
	ed.numero = rv.numero;


--Todo lo que tiene encuesta encabezado tiene alguna respuesta y por lo tanto no está ignorada
INSERT INTO kolla_new.sge_respondido_encuestado(
            respondido_encuestado, encuestado, sistema, codigo_externo, formulario_habilitado, 
            respondido_formulario, estado_sinc, fecha,
            terminado, ignorado)
SELECT      ee.encuesta_encabezado, ee.encuestado, NULL,  NULL, ee.habilitacion, 
            ee.encuesta_encabezado, 'OK', 
            CASE WHEN ee.fecha IS NULL THEN eh.fecha_hasta ELSE ee.fecha END as fecha, 
            CASE WHEN et.encuesta_encabezado IS NULL THEN 'N' ELSE 'S' END as terminado, 'N' as ignorado
 FROM kolla.sge_encuestas_realizada_encabezado ee 
    LEFT JOIN kolla.sge_encuestas_terminada et ON (et.encuesta_encabezado = ee.encuesta_encabezado)
    INNER JOIN kolla.sge_encuesta_habilitada eh ON (eh.habilitacion = ee.habilitacion);

--Actualizo secuencia ya porque tengo que insertar los ignorados
SELECT setval('kolla_new.sge_respondido_encuestado_seq',
			(SELECT MAX(respondido_encuestado) FROM kolla_new.sge_respondido_encuestado));

INSERT INTO kolla_new.sge_respondido_encuestado(
            encuestado, sistema, codigo_externo, formulario_habilitado, 
            respondido_formulario, terminado, ignorado, estado_sinc, fecha)
SELECT      u.encuestado, NULL,  NULL, ei.habilitacion, 
            NULL,                   'N',        'S'  , 'OK',  ei.fecha
  FROM kolla.sge_encuestas_ignoradas ei
  INNER JOIN kolla.sge_encuestados u
ON u.usuario = ei.usuario;


-----------------------------------------
--- Migracion de reportes exportados
-----------------------------------------
--1 - por encuestado
--2 - por pregunta
--3 - preguntas de respuesta múltiple por encuestado -- ESTE NO ESTÁ MÁS
INSERT INTO kolla_new.sge_reporte_exportado(exportado_codigo, formulario_habilitado, reporte_tipo, fecha_desde, fecha_hasta,
  inconclusas, multiples, archivo, codigos, encuesta, elemento)
SELECT exportado_codigo, habilitacion, reporte_tipo, fecha_desde, fecha_hasta,
  inconclusas, COALESCE(multiples, 0), archivo, 0, encuesta, NULL
FROM kolla.sge_reportes_exportados
--WHERE reporte_tipo <> 3
;

-- Si hubiera reportes de tipo 3 en la tabla de reportes exportados 
-- hay que insertar dicho tipo en la tabla sge_reporte_tipo
INSERT INTO kolla_new.sge_reporte_tipo( reporte_tipo, nombre, descripcion)
SELECT srt.reporte_tipo, srt.nombre, srt.descripcion
FROM kolla.sge_reportes_exportados sre 
    INNER JOIN kolla.sge_reportes_tipos srt ON (sre.reporte_tipo = srt.reporte_tipo)
WHERE sre.reporte_tipo = 3
LIMIT 1;
--El tipo de reporte se removerá manualmente de las opciones en las operaciones de generación de reportes


----------------------------------------------------------------
--Migración de los indicadores
----------------------------------------------------------------
INSERT INTO	kolla_new.sge_encuesta_indicador(encuesta_definicion, encuesta)
SELECT 		ted.encuesta_definicion, ted.encuesta
FROM 		kolla.sge_encuesta_indicadores sei
                INNER JOIN temp_sge_encuesta_definicion ted 
                                ON ted.encuesta = sei.encuesta AND
                                ted.bloque = sei.bloque AND
                                ted.pregunta = sei.pregunta AND
                                ted.numero = sei.numero;

INSERT INTO	kolla_new.sge_formulario_habilitado_indicador
    (encuesta_definicion, formulario_habilitado_detalle, formulario_habilitado)

SELECT ted.encuesta_definicion, eh.habilitacion, eh.habilitacion
FROM kolla.sge_encuesta_habilitacion_indicadores ehi 
	INNER JOIN kolla.sge_encuesta_habilitada eh ON (ehi.habilitacion = eh.habilitacion
						AND ehi.encuesta = eh.encuesta)
	INNER JOIN kolla.sge_encuesta_definicion ed ON (ehi.encuesta = ed.encuesta 
						AND ehi.bloque = ed.bloque
						AND ehi.numero = ed.numero
						AND ehi.pregunta = ed.pregunta
						)
	INNER JOIN temp_sge_encuesta_definicion ted ON (ted.encuesta = ehi.encuesta 
						AND ted.bloque = ehi.bloque 
						AND ted.pregunta = ehi.pregunta 
						AND ted.numero = ehi.numero);