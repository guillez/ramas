-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_responsable_academica
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgi_responsable_academica;
CREATE  TABLE mgi_responsable_academica
(
	responsable_academica INTEGER NOT NULL DEFAULT nextval('mgi_responsable_academica_seq'::text) ,
	nombre Varchar(200) NOT NULL,
	codigo Varchar NOT NULL,
	tipo_responsable_academica Integer NOT NULL,
	institucion Integer NOT NULL,
	ra_araucano Integer,
	localidad Integer,
	calle Varchar(100),
	numero Varchar(10),
	codigo_postal Varchar(15),
	telefono Varchar(50),
	fax Varchar(50),
	email Varchar(100),
	unidad_gestion Varchar
);

-- ALTER TABLE mgi_responsable_academica DROP CONSTRAINT pk_mgi_responsable_academica;
ALTER TABLE mgi_responsable_academica ADD CONSTRAINT pk_mgi_responsable_academica PRIMARY KEY (responsable_academica);
-- ++++++++++++++++++++++++++ Fin tabla mgi_responsable_academica +++++++++++++++++++++++++++++

