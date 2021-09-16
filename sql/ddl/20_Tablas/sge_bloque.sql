-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_bloque
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_bloque;
CREATE  TABLE sge_bloque
(
	bloque INTEGER NOT NULL DEFAULT nextval('sge_bloque_seq'::text) ,
	nombre Varchar(255) NOT NULL,
	descripcion Varchar(255),
	orden Smallint NOT NULL
);

-- ALTER TABLE sge_bloque DROP CONSTRAINT pk_sge_bloque;
ALTER TABLE sge_bloque ADD CONSTRAINT pk_sge_bloque PRIMARY KEY (bloque);
-- ++++++++++++++++++++++++++ Fin tabla sge_bloque +++++++++++++++++++++++++++++

