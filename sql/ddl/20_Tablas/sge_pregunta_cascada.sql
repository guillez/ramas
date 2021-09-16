-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_cascada
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_pregunta_cascada;
CREATE  TABLE sge_pregunta_cascada
(
	pregunta_disparadora Integer NOT NULL,
	pregunta_receptora Integer NOT NULL
);

-- ALTER TABLE sge_pregunta_cascada DROP CONSTRAINT pk_sge_pregunta_cascada;
ALTER TABLE sge_pregunta_cascada ADD CONSTRAINT pk_sge_pregunta_cascada PRIMARY KEY (pregunta_disparadora);
-- ++++++++++++++++++++++++++ Fin tabla sge_pregunta_cascada +++++++++++++++++++++++++++++

