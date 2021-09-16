-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_titulo_propuesta
-- FK: fk_mgi_titulo_propuesta_mgi_titulo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_titulo_propuesta_mgi_titulo;
CREATE INDEX ifk_mgi_titulo_propuesta_mgi_titulo ON  mgi_titulo_propuesta (titulo);

-- ALTER TABLE mgi_titulo_propuesta DROP CONSTRAINT fk_mgi_titulo_propuesta_mgi_titulo; 
ALTER TABLE mgi_titulo_propuesta 
	ADD CONSTRAINT fk_mgi_titulo_propuesta_mgi_titulo FOREIGN KEY (titulo) 
	REFERENCES mgi_titulo (titulo) deferrable;


