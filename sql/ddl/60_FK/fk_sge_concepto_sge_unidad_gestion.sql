-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_concepto
-- FK: fk_sge_concepto_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_concepto_sge_unidad_gestion;
CREATE INDEX ifk_sge_concepto_sge_unidad_gestion ON  sge_concepto (unidad_gestion);

-- ALTER TABLE sge_concepto DROP CONSTRAINT fk_sge_concepto_sge_unidad_gestion; 
ALTER TABLE sge_concepto 
	ADD CONSTRAINT fk_sge_concepto_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


