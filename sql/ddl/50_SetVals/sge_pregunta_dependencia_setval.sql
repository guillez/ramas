-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_pregunta_dependencia
-- Actualizacion Nro de Secuencia: sge_pregunta_dependencia_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_pregunta_dependencia_seq',(SELECT MAX(pregunta_dependencia) FROM sge_pregunta_dependencia));


