-- Function: sp_guarda_respuesta_libre(integer, integer, character varying)

-- DROP FUNCTION sp_guarda_respuesta_libre(integer, integer, character varying);
CREATE OR REPLACE FUNCTION sp_guarda_respuesta_libre(integer, integer, character varying)
  RETURNS int AS
$BODY$
DECLARE 
Prespondido_encuesta ALIAS for $1;
Pencuesta_definicion ALIAS for $2;
Prespuesta ALIAS FOR $3; --respuesta_codigo o respuesta_valor segun el tipo
recl record;


id_rta integer;

BEGIN
IF (Prespuesta IS NULL) THEN
	DELETE FROM sge_respondido_detalle 
	WHERE 	respondido_encuesta = Prespondido_encuesta AND
		encuesta_definicion = Pencuesta_definicion
	RETURNING respondido_detalle INTO id_rta;
ELSE
	UPDATE sge_respondido_detalle SET respuesta_valor = Prespuesta
	WHERE
	  respondido_encuesta = Prespondido_encuesta AND
	  encuesta_definicion = Pencuesta_definicion
	RETURNING respondido_detalle INTO id_rta;

	IF( id_rta IS NULL) THEN
		INSERT INTO sge_respondido_detalle (respondido_encuesta, encuesta_definicion, respuesta_valor)
		VALUES(Prespondido_encuesta, Pencuesta_definicion, Prespuesta)
		RETURNING respondido_detalle INTO id_rta;
	END IF;
END IF;

RETURN id_rta;
END
$BODY$
  LANGUAGE 'plpgsql' VOLATILE;
ALTER FUNCTION sp_guarda_respuesta_libre(integer, integer, character varying) OWNER TO postgres;
