-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: mug_cod_postales
-- Actualizacion Nro de Secuencia: mug_cod_postales_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('mug_cod_postales_seq',(SELECT MAX(id) FROM mug_cod_postales));


