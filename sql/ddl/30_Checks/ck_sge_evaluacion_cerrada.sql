-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_evaluacion
-- Check: ck_sge_evaluacion_cerrada
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_evaluacion DROP CONSTRAINT ck_sge_evaluacion_cerrada;
ALTER TABLE sge_evaluacion ADD CONSTRAINT ck_sge_evaluacion_cerrada CHECK (cerrada IN ('N','S'));

