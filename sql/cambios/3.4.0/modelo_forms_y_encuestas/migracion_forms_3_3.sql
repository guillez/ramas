---------------------------------------------------------------
------------ MIGRACION - COMUN ---------------------------------
----------------------------------------------------------------

/*
INSERT INTO kolla_new.sge_encuesta_estilo
	(estilo, nombre, descripcion, archivo)
SELECT estilo, nombre, descripcion, archivo
  FROM kolla.sge_encuestas_estilos;
--Se incluye en datos base
*/

INSERT INTO kolla_new.sge_encuesta_atributo
	(encuesta, nombre, descripcion, implementada, estado, texto_preliminar)	
SELECT encuesta, nombre, descripcion, implementada, estado, texto_preliminar
  FROM kolla.sge_encuestas_atributos;



/*
INSERT INTO kolla_new.sge_componente_pregunta
	(numero, componente, descripcion)
SELECT numero, componente, descripcion
  FROM kolla.sge_componente_pregunta;
--Se incluye en datos base
*/

INSERT INTO kolla_new.sge_pregunta
	(pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo,
	tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo)
SELECT pregunta, nombre,          numero, tabla_asociada, 
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
FROM kolla.sge_preguntas;

UPDATE kolla_new.sge_pregunta
   SET tabla_asociada='sge_institucion'
 WHERE tabla_asociada='sge_instituciones';

INSERT INTO kolla_new.sge_concepto
	(concepto, concepto_externo, sistema, descripcion)
SELECT concepto, concepto_externo, sistema,  descripcion
  FROM kolla.sge_concepto;

INSERT INTO kolla_new.sge_elemento
	(elemento, elemento_externo, sistema, url_img, descripcion)
SELECT elemento, elemento_externo, sistema,url_img, descripcion 
  FROM kolla.sge_elemento;


INSERT INTO kolla_new.sge_respuesta
	(respuesta, valor_tabulado)
SELECT respuesta, valor_tabulado
  FROM kolla.sge_respuestas;

INSERT INTO kolla_new.sge_pregunta_respuesta
SELECT respuesta, pregunta, orden
  FROM kolla.sge_preguntas_respuestas;

-- Function: sp_migrar_bloques(integer, integer, integer[])

/**
Inserta las preguntas migradas en temp_sge_encuesta_definicion y en kolla_new.sge_encuesta_definicion
para que se pueda asociar el encuesta, bloque, numero, pregunta con el id nuevo (ya 
que en la tabla nueva, se borran y/o modifican esas columnas).
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
	ORDER BY ed.encuesta, ed.numero 
	
	LOOP
		IF x.encuesta <> ult_enc THEN
			RAISE NOTICE 'Porcesando encuesta %', x.encuesta;
			nuevo_ord_preg := 0;
			nuevo_ord_bloq := 0;
			ult_bloq := -1898; --quiero que no coincida el prox bloque 
			ult_enc := x.encuesta;
		END IF;

		IF x.bloque <> ult_bloq THEN
			ult_bloq := x.bloque;
			RAISE NOTICE 'Generarndo nuevo bloque %', x.descripcion;
			INSERT INTO kolla_new.sge_bloque
				(nombre, descripcion, orden)
			VALUES (x.nombre, x.descripcion, nuevo_ord_bloq)
			RETURNING bloque INTO nuevo_bloq;
			
			
			nuevo_ord_bloq := nuevo_ord_bloq +1;
		END IF;

		RAISE NOTICE 'Porcesando fila %', x;

		INSERT INTO kolla_new.sge_encuesta_definicion
			(  encuesta,     bloque,    pregunta,    orden,   obligatoria)
		VALUES   (x.encuesta, nuevo_bloq, x.pregunta, nuevo_ord_preg, x.obligatoria)
		RETURNING encuesta_definicion INTO nuevo_id_preg;

		-- Copio la tabla, con el nuevo id para que se puedan migrar las respuestas
		INSERT INTO temp_sge_encuesta_definicion
				(encuesta_definicion, encuesta, bloque, numero, pregunta, obligatoria)
		VALUES (nuevo_id_preg,   x.encuesta, x.bloque, x.numero, x.pregunta, x.obligatoria);

		IF (nuevo_id_preg = 30) THEN
--tengo que dejar un id vacio por pregunta que se elimino.
			PERFORM setval('sge_encuesta_definicion_seq',31);

		END IF;

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
------------ MIGRACION  3.3  -----------------------------------
----------------------------------------------------------------
INSERT INTO kolla_new.sge_sistema_externo
	(sistema, nombre, usuario, estado)
SELECT sistema, nombre, usuario, estado
  FROM kolla.sge_sistemas_externos;



----------------------------------------------------------------
-- Copio la tabla de hablitacion. 
----------------------------------------------------------------
INSERT INTO kolla_new.sge_habilitacion
	(habilitacion, fecha_desde, fecha_hasta, paginado, estilo, externa, anonima, descripcion, texto_preliminar, sistema, password_se)
SELECT habilitacion, fecha_desde, fecha_hasta, paginado, estilo, externa, anonima, 'Habilitación externa', '', sistema, password_se
FROM kolla.sge_encuesta_habilitada eh ;
--INNER JOIN sge_encuestas_atributos ea ON
--	eh.encuesta = ea.encuesta;


----------------------------------------------------------------
-- Creacion de un formulario_habilitado por cada habilitacion.
-- Uso el id de habilitacion como id_formulario_habilitado ya que es 1 a 1 el mapeo
----------------------------------------------------------------

INSERT INTO kolla_new.sge_formulario_habilitado
(formulario_habilitado, habilitacion, concepto, nombre, estado)
SELECT formulario_habilitado, habilitacion, concepto, nombre,estado
  FROM kolla.sge_formulario_habilitado;


--Creacion del detalle de los formularios, por cada habilitacion

INSERT INTO kolla_new.sge_formulario_habilitado_detalle 
	(formulario_habilitado, encuesta, elemento, orden)
SELECT formulario_habilitado, encuesta, elemento, orden 
FROM kolla.sge_formulario_detalle;

----------------------------------------------------------------
--Actualizacion de los grupos de encuestados.
----------------------------------------------------------------

/* --No aplica! */

-----------------------------------------
--- Migracion de las respuestas - 
-----------------------------------------

----------------------------------------------------------------
-- Copio formulario_realizado_encabezado a sge_respondido_formulario
----------------------------------------------------------------
INSERT INTO kolla_new.sge_respondido_formulario
	(respondido_formulario, formulario_habilitado,ingreso,fecha,codigo_recuperacion,version_digest, terminado, fecha_terminado)
SELECT  formulario_encabezado, formulario_habilitado, ingreso, fecha,codigo_recuperacion,version_digest, 'S' , fecha as ft   
  FROM kolla.sge_formulario_realizado_encabezado fre;

----------------------------------------------------------------
-- Copio encuesta_realizada_encabezado a sge_respondido_encuesta
----------------------------------------------------------------
INSERT INTO kolla_new.sge_respondido_encuesta
      (respondido_encuesta,    respondido_formulario,    formulario_habilitado_detalle)
SELECT ee.encuesta_encabezado, fre.formulario_encabezado, fhd.formulario_habilitado_detalle
FROM 
  kolla.sge_formulario_realizado_encabezado fre
INNER JOIN kolla.sge_encuestas_realizada_encabezado ee
	ON ee.formulario_encabezado = fre.formulario_encabezado
--JOINEO CON LA TABLA NUEVA QUE TIENE LOS IDS RECIEN GENERADOS
INNER JOIN kolla_new.sge_formulario_habilitado_detalle fhd
	ON fre.formulario_habilitado = fhd.formulario_habilitado AND
  	   ee.orden                 = fhd.orden
ORDER BY ee.encuesta_encabezado; 

----------------------------------------------------------------
-- Copio encuesta_realizada_/valores a sge_respondido_detalle
----------------------------------------------------------------
--NO SE ESTAN MIGRANDO LAS MODERACIONES
INSERT INTO kolla_new.sge_respondido_detalle
	(respondido_encuesta, encuesta_definicion, respuesta_codigo, respuesta_valor, moderada)
SELECT  ee.encuesta_encabezado, ed.encuesta_definicion, rv.respuesta  , rv.respuesta_valor, 'N'
FROM 
  kolla.sge_formulario_realizado_encabezado fe
INNER JOIN kolla.sge_encuestas_realizada_encabezado ee
	ON ee.formulario_encabezado = fe.formulario_encabezado
--JOINEO CON LA TABLA NUEVA QUE TIENE LOS IDS RECIEN GENERADOS
INNER JOIN kolla_new.sge_formulario_habilitado_detalle fhd
	ON fe.formulario_habilitado = fhd.formulario_habilitado AND
  	   ee.orden                 = fhd.orden
--INNER JOIN kolla_new.sge_respondido_encuesta re
--ON re.formulario_habilitado_detalle = fhd.formulario_habilitado_detalle
 --FIX 06/11/13!!!
INNER JOIN kolla.respuestas_view rv
	ON rv.encuesta_encabezado = ee.encuesta_encabezado
INNER JOIN temp_sge_encuesta_definicion ed 
	ON ed.encuesta = rv.encuesta AND
	ed.bloque = rv.bloque AND
	ed.pregunta = rv.pregunta AND
	ed.numero = rv.numero;


INSERT INTO kolla_new.sge_respondido_encuestado(
            respondido_encuestado, encuestado, sistema, codigo_externo, formulario_habilitado, 
            respondido_formulario, terminado, ignorado, estado_sinc, fecha)
SELECT      ee.encuesta_externa,  en.encuestado, ee.sistema,  ee.codigo_externo, fh.formulario_habilitado, 
            ee.formulario_encabezado, NULL, NULL    , ee.estado, ee.fecha
FROM kolla.sge_encuestas_externas ee
INNER JOIN kolla.sge_formulario_habilitado fh ON
		ee.concepto = fh.concepto 
		AND ee.habilitacion = fh.habilitacion
INNER JOIN kolla.sge_sistemas_externos si
	ON ee.sistema = si.sistema
INNER JOIN kolla.sge_encuestados en
	ON si.usuario = en.usuario;


