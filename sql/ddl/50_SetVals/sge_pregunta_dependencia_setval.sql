-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_dependencia
-- Actualizacion Nro de Secuencia: sge_pregunta_dependencia_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_pregunta_dependencia_seq',(SELECT MAX(pregunta_dependencia) FROM sge_pregunta_dependencia));


