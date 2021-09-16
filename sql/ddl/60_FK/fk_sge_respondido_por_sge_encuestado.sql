-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_por
-- FK: fk_sge_respondido_por_sge_encuestado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respondido_por_sge_encuestado;
CREATE INDEX ifk_sge_respondido_por_sge_encuestado ON  sge_respondido_por (encuestado);

-- ALTER TABLE sge_respondido_por DROP CONSTRAINT fk_sge_respondido_por_sge_encuestado; 
ALTER TABLE sge_respondido_por 
	ADD CONSTRAINT fk_sge_respondido_por_sge_encuestado FOREIGN KEY (encuestado) 
	REFERENCES sge_encuestado (encuestado) deferrable;


