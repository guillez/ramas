-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_componente_pregunta
-- Check: ck_sge_componente_pregunta_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_componente_pregunta DROP CONSTRAINT ck_sge_componente_pregunta_tipo;
ALTER TABLE sge_componente_pregunta ADD CONSTRAINT ck_sge_componente_pregunta_tipo CHECK (tipo IN ('A', 'C', 'E'));
