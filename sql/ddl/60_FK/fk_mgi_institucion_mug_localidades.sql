-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_institucion
-- FK: fk_mgi_institucion_mug_localidades
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_institucion_mug_localidades;
CREATE INDEX ifk_mgi_institucion_mug_localidades ON  mgi_institucion (localidad);

-- ALTER TABLE mgi_institucion DROP CONSTRAINT fk_mgi_institucion_mug_localidades; 
ALTER TABLE mgi_institucion 
	ADD CONSTRAINT fk_mgi_institucion_mug_localidades FOREIGN KEY (localidad) 
	REFERENCES mug_localidades (localidad) deferrable;


