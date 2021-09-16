-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_pregunta
-- Actualizacion Nro de Secuencia: sge_puntaje_pregunta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_puntaje_pregunta_seq',(SELECT MAX(puntaje_pregunta) FROM sge_puntaje_pregunta));


