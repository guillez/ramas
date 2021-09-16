-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_titulo_ra
-- FK: fk_mgi_titulo_ra_mgi_responsable_academica
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_titulo_ra_mgi_responsable_academica;
CREATE INDEX ifk_mgi_titulo_ra_mgi_responsable_academica ON  mgi_titulo_ra (responsable_academica);

-- ALTER TABLE mgi_titulo_ra DROP CONSTRAINT fk_mgi_titulo_ra_mgi_responsable_academica; 
ALTER TABLE mgi_titulo_ra 
	ADD CONSTRAINT fk_mgi_titulo_ra_mgi_responsable_academica FOREIGN KEY (responsable_academica) 
	REFERENCES mgi_responsable_academica (responsable_academica) deferrable;


