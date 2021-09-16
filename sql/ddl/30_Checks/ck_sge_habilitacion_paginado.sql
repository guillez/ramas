-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_habilitacion
-- Check: ck_sge_habilitacion_paginado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_habilitacion DROP CONSTRAINT ck_sge_habilitacion_paginado;
ALTER TABLE sge_habilitacion ADD CONSTRAINT ck_sge_habilitacion_paginado CHECK (paginado IN ('S', 'N'));
