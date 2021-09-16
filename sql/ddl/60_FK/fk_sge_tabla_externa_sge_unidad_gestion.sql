-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tabla_externa
-- FK: fk_sge_tabla_externa_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_tabla_externa_sge_unidad_gestion;
CREATE INDEX ifk_sge_tabla_externa_sge_unidad_gestion ON  sge_tabla_externa (unidad_gestion);

-- ALTER TABLE sge_tabla_externa DROP CONSTRAINT fk_sge_tabla_externa_sge_unidad_gestion; 
ALTER TABLE sge_tabla_externa 
	ADD CONSTRAINT fk_sge_tabla_externa_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion);


