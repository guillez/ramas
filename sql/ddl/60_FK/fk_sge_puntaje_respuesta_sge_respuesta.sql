-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_respuesta
-- FK: fk_sge_puntaje_respuesta_sge_respuesta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_puntaje_respuesta_sge_respuesta;
CREATE INDEX ifk_sge_puntaje_respuesta_sge_respuesta ON  sge_puntaje_respuesta (respuesta);

-- ALTER TABLE sge_puntaje_respuesta DROP CONSTRAINT fk_sge_puntaje_respuesta_sge_respuesta; 
ALTER TABLE sge_puntaje_respuesta 
	ADD CONSTRAINT fk_sge_puntaje_respuesta_sge_respuesta FOREIGN KEY (respuesta) 
	REFERENCES sge_respuesta (respuesta) deferrable;


