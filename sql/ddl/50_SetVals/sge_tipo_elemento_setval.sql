-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_tipo_elemento
-- Actualizacion Nro de Secuencia: sge_tipo_elemento_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_tipo_elemento_seq',(SELECT MAX(tipo_elemento) FROM sge_tipo_elemento));


