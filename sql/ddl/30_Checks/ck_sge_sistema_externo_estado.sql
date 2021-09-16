-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_sistema_externo
-- Check: ck_sge_sistema_externo_estado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_sistema_externo DROP CONSTRAINT ck_sge_sistema_externo_estado;
ALTER TABLE sge_sistema_externo ADD CONSTRAINT ck_sge_sistema_externo_estado CHECK (estado IN ('A', 'B'));
