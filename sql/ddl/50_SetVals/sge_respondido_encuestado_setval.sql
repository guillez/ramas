-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuestado
-- Actualizacion Nro de Secuencia: sge_respondido_encuestado_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_respondido_encuestado_seq',(SELECT MAX(respondido_encuestado) FROM sge_respondido_encuestado));


