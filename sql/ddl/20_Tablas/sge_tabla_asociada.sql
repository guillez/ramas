-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tabla_asociada
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_tabla_asociada;
CREATE  TABLE sge_tabla_asociada
(
	tabla_asociada INTEGER NOT NULL DEFAULT nextval('sge_tabla_asociada_seq'::text) ,
	unidad_gestion Varchar NOT NULL,
	tabla_asociada_nombre Varchar NOT NULL
);

-- ALTER TABLE sge_tabla_asociada DROP CONSTRAINT pk_sge_tabla_asociada;
ALTER TABLE sge_tabla_asociada ADD CONSTRAINT pk_sge_tabla_asociada PRIMARY KEY (tabla_asociada);
-- ++++++++++++++++++++++++++ Fin tabla sge_tabla_asociada +++++++++++++++++++++++++++++

