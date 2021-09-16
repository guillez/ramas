-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_grupo_definicion
-- Check: ck_sge_grupo_definicion_estado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_grupo_definicion DROP CONSTRAINT ck_sge_grupo_definicion_estado;
ALTER TABLE sge_grupo_definicion ADD CONSTRAINT ck_sge_grupo_definicion_estado CHECK (estado IN ('A', 'B', 'O'));


