-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_respuesta_moderadas
-- Actualizacion Nro de Secuencia: sge_respuesta_moderadas_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_respuesta_moderadas_seq',(SELECT MAX(respuesta_moderada) FROM sge_respuesta_moderadas));


