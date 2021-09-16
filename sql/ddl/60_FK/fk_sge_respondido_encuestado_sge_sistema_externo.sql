-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuestado
-- FK: fk_sge_respondido_encuestado_sge_sistema_externo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respondido_encuestado_sge_sistema_externo;
CREATE INDEX ifk_sge_respondido_encuestado_sge_sistema_externo ON  sge_respondido_encuestado (sistema);

-- ALTER TABLE sge_respondido_encuestado DROP CONSTRAINT fk_sge_respondido_encuestado_sge_sistema_externo; 
ALTER TABLE sge_respondido_encuestado 
	ADD CONSTRAINT fk_sge_respondido_encuestado_sge_sistema_externo FOREIGN KEY (sistema) 
	REFERENCES sge_sistema_externo (sistema) deferrable;


