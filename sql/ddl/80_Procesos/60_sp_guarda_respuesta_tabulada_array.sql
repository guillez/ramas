-- Function: sp_guarda_respuesta_tabulada_array(integer, integer, integer[])

-- DROP FUNCTION sp_guarda_respuesta_tabulada_array(integer, integer, integer[]);

CREATE OR REPLACE FUNCTION sp_guarda_respuesta_tabulada_array
	(Prespondido_encuesta integer, Pencuesta_definicion integer, Prespuesta character varying)
  RETURNS integer AS
$BODY$
DECLARE 

array_aux _int4;

BEGIN
	array_aux =	Prespuesta; --"transformo" el texto en arreglo-> PHP no anda bien con arreglos
    DELETE FROM sge_respondido_detalle
    WHERE 
       respondido_encuesta = Prespondido_encuesta AND
	   encuesta_definicion = Pencuesta_definicion;
    
	IF (array_upper(array_aux, 1) IS NOT NULL) THEN -- es vacio
		FOR i IN 1 .. array_upper(array_aux, 1) LOOP
			INSERT INTO sge_respondido_detalle (respondido_encuesta, encuesta_definicion, respuesta_codigo)
				VALUES(Prespondido_encuesta, Pencuesta_definicion, array_aux[i]); 
		END LOOP;
	END IF; 

RETURN (0);
END
$BODY$
  LANGUAGE 'plpgsql' VOLATILE;
ALTER FUNCTION sp_guarda_respuesta_tabulada_array(integer, integer, character varying) OWNER TO postgres;
