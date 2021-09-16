-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: mgi_titulo_propuesta
-- FK: fk_mgi_titulo_propuesta_mgi_propuesta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_titulo_propuesta_mgi_propuesta;
CREATE INDEX ifk_mgi_titulo_propuesta_mgi_propuesta ON  mgi_titulo_propuesta (propuesta);

-- ALTER TABLE mgi_titulo_propuesta DROP CONSTRAINT fk_mgi_titulo_propuesta_mgi_propuesta; 
ALTER TABLE mgi_titulo_propuesta 
	ADD CONSTRAINT fk_mgi_titulo_propuesta_mgi_propuesta FOREIGN KEY (propuesta) 
	REFERENCES mgi_propuesta (propuesta) deferrable;


