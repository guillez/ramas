-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_ws_conexion
-- Check: ck_sge_ws_conexion_ws_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_ws_conexion DROP CONSTRAINT ck_sge_ws_conexion_ws_tipo;
ALTER TABLE sge_ws_conexion ADD CONSTRAINT ck_sge_ws_conexion_ws_tipo CHECK (ws_tipo IN ('rest', 'soap'));
