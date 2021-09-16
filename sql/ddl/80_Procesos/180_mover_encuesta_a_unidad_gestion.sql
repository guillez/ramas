--
-- FUNCION PARA MOVER UNA ENCUESTA A OTRA UNIDAD DE GESTION.
--

CREATE OR REPLACE FUNCTION mover_encuesta_a_unidad_gestion(_encuesta integer,_unidad_gestion character varying)
  RETURNS INTEGER AS

$BODY$

BEGIN
SET search_path TO kolla;

-- sge_pregunta

UPDATE	sge_pregunta
SET 	unidad_gestion = _unidad_gestion
WHERE	sge_pregunta.pregunta IN (
	SELECT	sge_encuesta_definicion.pregunta
	FROM	sge_encuesta_definicion
	WHERE	sge_encuesta_definicion.encuesta = _encuesta);
	
-- sge_respuesta

UPDATE	sge_respuesta
SET 	unidad_gestion = _unidad_gestion
WHERE	sge_respuesta.respuesta IN (
	SELECT	sge_pregunta_respuesta.respuesta
	FROM	sge_pregunta_respuesta,
		sge_encuesta_definicion
	WHERE	sge_pregunta_respuesta.pregunta = sge_encuesta_definicion.pregunta
	AND	sge_encuesta_definicion.encuesta = _encuesta);

-- sge_encuesta_atributo

UPDATE sge_encuesta_atributo SET unidad_gestion = _unidad_gestion WHERE encuesta = _encuesta;

RETURN 1;

END;

$BODY$
LANGUAGE plpgsql VOLATILE;