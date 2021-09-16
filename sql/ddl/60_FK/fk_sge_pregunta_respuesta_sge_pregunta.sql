-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_respuesta
-- FK: fk_sge_pregunta_respuesta_sge_pregunta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_respuesta_sge_pregunta;
CREATE INDEX ifk_sge_pregunta_respuesta_sge_pregunta ON  sge_pregunta_respuesta (pregunta);

-- ALTER TABLE sge_pregunta_respuesta DROP CONSTRAINT fk_sge_pregunta_respuesta_sge_pregunta; 
ALTER TABLE sge_pregunta_respuesta 
	ADD CONSTRAINT fk_sge_pregunta_respuesta_sge_pregunta FOREIGN KEY (pregunta) 
	REFERENCES sge_pregunta (pregunta) deferrable;


