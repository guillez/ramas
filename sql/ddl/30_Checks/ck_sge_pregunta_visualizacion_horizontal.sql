-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_pregunta
-- Check: ck_sge_pregunta_visualizacion_horizontal
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_pregunta DROP CONSTRAINT ck_sge_pregunta_visualizacion_horizontal;
ALTER TABLE sge_pregunta ADD CONSTRAINT ck_sge_pregunta_visualizacion_horizontal CHECK (visualizacion_horizontal IN ('S', 'N'));
