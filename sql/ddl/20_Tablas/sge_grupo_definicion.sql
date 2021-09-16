-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_grupo_definicion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_grupo_definicion;
CREATE  TABLE sge_grupo_definicion
(
	grupo INTEGER NOT NULL DEFAULT nextval('sge_grupo_definicion_seq'::text) ,
	nombre Varchar(100) NOT NULL,
	estado Char(1) NOT NULL DEFAULT 'A',
	externo Char(1) NOT NULL DEFAULT 'N',
	descripcion Text,
	unidad_gestion Varchar
);

-- ALTER TABLE sge_grupo_definicion DROP CONSTRAINT pk_sge_grupo_definicion;
ALTER TABLE sge_grupo_definicion ADD CONSTRAINT pk_sge_grupo_definicion PRIMARY KEY (grupo);
-- ++++++++++++++++++++++++++ Fin tabla sge_grupo_definicion +++++++++++++++++++++++++++++

