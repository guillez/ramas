-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mdi_pueblo_originario
-- Actualizacion Nro de Secuencia: mdi_pueblo_originario_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('mdi_pueblo_originario_seq',(SELECT MAX(pueblo_originario) FROM mdi_pueblo_originario));


