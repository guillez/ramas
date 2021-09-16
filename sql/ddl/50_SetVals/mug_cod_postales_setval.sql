-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_cod_postales
-- Actualizacion Nro de Secuencia: mug_cod_postales_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('mug_cod_postales_seq',(SELECT MAX(id) FROM mug_cod_postales));


