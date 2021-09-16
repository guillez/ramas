-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_respondido_encuestado
-- Check: ck_sge_respondido_encuestado_terminado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_respondido_encuestado DROP CONSTRAINT ck_sge_respondido_encuestado_terminado;
ALTER TABLE sge_respondido_encuestado ADD CONSTRAINT ck_sge_respondido_encuestado_terminado CHECK (terminado IN ('S', 'N'));
