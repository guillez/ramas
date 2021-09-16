-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_atributo
-- Check: ck_sge_encuesta_atributo_estado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_encuesta_atributo DROP CONSTRAINT ck_sge_encuesta_atributo_estado;
ALTER TABLE sge_encuesta_atributo ADD CONSTRAINT ck_sge_encuesta_atributo_estado CHECK (estado IN ('A', 'B'));

