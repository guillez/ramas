-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_reporte_exportado
-- Actualizacion Nro de Secuencia: sge_reporte_exportado_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_reporte_exportado_seq',(SELECT MAX(exportado_codigo) FROM sge_reporte_exportado));


