-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_formulario_habilitado
-- Actualizacion Nro de Secuencia: sge_formulario_habilitado_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_formulario_habilitado_seq',(SELECT MAX(formulario_habilitado) FROM sge_formulario_habilitado));


