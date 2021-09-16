-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_cod_postales
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mug_cod_postales;
CREATE  TABLE mug_cod_postales
(
	codigo_postal Varchar(15) NOT NULL,
	localidad Integer NOT NULL,
	id INTEGER NOT NULL DEFAULT nextval('mug_cod_postales_seq'::text) 
);

-- ALTER TABLE mug_cod_postales DROP CONSTRAINT pk_mug_cod_postales;
ALTER TABLE mug_cod_postales ADD CONSTRAINT pk_mug_cod_postales PRIMARY KEY (codigo_postal,localidad);
-- ++++++++++++++++++++++++++ Fin tabla mug_cod_postales +++++++++++++++++++++++++++++

