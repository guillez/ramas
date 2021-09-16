-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_definicion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_formulario_definicion;
CREATE  TABLE sge_formulario_definicion
(
	formulario_definicion INTEGER NOT NULL DEFAULT nextval('sge_formulario_definicion_seq'::text) ,
	formulario Integer NOT NULL,
	encuesta Integer NOT NULL,
	tipo_elemento Integer,
	orden Smallint NOT NULL
);

-- ALTER TABLE sge_formulario_definicion DROP CONSTRAINT pk_sge_formulario_definicion;
ALTER TABLE sge_formulario_definicion ADD CONSTRAINT pk_sge_formulario_definicion PRIMARY KEY (formulario_definicion);
-- ++++++++++++++++++++++++++ Fin tabla sge_formulario_definicion +++++++++++++++++++++++++++++

