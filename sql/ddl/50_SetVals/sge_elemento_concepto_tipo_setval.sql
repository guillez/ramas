-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_elemento_concepto_tipo
-- Actualizacion Nro de Secuencia: sge_elemento_concepto_tipo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_elemento_concepto_tipo_seq',(SELECT MAX(elemento_concepto) FROM sge_elemento_concepto_tipo));


