-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_titulo
-- FK: fk_mgi_titulo_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_titulo_sge_unidad_gestion;
CREATE INDEX ifk_mgi_titulo_sge_unidad_gestion ON  mgi_titulo (unidad_gestion);

-- ALTER TABLE mgi_titulo DROP CONSTRAINT fk_mgi_titulo_sge_unidad_gestion; 
ALTER TABLE mgi_titulo 
	ADD CONSTRAINT fk_mgi_titulo_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


