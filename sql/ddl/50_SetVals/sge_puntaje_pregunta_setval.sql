-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_puntaje_pregunta
-- Actualizacion Nro de Secuencia: sge_puntaje_pregunta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_puntaje_pregunta_seq',(SELECT MAX(puntaje_pregunta) FROM sge_puntaje_pregunta));


