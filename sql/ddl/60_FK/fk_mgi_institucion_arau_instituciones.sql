-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_institucion
-- FK: fk_mgi_institucion_arau_instituciones
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_institucion_arau_instituciones;
CREATE INDEX ifk_mgi_institucion_arau_instituciones ON  mgi_institucion (institucion_araucano);

-- ALTER TABLE mgi_institucion DROP CONSTRAINT fk_mgi_institucion_arau_instituciones; 
ALTER TABLE mgi_institucion 
	ADD CONSTRAINT fk_mgi_institucion_arau_instituciones FOREIGN KEY (institucion_araucano) 
	REFERENCES arau_instituciones (institucion_araucano) deferrable;


