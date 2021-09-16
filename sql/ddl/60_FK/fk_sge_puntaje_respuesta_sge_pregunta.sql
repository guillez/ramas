-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_respuesta
-- FK: fk_sge_puntaje_respuesta_sge_pregunta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_puntaje_respuesta_sge_pregunta;
CREATE INDEX ifk_sge_puntaje_respuesta_sge_pregunta ON  sge_puntaje_respuesta (pregunta);

-- ALTER TABLE sge_puntaje_respuesta DROP CONSTRAINT fk_sge_puntaje_respuesta_sge_pregunta; 
ALTER TABLE sge_puntaje_respuesta 
	ADD CONSTRAINT fk_sge_puntaje_respuesta_sge_pregunta FOREIGN KEY (pregunta) 
	REFERENCES sge_pregunta (pregunta) deferrable;


