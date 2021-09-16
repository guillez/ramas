-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tipo_elemento
-- Actualizacion Nro de Secuencia: sge_tipo_elemento_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_tipo_elemento_seq',(SELECT MAX(tipo_elemento) FROM sge_tipo_elemento));


