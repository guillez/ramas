-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_responsable_academica
-- FK: fk_mgi_responsable_academica_mug_localidades
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_responsable_academica_mug_localidades;
CREATE INDEX ifk_mgi_responsable_academica_mug_localidades ON  mgi_responsable_academica (localidad);

-- ALTER TABLE mgi_responsable_academica DROP CONSTRAINT fk_mgi_responsable_academica_mug_localidades; 
ALTER TABLE mgi_responsable_academica 
	ADD CONSTRAINT fk_mgi_responsable_academica_mug_localidades FOREIGN KEY (localidad) 
	REFERENCES mug_localidades (localidad) deferrable;


