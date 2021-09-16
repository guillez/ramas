-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_definicion
-- Actualizacion Nro de Secuencia: sge_formulario_definicion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_formulario_definicion_seq',(SELECT MAX(formulario_definicion) FROM sge_formulario_definicion));


