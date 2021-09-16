-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_pregunta_dependencia
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_pregunta_dependencia;
CREATE  TABLE sge_pregunta_dependencia
(
	pregunta_dependencia INTEGER NOT NULL DEFAULT nextval('sge_pregunta_dependencia_seq'::text) ,
	encuesta_definicion Integer NOT NULL
);

-- ALTER TABLE sge_pregunta_dependencia DROP CONSTRAINT pk_sge_pregunta_dependencia;
ALTER TABLE sge_pregunta_dependencia ADD CONSTRAINT pk_sge_pregunta_dependencia PRIMARY KEY (pregunta_dependencia);
-- ++++++++++++++++++++++++++ Fin tabla sge_pregunta_dependencia +++++++++++++++++++++++++++++

