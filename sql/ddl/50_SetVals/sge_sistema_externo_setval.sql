-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_sistema_externo
-- Actualizacion Nro de Secuencia: sge_sistema_externo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_sistema_externo_seq',(SELECT MAX(sistema) FROM sge_sistema_externo));


