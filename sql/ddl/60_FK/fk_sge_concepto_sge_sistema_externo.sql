-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_concepto
-- FK: fk_sge_concepto_sge_sistema_externo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_concepto_sge_sistema_externo;
CREATE INDEX ifk_sge_concepto_sge_sistema_externo ON  sge_concepto (sistema);

-- ALTER TABLE sge_concepto DROP CONSTRAINT fk_sge_concepto_sge_sistema_externo; 
ALTER TABLE sge_concepto 
	ADD CONSTRAINT fk_sge_concepto_sge_sistema_externo FOREIGN KEY (sistema) 
	REFERENCES sge_sistema_externo (sistema) deferrable;


