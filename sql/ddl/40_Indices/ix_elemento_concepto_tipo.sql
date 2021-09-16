-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3 
-- Tabla: sge_elemento_concepto_tipo
-- Indice: ix_elemento_concepto_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ix_elemento_concepto_tipo;
CREATE UNIQUE INDEX ix_elemento_concepto_tipo ON sge_elemento_concepto_tipo (elemento,concepto,tipo_elemento);
