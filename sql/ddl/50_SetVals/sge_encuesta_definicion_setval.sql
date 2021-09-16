-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_definicion
-- Actualizacion Nro de Secuencia: sge_encuesta_definicion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_encuesta_definicion_seq',(SELECT MAX(encuesta_definicion) FROM sge_encuesta_definicion));


