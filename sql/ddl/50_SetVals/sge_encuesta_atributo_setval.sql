-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_encuesta_atributo
-- Actualizacion Nro de Secuencia: sge_encuesta_atributo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_encuesta_atributo_seq',(SELECT MAX(encuesta) FROM sge_encuesta_atributo));


