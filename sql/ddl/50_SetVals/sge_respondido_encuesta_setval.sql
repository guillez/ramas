-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuesta
-- Actualizacion Nro de Secuencia: sge_respondido_encuesta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_respondido_encuesta_seq',(SELECT MAX(respondido_encuesta) FROM sge_respondido_encuesta));


