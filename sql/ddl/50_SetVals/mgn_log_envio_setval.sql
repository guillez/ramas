-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_log_envio
-- Actualizacion Nro de Secuencia: mgn_log_envio_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('mgn_log_envio_seq',(SELECT MAX(log) FROM mgn_log_envio));


