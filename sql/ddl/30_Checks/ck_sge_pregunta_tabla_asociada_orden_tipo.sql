-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_pregunta
-- Check: ck_sge_pregunta_tabla_asociada_orden_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_pregunta DROP CONSTRAINT ck_sge_pregunta_tabla_asociada_orden_tipo;
ALTER TABLE sge_pregunta ADD CONSTRAINT ck_sge_pregunta_tabla_asociada_orden_tipo CHECK (tabla_asociada_orden_tipo IN ('ASC', 'DESC'));


