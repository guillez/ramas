-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_respuesta
-- Actualizacion Nro de Secuencia: sge_puntaje_respuesta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_puntaje_respuesta_seq',(SELECT MAX(puntaje_respuesta) FROM sge_puntaje_respuesta));


