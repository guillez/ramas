-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_responsable_academica
-- FK: fk_mgi_responsable_academica_arau_responsables_academicas
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_responsable_academica_arau_responsables_academicas;
CREATE INDEX ifk_mgi_responsable_academica_arau_responsables_academicas ON  mgi_responsable_academica (ra_araucano);

-- ALTER TABLE mgi_responsable_academica DROP CONSTRAINT fk_mgi_responsable_academica_arau_responsables_academicas; 
ALTER TABLE mgi_responsable_academica 
	ADD CONSTRAINT fk_mgi_responsable_academica_arau_responsables_academicas FOREIGN KEY (ra_araucano) 
	REFERENCES arau_responsables_academicas (ra_araucano) deferrable;


