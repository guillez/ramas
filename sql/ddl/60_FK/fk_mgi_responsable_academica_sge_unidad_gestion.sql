-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_responsable_academica
-- FK: fk_mgi_responsable_academica_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_responsable_academica_sge_unidad_gestion;
CREATE INDEX ifk_mgi_responsable_academica_sge_unidad_gestion ON  mgi_responsable_academica (unidad_gestion);

-- ALTER TABLE mgi_responsable_academica DROP CONSTRAINT fk_mgi_responsable_academica_sge_unidad_gestion; 
ALTER TABLE mgi_responsable_academica 
	ADD CONSTRAINT fk_mgi_responsable_academica_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


