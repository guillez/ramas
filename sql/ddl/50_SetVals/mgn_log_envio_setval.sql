-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: mgn_log_envio
-- Actualizacion Nro de Secuencia: mgn_log_envio_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('mgn_log_envio_seq',(SELECT MAX(log) FROM mgn_log_envio));


