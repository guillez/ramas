-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_respondido_encuestado
-- Actualizacion Nro de Secuencia: sge_respondido_encuestado_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_respondido_encuestado_seq',(SELECT MAX(respondido_encuestado) FROM sge_respondido_encuestado));


