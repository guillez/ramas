-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_formulario
-- Check: ck_sge_respondido_formulario_terminado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_respondido_formulario DROP CONSTRAINT ck_sge_respondido_formulario_terminado;
ALTER TABLE sge_respondido_formulario ADD CONSTRAINT ck_sge_respondido_formulario_terminado CHECK (terminado IN ('S', 'N'));

