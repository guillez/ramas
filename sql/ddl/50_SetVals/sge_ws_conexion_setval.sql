-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_ws_conexion
-- Actualizacion Nro de Secuencia: sge_ws_conexion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_ws_conexion_seq',(SELECT MAX(conexion) FROM sge_ws_conexion));


