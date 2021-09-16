-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tabla_asociada
-- Actualizacion Nro de Secuencia: sge_tabla_asociada_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_tabla_asociada_seq',(SELECT MAX(tabla_asociada) FROM sge_tabla_asociada));


