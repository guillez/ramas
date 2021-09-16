-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_respuesta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_puntaje_respuesta;
CREATE  TABLE sge_puntaje_respuesta
(
	puntaje_respuesta INTEGER NOT NULL DEFAULT nextval('sge_puntaje_respuesta_seq'::text) ,
	puntos Integer,
	puntaje_pregunta Integer NOT NULL,
	pregunta Integer NOT NULL,
	respuesta Integer NOT NULL
);

-- ALTER TABLE sge_puntaje_respuesta DROP CONSTRAINT pk_sge_puntaje_respuesta;
ALTER TABLE sge_puntaje_respuesta ADD CONSTRAINT pk_sge_puntaje_respuesta PRIMARY KEY (puntaje_respuesta);
-- ++++++++++++++++++++++++++ Fin tabla sge_puntaje_respuesta +++++++++++++++++++++++++++++

