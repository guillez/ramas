-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_respondido_encuesta
-- Actualizacion Nro de Secuencia: sge_respondido_encuesta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_respondido_encuesta_seq',(SELECT MAX(respondido_encuesta) FROM sge_respondido_encuesta));


