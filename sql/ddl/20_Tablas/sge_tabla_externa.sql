-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tabla_externa
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_tabla_externa;
CREATE  TABLE sge_tabla_externa
(
	tabla_externa INTEGER NOT NULL DEFAULT nextval('sge_tabla_externa_seq'::text) ,
	unidad_gestion Varchar NOT NULL,
	tabla_externa_nombre Varchar NOT NULL
);

-- ALTER TABLE sge_tabla_externa DROP CONSTRAINT pk_sge_tabla_externa;
ALTER TABLE sge_tabla_externa ADD CONSTRAINT pk_sge_tabla_externa PRIMARY KEY (tabla_externa);
-- ++++++++++++++++++++++++++ Fin tabla sge_tabla_externa +++++++++++++++++++++++++++++

