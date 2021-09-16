-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_componente_pregunta
-- Actualizacion Nro de Secuencia: sge_componente_pregunta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_componente_pregunta_seq',(SELECT MAX(numero) FROM sge_componente_pregunta));


