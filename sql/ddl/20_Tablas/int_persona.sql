-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_persona
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS int_persona;
CREATE  TABLE int_persona
(
	persona INTEGER NOT NULL DEFAULT nextval('int_persona_seq'::text) ,
	usuario Varchar(60),
	clave Varchar(200),
	autentificacion Varchar(10),
	apellidos Varchar(30),
	nombres Varchar(30),
	pais_documento Integer,
	tipo_documento Integer,
	nro_documento Varchar(20),
	sexo Char(1),
	fecha_nac Char(10),
	email Varchar(100),
	resultado_proceso Char(1),
	resultado_descripcion Varchar(200),
	grupo Integer
);

-- ALTER TABLE int_persona DROP CONSTRAINT pk_int_persona;
ALTER TABLE int_persona ADD CONSTRAINT pk_int_persona PRIMARY KEY (persona);
-- ++++++++++++++++++++++++++ Fin tabla int_persona +++++++++++++++++++++++++++++

