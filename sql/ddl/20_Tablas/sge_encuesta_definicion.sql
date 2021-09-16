-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_definicion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_encuesta_definicion;
CREATE  TABLE sge_encuesta_definicion
(
	encuesta_definicion INTEGER NOT NULL DEFAULT nextval('sge_encuesta_definicion_seq'::text) ,
	encuesta Integer NOT NULL,
	bloque Integer NOT NULL,
	pregunta Integer NOT NULL,
	orden Smallint NOT NULL,
	obligatoria Char(1) NOT NULL DEFAULT 'N'
);

-- ALTER TABLE sge_encuesta_definicion DROP CONSTRAINT pk_sge_encuesta_definicion;
ALTER TABLE sge_encuesta_definicion ADD CONSTRAINT pk_sge_encuesta_definicion PRIMARY KEY (encuesta_definicion);
-- ++++++++++++++++++++++++++ Fin tabla sge_encuesta_definicion +++++++++++++++++++++++++++++

