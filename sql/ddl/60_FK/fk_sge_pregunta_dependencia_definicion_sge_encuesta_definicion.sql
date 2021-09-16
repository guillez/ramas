-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_dependencia_definicion
-- FK: fk_sge_pregunta_dependencia_definicion_sge_encuesta_definicion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_dependencia_definicion_sge_encuesta_definicion;
CREATE INDEX ifk_sge_pregunta_dependencia_definicion_sge_encuesta_definicion ON  sge_pregunta_dependencia_definicion (encuesta_definicion);

-- ALTER TABLE sge_pregunta_dependencia_definicion DROP CONSTRAINT fk_sge_pregunta_dependencia_definicion_sge_encuesta_definicion; 
ALTER TABLE sge_pregunta_dependencia_definicion 
	ADD CONSTRAINT fk_sge_pregunta_dependencia_definicion_sge_encuesta_definicion FOREIGN KEY (encuesta_definicion) 
	REFERENCES sge_encuesta_definicion (encuesta_definicion) deferrable;


