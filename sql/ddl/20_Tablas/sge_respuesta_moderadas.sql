-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respuesta_moderadas
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_respuesta_moderadas;
CREATE  TABLE sge_respuesta_moderadas
(
	respuesta_moderada INTEGER NOT NULL DEFAULT nextval('sge_respuesta_moderadas_seq'::text) ,
	respondido_detalle Integer NOT NULL,
	texto_original Varchar NOT NULL,
	texto_nuevo Varchar NOT NULL,
	motivo Varchar NOT NULL,
	usuario Varchar(63) NOT NULL,
	fecha Timestamp with time zone NOT NULL,
	motivo_baja Varchar,
	usuario_baja Varchar(63),
	fecha_baja Timestamp with time zone
);

-- ALTER TABLE sge_respuesta_moderadas DROP CONSTRAINT pk_sge_respuesta_moderadas;
ALTER TABLE sge_respuesta_moderadas ADD CONSTRAINT pk_sge_respuesta_moderadas PRIMARY KEY (respuesta_moderada);
-- ++++++++++++++++++++++++++ Fin tabla sge_respuesta_moderadas +++++++++++++++++++++++++++++

