-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_cascada
-- FK: fk_sge_pregunta_sge_pregunta_cascada_disparadora
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_sge_pregunta_cascada_disparadora;
CREATE INDEX ifk_sge_pregunta_sge_pregunta_cascada_disparadora ON  sge_pregunta_cascada (pregunta_disparadora);

-- ALTER TABLE sge_pregunta_cascada DROP CONSTRAINT fk_sge_pregunta_sge_pregunta_cascada_disparadora; 
ALTER TABLE sge_pregunta_cascada 
	ADD CONSTRAINT fk_sge_pregunta_sge_pregunta_cascada_disparadora FOREIGN KEY (pregunta_disparadora) 
	REFERENCES sge_pregunta (pregunta) deferrable;


