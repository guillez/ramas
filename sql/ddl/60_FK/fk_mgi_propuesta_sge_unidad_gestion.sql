-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_propuesta
-- FK: fk_mgi_propuesta_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_propuesta_sge_unidad_gestion;
CREATE INDEX ifk_mgi_propuesta_sge_unidad_gestion ON  mgi_propuesta (unidad_gestion);

-- ALTER TABLE mgi_propuesta DROP CONSTRAINT fk_mgi_propuesta_sge_unidad_gestion; 
ALTER TABLE mgi_propuesta 
	ADD CONSTRAINT fk_mgi_propuesta_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


