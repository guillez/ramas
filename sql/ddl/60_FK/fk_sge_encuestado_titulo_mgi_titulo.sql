-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuestado_titulo
-- FK: fk_sge_encuestado_titulo_mgi_titulo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_encuestado_titulo_mgi_titulo;
CREATE INDEX ifk_sge_encuestado_titulo_mgi_titulo ON  sge_encuestado_titulo (titulo);

-- ALTER TABLE sge_encuestado_titulo DROP CONSTRAINT fk_sge_encuestado_titulo_mgi_titulo; 
ALTER TABLE sge_encuestado_titulo 
	ADD CONSTRAINT fk_sge_encuestado_titulo_mgi_titulo FOREIGN KEY (titulo) 
	REFERENCES mgi_titulo (titulo) deferrable;


