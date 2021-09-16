-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_propuesta_ra
-- FK: fk_mgi_propuesta_ra_mgi_responsable_academica
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_propuesta_ra_mgi_responsable_academica;
CREATE INDEX ifk_mgi_propuesta_ra_mgi_responsable_academica ON  mgi_propuesta_ra (responsable_academica);

-- ALTER TABLE mgi_propuesta_ra DROP CONSTRAINT fk_mgi_propuesta_ra_mgi_responsable_academica; 
ALTER TABLE mgi_propuesta_ra 
	ADD CONSTRAINT fk_mgi_propuesta_ra_mgi_responsable_academica FOREIGN KEY (responsable_academica) 
	REFERENCES mgi_responsable_academica (responsable_academica) deferrable;


