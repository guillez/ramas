-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_evaluacion
-- Actualizacion Nro de Secuencia: sge_evaluacion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_evaluacion_seq',(SELECT MAX(evaluacion) FROM sge_evaluacion));


