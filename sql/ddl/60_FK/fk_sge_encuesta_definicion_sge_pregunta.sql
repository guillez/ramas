-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_definicion
-- FK: fk_sge_encuesta_definicion_sge_pregunta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_encuesta_definicion_sge_pregunta;
CREATE INDEX ifk_sge_encuesta_definicion_sge_pregunta ON  sge_encuesta_definicion (pregunta);

-- ALTER TABLE sge_encuesta_definicion DROP CONSTRAINT fk_sge_encuesta_definicion_sge_pregunta; 
ALTER TABLE sge_encuesta_definicion 
	ADD CONSTRAINT fk_sge_encuesta_definicion_sge_pregunta FOREIGN KEY (pregunta) 
	REFERENCES sge_pregunta (pregunta) deferrable;


