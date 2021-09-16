-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuestado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_encuestado;
CREATE  TABLE sge_encuestado
(
	encuestado INTEGER NOT NULL DEFAULT nextval('sge_encuestado_seq'::text) ,
	usuario Varchar(60),
	clave Varchar(200),
	guest Char(1) NOT NULL DEFAULT 'N',
	externo Char(1) NOT NULL DEFAULT 'N',
	documento_pais Integer,
	documento_tipo Integer,
	documento_numero Varchar(20),
	apellidos Varchar(100),
	nombres Varchar(100),
	email Varchar(100),
	sexo Char(1),
	fecha_nacimiento Date,
	imagen_perfil_nombre Varchar(300),
	imagen_perfil_bytes Bytea
);

-- ALTER TABLE sge_encuestado DROP CONSTRAINT pk_sge_encuestado;
ALTER TABLE sge_encuestado ADD CONSTRAINT pk_sge_encuestado PRIMARY KEY (encuestado);
-- ++++++++++++++++++++++++++ Fin tabla sge_encuestado +++++++++++++++++++++++++++++

