-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_ws_conexion
-- Actualizacion Nro de Secuencia: sge_ws_conexion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_ws_conexion_seq',(SELECT MAX(conexion) FROM sge_ws_conexion));


