-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_ingenieria_relevamiento
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS int_ingenieria_relevamiento;
CREATE  TABLE int_ingenieria_relevamiento
(
	tipo_documento Integer NOT NULL,
	numero_documento Varchar(20) NOT NULL,
	pais_documento Integer,
	usuario Varchar(20) NOT NULL,
	clave Varchar(200) NOT NULL,
	arau_ua_nombre Varchar(200),
	arau_ua Varchar(200),
	arau_titulo_nombre Varchar(255),
	arau_titulo Integer NOT NULL,
	apellidos Varchar(100),
	nombres Varchar(100),
	fecha_nacimiento Date,
	email Varchar(100) NOT NULL,
	genero Char(1) NOT NULL,
	anio_ingreso Integer,
	cant_total_mat_aprob Integer,
	cant_mat_regul Integer,
	cant_mat_plan_estu Integer,
	cant_mat_aprob Integer,
	fecha_ult_act_acad Date,
	importado Char(1) NOT NULL,
	resultado_proceso Char(1),
	resultado_descripcion Varchar(200)
);

-- ALTER TABLE int_ingenieria_relevamiento DROP CONSTRAINT pk_int_ingenieria_relevamiento;
ALTER TABLE int_ingenieria_relevamiento ADD CONSTRAINT pk_int_ingenieria_relevamiento PRIMARY KEY (usuario,arau_titulo);
-- ++++++++++++++++++++++++++ Fin tabla int_ingenieria_relevamiento +++++++++++++++++++++++++++++

