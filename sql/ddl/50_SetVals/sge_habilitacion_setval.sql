-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_habilitacion
-- Actualizacion Nro de Secuencia: sge_habilitacion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_habilitacion_seq',(SELECT MAX(habilitacion) FROM sge_habilitacion));


