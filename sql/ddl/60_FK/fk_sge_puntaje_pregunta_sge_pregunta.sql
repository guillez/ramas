-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_pregunta
-- FK: fk_sge_puntaje_pregunta_sge_pregunta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_puntaje_pregunta_sge_pregunta;
CREATE INDEX ifk_sge_puntaje_pregunta_sge_pregunta ON  sge_puntaje_pregunta (pregunta);

-- ALTER TABLE sge_puntaje_pregunta DROP CONSTRAINT fk_sge_puntaje_pregunta_sge_pregunta; 
ALTER TABLE sge_puntaje_pregunta 
	ADD CONSTRAINT fk_sge_puntaje_pregunta_sge_pregunta FOREIGN KEY (pregunta) 
	REFERENCES sge_pregunta (pregunta) deferrable;


