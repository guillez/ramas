-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_respondido_detalle
-- Check: ck_sge_respondido_detalle_moderada
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_respondido_detalle DROP CONSTRAINT ck_sge_respondido_detalle_moderada;
ALTER TABLE sge_respondido_detalle ADD CONSTRAINT ck_sge_respondido_detalle_moderada CHECK (moderada IN ('S', 'N'));
