-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_evaluacion
-- Actualizacion Nro de Secuencia: sge_evaluacion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_evaluacion_seq',(SELECT MAX(evaluacion) FROM sge_evaluacion));


