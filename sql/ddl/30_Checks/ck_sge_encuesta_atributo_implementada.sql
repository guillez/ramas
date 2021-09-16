-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_atributo
-- Check: ck_sge_encuesta_atributo_implementada
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_encuesta_atributo DROP CONSTRAINT ck_sge_encuesta_atributo_implementada;
ALTER TABLE sge_encuesta_atributo ADD CONSTRAINT ck_sge_encuesta_atributo_implementada CHECK (implementada IN ('S', 'N'));
