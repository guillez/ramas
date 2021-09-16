-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_componente_pregunta
-- Check: ck_sge_componente_pregunta_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_componente_pregunta DROP CONSTRAINT ck_sge_componente_pregunta_tipo;
ALTER TABLE sge_componente_pregunta ADD CONSTRAINT ck_sge_componente_pregunta_tipo CHECK (tipo IN ('A', 'C', 'E'));
