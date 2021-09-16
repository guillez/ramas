-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_pregunta
-- FK: fk_sge_puntaje_pregunta_sge_encuesta_definicion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_puntaje_pregunta_sge_encuesta_definicion;
CREATE INDEX ifk_sge_puntaje_pregunta_sge_encuesta_definicion ON  sge_puntaje_pregunta (encuesta_definicion);

-- ALTER TABLE sge_puntaje_pregunta DROP CONSTRAINT fk_sge_puntaje_pregunta_sge_encuesta_definicion; 
ALTER TABLE sge_puntaje_pregunta 
	ADD CONSTRAINT fk_sge_puntaje_pregunta_sge_encuesta_definicion FOREIGN KEY (encuesta_definicion) 
	REFERENCES sge_encuesta_definicion (encuesta_definicion) deferrable;


