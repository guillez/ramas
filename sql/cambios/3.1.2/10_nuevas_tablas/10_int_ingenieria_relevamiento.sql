-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++  
-- SIU-KOLLA 3 - Sistema de Seguimiento de Graduados  					
-- Version 3.1.2
-- Tabla: int_ingenieria_relevamiento
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ 	
-- DROP TABLE int_ingenieria_relevamiento;

CREATE TABLE int_ingenieria_relevamiento
(
  tipo_documento integer NOT NULL, --descripcion corta (valores validos: )
  numero_documento character varying(20) NOT NULL, 
  pais_documento integer,
  usuario character varying(20) NOT NULL, 
  clave character varying(200) NOT NULL,
  arau_ua_nombre character varying(200), --Unidad académica
  arau_ua integer, -- Codigo araucano de unidad academica
  arau_titulo_nombre character varying(255), --Nombre de la Carrera
  arau_titulo integer, --Codigo araucano de carrera
  apellidos character varying(100) NOT NULL,
  nombres character varying(100),
  fecha_nacimiento date,
  email character varying(100) NOT NULL,
  genero character varying(1) NOT NULL,--Codigo
  anio_ingreso integer, --año de ingreso a la carrera
  cant_total_mat_aprob integer,
  cant_mat_regul integer,
  cant_mat_plan_estu integer,
  cant_mat_aprob integer,
  fecha_ult_act_acad date,
  importado character(1) NOT NULL DEFAULT 'N'::bpchar,
  resultado_proceso character(1),
  resultado_descripcion character varying(200),
  CONSTRAINT int_ingenieria_relevamiento_pkey PRIMARY KEY (usuario, arau_titulo)
)
WITH (
  OIDS=TRUE
);
ALTER TABLE int_ingenieria_relevamiento OWNER TO postgres;
COMMENT ON TABLE int_ingenieria_relevamiento IS 'Interfaz de Alumnos de Ingeniería.';
