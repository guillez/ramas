-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuestado
-- FK: fk_sge_respondido_encuestado_sge_encuestado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respondido_encuestado_sge_encuestado;
CREATE INDEX ifk_sge_respondido_encuestado_sge_encuestado ON  sge_respondido_encuestado (encuestado);

-- ALTER TABLE sge_respondido_encuestado DROP CONSTRAINT fk_sge_respondido_encuestado_sge_encuestado; 
ALTER TABLE sge_respondido_encuestado 
	ADD CONSTRAINT fk_sge_respondido_encuestado_sge_encuestado FOREIGN KEY (encuestado) 
	REFERENCES sge_encuestado (encuestado) deferrable;


