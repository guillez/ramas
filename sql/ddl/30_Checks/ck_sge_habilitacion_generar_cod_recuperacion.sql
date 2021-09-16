-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_habilitacion
-- Check: ck_sge_habilitacion_generar_cod_recuperacion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_habilitacion DROP CONSTRAINT ck_sge_habilitacion_generar_cod_recuperacion;
ALTER TABLE sge_habilitacion ADD CONSTRAINT ck_sge_habilitacion_generar_cod_recuperacion CHECK (generar_cod_recuperacion IN ('S', 'N'));


