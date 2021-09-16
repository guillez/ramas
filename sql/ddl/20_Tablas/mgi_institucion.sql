-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_institucion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgi_institucion;
CREATE  TABLE mgi_institucion
(
	institucion INTEGER NOT NULL DEFAULT nextval('mgi_institucion_seq'::text) ,
	nombre Varchar(255) NOT NULL,
	nombre_abreviado Varchar(50) NOT NULL,
	tipo_institucion Integer NOT NULL,
	localidad Integer,
	calle Varchar(100),
	numero Varchar(10),
	codigo_postal Varchar(15),
	telefono Varchar(50),
	fax Varchar(50),
	email Varchar(100),
	institucion_araucano Integer
);

-- ALTER TABLE mgi_institucion DROP CONSTRAINT pk_mgi_institucion;
ALTER TABLE mgi_institucion ADD CONSTRAINT pk_mgi_institucion PRIMARY KEY (institucion);
-- ++++++++++++++++++++++++++ Fin tabla mgi_institucion +++++++++++++++++++++++++++++

