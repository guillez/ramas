-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_dependencia_definicion
-- Actualizacion Nro de Secuencia: sge_pregunta_dependencia_definicion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_pregunta_dependencia_definicion_seq',(SELECT MAX(dependencia_definicion) FROM sge_pregunta_dependencia_definicion));


