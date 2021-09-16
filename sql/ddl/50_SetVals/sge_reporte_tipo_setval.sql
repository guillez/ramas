-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_reporte_tipo
-- Actualizacion Nro de Secuencia: sge_reporte_tipo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_reporte_tipo_seq',(SELECT MAX(reporte_tipo) FROM sge_reporte_tipo));


