-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuestado
-- Check: ck_sge_encuestado_externo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_encuestado DROP CONSTRAINT ck_sge_encuestado_externo;
ALTER TABLE sge_encuestado ADD CONSTRAINT ck_sge_encuestado_externo CHECK (externo IN ('S', 'N'));


