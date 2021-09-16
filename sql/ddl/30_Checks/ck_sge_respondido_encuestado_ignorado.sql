-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuestado
-- Check: ck_sge_respondido_encuestado_ignorado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_respondido_encuestado DROP CONSTRAINT ck_sge_respondido_encuestado_ignorado;
ALTER TABLE sge_respondido_encuestado ADD CONSTRAINT ck_sge_respondido_encuestado_ignorado CHECK (ignorado  IN ('S', 'N'));
