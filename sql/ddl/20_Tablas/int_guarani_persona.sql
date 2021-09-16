-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_persona
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS int_guarani_persona;
CREATE  TABLE int_guarani_persona
(
	fecha_proceso Date NOT NULL,
	usuario Varchar(20) NOT NULL,
	clave Varchar(200) NOT NULL,
	ra_codigo Varchar(5) NOT NULL,
	nro_inscripcion Varchar(10) NOT NULL,
	apellido Varchar(30) NOT NULL,
	nombres Varchar(30) NOT NULL,
	pais_documento Integer NOT NULL,
	tipo_documento Integer NOT NULL,
	nro_documento Varchar(20) NOT NULL,
	sexo Char(1) NOT NULL,
	fecha_nacimiento Char(10),
	email Varchar(100),
	titulo_codigo Varchar(5) NOT NULL,
	colacion_codigo Integer,
	colacion_fecha Char(10),
	resultado_proceso Char(1),
	resultado_descripcion Varchar(200),
	unidad_gestion Varchar,
	grupo Integer
);

-- ALTER TABLE int_guarani_persona DROP CONSTRAINT pk_int_guarani_persona;
ALTER TABLE int_guarani_persona ADD CONSTRAINT pk_int_guarani_persona PRIMARY KEY (fecha_proceso,usuario,titulo_codigo);
-- ++++++++++++++++++++++++++ Fin tabla int_guarani_persona +++++++++++++++++++++++++++++

