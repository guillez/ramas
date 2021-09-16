-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_atributo
-- Check: ck_sge_formulario_atributo_estado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_formulario_atributo DROP CONSTRAINT ck_sge_formulario_atributo_estado;
ALTER TABLE sge_formulario_atributo ADD CONSTRAINT ck_sge_formulario_atributo_estado CHECK (estado IN ('A', 'I'));
