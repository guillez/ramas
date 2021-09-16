-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_encuesta_atributo
-- Check: ck_sge_encuesta_atributo_estado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_encuesta_atributo DROP CONSTRAINT ck_sge_encuesta_atributo_estado;
ALTER TABLE sge_encuesta_atributo ADD CONSTRAINT ck_sge_encuesta_atributo_estado CHECK (estado IN ('A', 'B'));

