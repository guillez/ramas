-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_elemento_concepto_tipo
-- FK: fk_sge_elemento_concepto_tipo_sge_concepto
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_elemento_concepto_tipo_sge_concepto;
CREATE INDEX ifk_sge_elemento_concepto_tipo_sge_concepto ON  sge_elemento_concepto_tipo (concepto);

-- ALTER TABLE sge_elemento_concepto_tipo DROP CONSTRAINT fk_sge_elemento_concepto_tipo_sge_concepto; 
ALTER TABLE sge_elemento_concepto_tipo 
	ADD CONSTRAINT fk_sge_elemento_concepto_tipo_sge_concepto FOREIGN KEY (concepto) 
	REFERENCES sge_concepto (concepto) deferrable;


