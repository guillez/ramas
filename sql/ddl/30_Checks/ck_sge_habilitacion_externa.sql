-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_habilitacion
-- Check: ck_sge_habilitacion_externa
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_habilitacion DROP CONSTRAINT ck_sge_habilitacion_externa;
ALTER TABLE sge_habilitacion ADD CONSTRAINT ck_sge_habilitacion_externa CHECK (externa IN ('S', 'N'));
