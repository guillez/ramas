-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado
-- Check: ck_sge_formulario_habilitado_estado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_formulario_habilitado DROP CONSTRAINT ck_sge_formulario_habilitado_estado;
ALTER TABLE sge_formulario_habilitado ADD CONSTRAINT ck_sge_formulario_habilitado_estado CHECK (estado IN ('A', 'B'));

