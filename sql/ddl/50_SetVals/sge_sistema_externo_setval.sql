-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_sistema_externo
-- Actualizacion Nro de Secuencia: sge_sistema_externo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_sistema_externo_seq',(SELECT MAX(sistema) FROM sge_sistema_externo));


