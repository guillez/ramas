-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_pregunta_respuesta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_pregunta_respuesta;
CREATE  TABLE sge_pregunta_respuesta
(
	respuesta Integer NOT NULL,
	pregunta Integer NOT NULL,
	orden Smallint NOT NULL
);

-- ALTER TABLE sge_pregunta_respuesta DROP CONSTRAINT pk_sge_pregunta_respuesta;
ALTER TABLE sge_pregunta_respuesta ADD CONSTRAINT pk_sge_pregunta_respuesta PRIMARY KEY (respuesta,pregunta);
-- ++++++++++++++++++++++++++ Fin tabla sge_pregunta_respuesta +++++++++++++++++++++++++++++

