-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_pregunta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_puntaje_pregunta;
CREATE  TABLE sge_puntaje_pregunta
(
	puntaje_pregunta INTEGER NOT NULL DEFAULT nextval('sge_puntaje_pregunta_seq'::text) ,
	puntaje Integer NOT NULL,
	encuesta_definicion Integer NOT NULL,
	pregunta Integer NOT NULL,
	puntos Integer
);

-- ALTER TABLE sge_puntaje_pregunta DROP CONSTRAINT pk_sge_puntaje_pregunta;
ALTER TABLE sge_puntaje_pregunta ADD CONSTRAINT pk_sge_puntaje_pregunta PRIMARY KEY (puntaje_pregunta);
-- ++++++++++++++++++++++++++ Fin tabla sge_puntaje_pregunta +++++++++++++++++++++++++++++

