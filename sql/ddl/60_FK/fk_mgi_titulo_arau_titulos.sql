-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_titulo
-- FK: fk_mgi_titulo_arau_titulos
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_titulo_arau_titulos;
CREATE INDEX ifk_mgi_titulo_arau_titulos ON  mgi_titulo (titulo_araucano);

-- ALTER TABLE mgi_titulo DROP CONSTRAINT fk_mgi_titulo_arau_titulos; 
ALTER TABLE mgi_titulo 
	ADD CONSTRAINT fk_mgi_titulo_arau_titulos FOREIGN KEY (titulo_araucano) 
	REFERENCES arau_titulos (titulo_araucano) deferrable;


