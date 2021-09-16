-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_titulos
-- FK: fk_int_guarani_titulos_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_int_guarani_titulos_sge_unidad_gestion;
CREATE INDEX ifk_int_guarani_titulos_sge_unidad_gestion ON  int_guarani_titulos (unidad_gestion);

-- ALTER TABLE int_guarani_titulos DROP CONSTRAINT fk_int_guarani_titulos_sge_unidad_gestion; 
ALTER TABLE int_guarani_titulos 
	ADD CONSTRAINT fk_int_guarani_titulos_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


