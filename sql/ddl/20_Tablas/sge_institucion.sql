-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_institucion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_institucion;
CREATE  TABLE sge_institucion
(
	codigo INTEGER NOT NULL DEFAULT nextval('sge_institucion_seq'::text) ,
	nombre Varchar(255) NOT NULL
);

-- ALTER TABLE sge_institucion DROP CONSTRAINT pk_sge_institucion;
ALTER TABLE sge_institucion ADD CONSTRAINT pk_sge_institucion PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla sge_institucion +++++++++++++++++++++++++++++

