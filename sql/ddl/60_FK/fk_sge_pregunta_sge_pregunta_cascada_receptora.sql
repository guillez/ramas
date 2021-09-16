-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_cascada
-- FK: fk_sge_pregunta_sge_pregunta_cascada_receptora
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_sge_pregunta_cascada_receptora;
CREATE INDEX ifk_sge_pregunta_sge_pregunta_cascada_receptora ON  sge_pregunta_cascada (pregunta_receptora);

-- ALTER TABLE sge_pregunta_cascada DROP CONSTRAINT fk_sge_pregunta_sge_pregunta_cascada_receptora; 
ALTER TABLE sge_pregunta_cascada 
	ADD CONSTRAINT fk_sge_pregunta_sge_pregunta_cascada_receptora FOREIGN KEY (pregunta_receptora) 
	REFERENCES sge_pregunta (pregunta) deferrable;


