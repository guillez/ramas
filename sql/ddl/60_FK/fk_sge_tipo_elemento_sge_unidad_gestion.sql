-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tipo_elemento
-- FK: fk_sge_tipo_elemento_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_tipo_elemento_sge_unidad_gestion;
CREATE INDEX ifk_sge_tipo_elemento_sge_unidad_gestion ON  sge_tipo_elemento (unidad_gestion);

-- ALTER TABLE sge_tipo_elemento DROP CONSTRAINT fk_sge_tipo_elemento_sge_unidad_gestion; 
ALTER TABLE sge_tipo_elemento 
	ADD CONSTRAINT fk_sge_tipo_elemento_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


