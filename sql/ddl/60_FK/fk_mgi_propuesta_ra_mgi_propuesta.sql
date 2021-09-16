-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_propuesta_ra
-- FK: fk_mgi_propuesta_ra_mgi_propuesta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_propuesta_ra_mgi_propuesta;
CREATE INDEX ifk_mgi_propuesta_ra_mgi_propuesta ON  mgi_propuesta_ra (propuesta);

-- ALTER TABLE mgi_propuesta_ra DROP CONSTRAINT fk_mgi_propuesta_ra_mgi_propuesta; 
ALTER TABLE mgi_propuesta_ra 
	ADD CONSTRAINT fk_mgi_propuesta_ra_mgi_propuesta FOREIGN KEY (propuesta) 
	REFERENCES mgi_propuesta (propuesta) deferrable;


