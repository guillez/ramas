-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_encuestado
-- Check: ck_sge_encuestado_guest
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_encuestado DROP CONSTRAINT ck_sge_encuestado_guest;
ALTER TABLE sge_encuestado ADD CONSTRAINT ck_sge_encuestado_guest CHECK (guest IN ('S', 'N'));
