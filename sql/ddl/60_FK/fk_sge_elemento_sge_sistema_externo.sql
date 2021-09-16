-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_elemento
-- FK: fk_sge_elemento_sge_sistema_externo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_elemento_sge_sistema_externo;
CREATE INDEX ifk_sge_elemento_sge_sistema_externo ON  sge_elemento (sistema);

-- ALTER TABLE sge_elemento DROP CONSTRAINT fk_sge_elemento_sge_sistema_externo; 
ALTER TABLE sge_elemento 
	ADD CONSTRAINT fk_sge_elemento_sge_sistema_externo FOREIGN KEY (sistema) 
	REFERENCES sge_sistema_externo (sistema) deferrable;


