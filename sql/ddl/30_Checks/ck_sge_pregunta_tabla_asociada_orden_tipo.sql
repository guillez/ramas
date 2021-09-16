-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta
-- Check: ck_sge_pregunta_tabla_asociada_orden_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_pregunta DROP CONSTRAINT ck_sge_pregunta_tabla_asociada_orden_tipo;
ALTER TABLE sge_pregunta ADD CONSTRAINT ck_sge_pregunta_tabla_asociada_orden_tipo CHECK (tabla_asociada_orden_tipo IN ('ASC', 'DESC'));


