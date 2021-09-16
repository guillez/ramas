-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_elemento
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_elemento;
CREATE  TABLE sge_elemento
(
	elemento INTEGER NOT NULL DEFAULT nextval('sge_elemento_seq'::text) ,
	elemento_externo Varchar(100),
	url_img Varchar(127),
	descripcion Text NOT NULL,
	unidad_gestion Varchar NOT NULL,
	sistema Integer
);

-- ALTER TABLE sge_elemento DROP CONSTRAINT pk_sge_elemento;
ALTER TABLE sge_elemento ADD CONSTRAINT pk_sge_elemento PRIMARY KEY (elemento);
-- ++++++++++++++++++++++++++ Fin tabla sge_elemento +++++++++++++++++++++++++++++

