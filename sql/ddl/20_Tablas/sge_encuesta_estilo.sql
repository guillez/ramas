-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_estilo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_encuesta_estilo;
CREATE  TABLE sge_encuesta_estilo
(
	estilo Integer NOT NULL,
	nombre Varchar(60) NOT NULL,
	descripcion Varchar(255),
	archivo Varchar(255) NOT NULL
);

-- ALTER TABLE sge_encuesta_estilo DROP CONSTRAINT pk_sge_encuesta_estilo;
ALTER TABLE sge_encuesta_estilo ADD CONSTRAINT pk_sge_encuesta_estilo PRIMARY KEY (estilo);
-- ++++++++++++++++++++++++++ Fin tabla sge_encuesta_estilo +++++++++++++++++++++++++++++

