-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_respuesta
-- FK: fk_sge_pregunta_respuesta_sge_respuesta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_respuesta_sge_respuesta;
CREATE INDEX ifk_sge_pregunta_respuesta_sge_respuesta ON  sge_pregunta_respuesta (respuesta);

-- ALTER TABLE sge_pregunta_respuesta DROP CONSTRAINT fk_sge_pregunta_respuesta_sge_respuesta; 
ALTER TABLE sge_pregunta_respuesta 
	ADD CONSTRAINT fk_sge_pregunta_respuesta_sge_respuesta FOREIGN KEY (respuesta) 
	REFERENCES sge_respuesta (respuesta) deferrable;


