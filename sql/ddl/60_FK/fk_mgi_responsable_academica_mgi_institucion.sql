-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_responsable_academica
-- FK: fk_mgi_responsable_academica_mgi_institucion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_responsable_academica_mgi_institucion;
CREATE INDEX ifk_mgi_responsable_academica_mgi_institucion ON  mgi_responsable_academica (institucion);

-- ALTER TABLE mgi_responsable_academica DROP CONSTRAINT fk_mgi_responsable_academica_mgi_institucion; 
ALTER TABLE mgi_responsable_academica 
	ADD CONSTRAINT fk_mgi_responsable_academica_mgi_institucion FOREIGN KEY (institucion) 
	REFERENCES mgi_institucion (institucion) deferrable;


