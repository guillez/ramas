-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tipo_elemento
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_tipo_elemento;
CREATE  TABLE sge_tipo_elemento
(
	tipo_elemento INTEGER NOT NULL DEFAULT nextval('sge_tipo_elemento_seq'::text) ,
	descripcion Varchar(100),
	unidad_gestion Varchar,
	tipo_elemento_externo Varchar(100),
	sistema Integer
);

-- ALTER TABLE sge_tipo_elemento DROP CONSTRAINT pk_sge_tipo_elemento;
ALTER TABLE sge_tipo_elemento ADD CONSTRAINT pk_sge_tipo_elemento PRIMARY KEY (tipo_elemento);
-- ++++++++++++++++++++++++++ Fin tabla sge_tipo_elemento +++++++++++++++++++++++++++++

