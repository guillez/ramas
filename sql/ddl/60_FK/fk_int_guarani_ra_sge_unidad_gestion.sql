-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: int_guarani_ra
-- FK: fk_int_guarani_ra_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_int_guarani_ra_sge_unidad_gestion;
CREATE INDEX ifk_int_guarani_ra_sge_unidad_gestion ON  int_guarani_ra (unidad_gestion);

-- ALTER TABLE int_guarani_ra DROP CONSTRAINT fk_int_guarani_ra_sge_unidad_gestion; 
ALTER TABLE int_guarani_ra 
	ADD CONSTRAINT fk_int_guarani_ra_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


