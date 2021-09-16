-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_carrera
-- FK: fk_int_guarani_carrera_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_int_guarani_carrera_sge_unidad_gestion;
CREATE INDEX ifk_int_guarani_carrera_sge_unidad_gestion ON  int_guarani_carrera (unidad_gestion);

-- ALTER TABLE int_guarani_carrera DROP CONSTRAINT fk_int_guarani_carrera_sge_unidad_gestion; 
ALTER TABLE int_guarani_carrera 
	ADD CONSTRAINT fk_int_guarani_carrera_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


