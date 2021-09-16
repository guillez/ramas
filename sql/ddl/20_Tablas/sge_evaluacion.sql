-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_evaluacion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_evaluacion;
CREATE  TABLE sge_evaluacion
(
	evaluacion INTEGER NOT NULL DEFAULT nextval('sge_evaluacion_seq'::text) ,
	nombre Varchar NOT NULL,
	cerrada Char(1) NOT NULL DEFAULT 'N',
	habilitacion Integer NOT NULL
);

-- ALTER TABLE sge_evaluacion DROP CONSTRAINT pk_sge_evaluacion;
ALTER TABLE sge_evaluacion ADD CONSTRAINT pk_sge_evaluacion PRIMARY KEY (evaluacion);
-- ++++++++++++++++++++++++++ Fin tabla sge_evaluacion +++++++++++++++++++++++++++++

