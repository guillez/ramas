-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_elemento_concepto_tipo
-- Actualizacion Nro de Secuencia: sge_elemento_concepto_tipo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_elemento_concepto_tipo_seq',(SELECT MAX(elemento_concepto) FROM sge_elemento_concepto_tipo));


