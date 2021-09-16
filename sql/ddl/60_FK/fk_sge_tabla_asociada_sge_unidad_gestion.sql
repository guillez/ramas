-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tabla_asociada
-- FK: fk_sge_tabla_asociada_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_tabla_asociada_sge_unidad_gestion;
CREATE INDEX ifk_sge_tabla_asociada_sge_unidad_gestion ON  sge_tabla_asociada (unidad_gestion);

-- ALTER TABLE sge_tabla_asociada DROP CONSTRAINT fk_sge_tabla_asociada_sge_unidad_gestion; 
ALTER TABLE sge_tabla_asociada 
	ADD CONSTRAINT fk_sge_tabla_asociada_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


