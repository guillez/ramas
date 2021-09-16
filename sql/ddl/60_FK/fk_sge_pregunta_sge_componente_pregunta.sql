-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta
-- FK: fk_sge_pregunta_sge_componente_pregunta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_sge_componente_pregunta;
CREATE INDEX ifk_sge_pregunta_sge_componente_pregunta ON  sge_pregunta (componente_numero);

-- ALTER TABLE sge_pregunta DROP CONSTRAINT fk_sge_pregunta_sge_componente_pregunta; 
ALTER TABLE sge_pregunta 
	ADD CONSTRAINT fk_sge_pregunta_sge_componente_pregunta FOREIGN KEY (componente_numero) 
	REFERENCES sge_componente_pregunta (numero) deferrable;


