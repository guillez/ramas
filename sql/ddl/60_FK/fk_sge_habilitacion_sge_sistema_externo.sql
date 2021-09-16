-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_habilitacion
-- FK: fk_sge_habilitacion_sge_sistema_externo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_habilitacion_sge_sistema_externo;
CREATE INDEX ifk_sge_habilitacion_sge_sistema_externo ON  sge_habilitacion (sistema);

-- ALTER TABLE sge_habilitacion DROP CONSTRAINT fk_sge_habilitacion_sge_sistema_externo; 
ALTER TABLE sge_habilitacion 
	ADD CONSTRAINT fk_sge_habilitacion_sge_sistema_externo FOREIGN KEY (sistema) 
	REFERENCES sge_sistema_externo (sistema) deferrable;


