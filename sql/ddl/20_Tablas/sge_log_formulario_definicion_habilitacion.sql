-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_log_formulario_definicion_habilitacion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_log_formulario_definicion_habilitacion;
CREATE  TABLE sge_log_formulario_definicion_habilitacion
(
	habilitacion Integer NOT NULL,
	encuesta Integer NOT NULL,
	tipo_elemento Integer,
	orden Smallint NOT NULL
);


-- ++++++++++++++++++++++++++ Fin tabla sge_log_formulario_definicion_habilitacion +++++++++++++++++++++++++++++

