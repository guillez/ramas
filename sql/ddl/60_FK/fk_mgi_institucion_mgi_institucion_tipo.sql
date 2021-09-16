-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_institucion
-- FK: fk_mgi_institucion_mgi_institucion_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_institucion_mgi_institucion_tipo;
CREATE INDEX ifk_mgi_institucion_mgi_institucion_tipo ON  mgi_institucion (tipo_institucion);

-- ALTER TABLE mgi_institucion DROP CONSTRAINT fk_mgi_institucion_mgi_institucion_tipo; 
ALTER TABLE mgi_institucion 
	ADD CONSTRAINT fk_mgi_institucion_mgi_institucion_tipo FOREIGN KEY (tipo_institucion) 
	REFERENCES mgi_institucion_tipo (tipo_institucion) deferrable;


