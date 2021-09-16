-- Function: sp_guarda_respuesta_tabulada(integer, integer, integer)

-- DROP FUNCTION sp_guarda_respuesta_tabulada(integer, integer, integer);

CREATE OR REPLACE FUNCTION sp_guarda_respuesta_tabulada
	(Prespondido_encuesta integer, Pencuesta_definicion integer, Prespuesta integer)
  RETURNS int AS
$BODY$
DECLARE 
id_rta integer;

BEGIN
IF (Prespuesta IS NULL) THEN
	DELETE FROM sge_respondido_detalle WHERE
	  respondido_encuesta = Prespondido_encuesta AND
	  encuesta_definicion = Pencuesta_definicion
	  RETURNING respondido_detalle INTO id_rta;
ELSE
	UPDATE sge_respondido_detalle SET respuesta_codigo = Prespuesta
	WHERE
	  respondido_encuesta = Prespondido_encuesta AND
	  encuesta_definicion = Pencuesta_definicion
	RETURNING respondido_detalle INTO id_rta;

	IF( id_rta IS NULL) THEN
		INSERT INTO sge_respondido_detalle (respondido_encuesta, encuesta_definicion, respuesta_codigo)
		VALUES(Prespondido_encuesta, Pencuesta_definicion, Prespuesta)
		RETURNING respondido_detalle INTO id_rta;
	END IF;
END IF;



RETURN id_rta;
END
$BODY$
  LANGUAGE 'plpgsql' VOLATILE;
ALTER FUNCTION sp_guarda_respuesta_tabulada(integer, integer, integer) OWNER TO postgres;