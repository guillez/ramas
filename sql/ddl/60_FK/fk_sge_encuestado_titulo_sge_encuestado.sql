-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuestado_titulo
-- FK: fk_sge_encuestado_titulo_sge_encuestado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_encuestado_titulo_sge_encuestado;
CREATE INDEX ifk_sge_encuestado_titulo_sge_encuestado ON  sge_encuestado_titulo (encuestado);

-- ALTER TABLE sge_encuestado_titulo DROP CONSTRAINT fk_sge_encuestado_titulo_sge_encuestado; 
ALTER TABLE sge_encuestado_titulo 
	ADD CONSTRAINT fk_sge_encuestado_titulo_sge_encuestado FOREIGN KEY (encuestado) 
	REFERENCES sge_encuestado (encuestado) deferrable;


