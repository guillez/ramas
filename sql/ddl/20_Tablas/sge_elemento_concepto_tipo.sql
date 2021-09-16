-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_elemento_concepto_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_elemento_concepto_tipo;
CREATE  TABLE sge_elemento_concepto_tipo
(
	elemento_concepto INTEGER NOT NULL DEFAULT nextval('sge_elemento_concepto_tipo_seq'::text) ,
	elemento Integer NOT NULL,
	concepto Integer NOT NULL,
	tipo_elemento Integer NOT NULL
);

-- ALTER TABLE sge_elemento_concepto_tipo DROP CONSTRAINT pk_sge_elemento_concepto_tipo;
ALTER TABLE sge_elemento_concepto_tipo ADD CONSTRAINT pk_sge_elemento_concepto_tipo PRIMARY KEY (elemento_concepto);
-- ++++++++++++++++++++++++++ Fin tabla sge_elemento_concepto_tipo +++++++++++++++++++++++++++++

