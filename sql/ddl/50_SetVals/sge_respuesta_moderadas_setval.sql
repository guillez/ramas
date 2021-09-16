-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respuesta_moderadas
-- Actualizacion Nro de Secuencia: sge_respuesta_moderadas_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_respuesta_moderadas_seq',(SELECT MAX(respuesta_moderada) FROM sge_respuesta_moderadas));


