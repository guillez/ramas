-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_dependencia_definicion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_pregunta_dependencia_definicion;
CREATE  TABLE sge_pregunta_dependencia_definicion
(
	dependencia_definicion INTEGER NOT NULL DEFAULT nextval('sge_pregunta_dependencia_definicion_seq'::text) ,
	pregunta_dependencia Integer NOT NULL,
	bloque Integer NOT NULL,
	pregunta Integer,
	condicion Varchar NOT NULL,
	valor Varchar,
	accion Varchar NOT NULL,
	encuesta_definicion Integer
);

-- ALTER TABLE sge_pregunta_dependencia_definicion DROP CONSTRAINT pk_sge_pregunta_dependencia_definicion;
ALTER TABLE sge_pregunta_dependencia_definicion ADD CONSTRAINT pk_sge_pregunta_dependencia_definicion PRIMARY KEY (dependencia_definicion);
-- ++++++++++++++++++++++++++ Fin tabla sge_pregunta_dependencia_definicion +++++++++++++++++++++++++++++

