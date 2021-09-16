-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_puntaje;
CREATE  TABLE sge_puntaje
(
	puntaje INTEGER NOT NULL DEFAULT nextval('sge_puntaje_seq'::text) ,
	nombre Varchar,
	implementado Char(1),
	fecha_hora_creacion Timestamp with time zone,
	encuesta Integer NOT NULL
);

-- ALTER TABLE sge_puntaje DROP CONSTRAINT pk_sge_puntaje;
ALTER TABLE sge_puntaje ADD CONSTRAINT pk_sge_puntaje PRIMARY KEY (puntaje);
-- ++++++++++++++++++++++++++ Fin tabla sge_puntaje +++++++++++++++++++++++++++++

