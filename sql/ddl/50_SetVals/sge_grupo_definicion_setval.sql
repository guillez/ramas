-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_grupo_definicion
-- Actualizacion Nro de Secuencia: sge_grupo_definicion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_grupo_definicion_seq',(SELECT MAX(grupo) FROM sge_grupo_definicion));


