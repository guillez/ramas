/*
SIU-Kolla 4 - Módulo de Gestión de Encuestas

Creado:		11/05/2005
Modificado:	13/04/2021
Modelo:		
Versión:		
*/


-- ##ARCHIVO##sge_bloque_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_bloque
-- Secuencia: sge_bloque_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_bloque_seq;
CREATE SEQUENCE sge_bloque_seq START 1;


-- ##ARCHIVO##sge_bloque##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_bloque
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_bloque;
CREATE  TABLE sge_bloque
(
	bloque INTEGER NOT NULL DEFAULT nextval('sge_bloque_seq'::text) ,
	nombre Varchar(255) NOT NULL,
	descripcion Varchar(255),
	orden Smallint NOT NULL
);

-- ALTER TABLE sge_bloque DROP CONSTRAINT pk_sge_bloque;
ALTER TABLE sge_bloque ADD CONSTRAINT pk_sge_bloque PRIMARY KEY (bloque);
-- ++++++++++++++++++++++++++ Fin tabla sge_bloque +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_bloque##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_bloque
-- Permisos para la tabla: sge_bloque_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_bloque OWNER TO postgres;
GRANT ALL ON TABLE sge_bloque TO postgres;


-- ##ARCHIVO##sge_bloque_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_bloque
-- Actualizacion Nro de Secuencia: sge_bloque_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_bloque_seq',(SELECT MAX(bloque) FROM sge_bloque));


-- ##ARCHIVO##sge_respondido_detalle_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_detalle
-- Secuencia: sge_respondido_detalle_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_respondido_detalle_seq;
CREATE SEQUENCE sge_respondido_detalle_seq START 1;


-- ##ARCHIVO##sge_respondido_detalle##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_detalle
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_respondido_detalle;
CREATE  TABLE sge_respondido_detalle
(
	respondido_detalle INTEGER NOT NULL DEFAULT nextval('sge_respondido_detalle_seq'::text) ,
	respondido_encuesta Integer NOT NULL,
	encuesta_definicion Integer NOT NULL,
	respuesta_valor Varchar,
	respuesta_codigo Integer,
	moderada Char(1) NOT NULL DEFAULT 'N'
);

-- ALTER TABLE sge_respondido_detalle DROP CONSTRAINT pk_sge_respondido_detalle;
ALTER TABLE sge_respondido_detalle ADD CONSTRAINT pk_sge_respondido_detalle PRIMARY KEY (respondido_detalle);
-- ++++++++++++++++++++++++++ Fin tabla sge_respondido_detalle +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_respondido_detalle##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_detalle
-- Permisos para la tabla: sge_respondido_detalle_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_respondido_detalle OWNER TO postgres;
GRANT ALL ON TABLE sge_respondido_detalle TO postgres;


-- ##ARCHIVO##ck_sge_respondido_detalle_moderada##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_detalle
-- Check: ck_sge_respondido_detalle_moderada
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_respondido_detalle DROP CONSTRAINT ck_sge_respondido_detalle_moderada;
ALTER TABLE sge_respondido_detalle ADD CONSTRAINT ck_sge_respondido_detalle_moderada CHECK (moderada IN ('S', 'N'));
-- ##ARCHIVO##sge_respondido_detalle_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_detalle
-- Actualizacion Nro de Secuencia: sge_respondido_detalle_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_respondido_detalle_seq',(SELECT MAX(respondido_detalle) FROM sge_respondido_detalle));


-- ##ARCHIVO##sge_encuesta_atributo_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_atributo
-- Secuencia: sge_encuesta_atributo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_encuesta_atributo_seq;
CREATE SEQUENCE sge_encuesta_atributo_seq START 1;


-- ##ARCHIVO##sge_encuesta_atributo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_atributo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_encuesta_atributo;
CREATE  TABLE sge_encuesta_atributo
(
	encuesta INTEGER NOT NULL DEFAULT nextval('sge_encuesta_atributo_seq'::text) ,
	nombre Varchar NOT NULL,
	descripcion Varchar,
	texto_preliminar Text,
	implementada Char(1) NOT NULL,
	estado Char(1) NOT NULL,
	unidad_gestion Varchar
);

-- ALTER TABLE sge_encuesta_atributo DROP CONSTRAINT pk_sge_encuesta_atributo;
ALTER TABLE sge_encuesta_atributo ADD CONSTRAINT pk_sge_encuesta_atributo PRIMARY KEY (encuesta);
-- ++++++++++++++++++++++++++ Fin tabla sge_encuesta_atributo +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_encuesta_atributo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_atributo
-- Permisos para la tabla: sge_encuesta_atributo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_encuesta_atributo OWNER TO postgres;
GRANT ALL ON TABLE sge_encuesta_atributo TO postgres;


-- ##ARCHIVO##ck_sge_encuesta_atributo_implementada##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_atributo
-- Check: ck_sge_encuesta_atributo_implementada
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_encuesta_atributo DROP CONSTRAINT ck_sge_encuesta_atributo_implementada;
ALTER TABLE sge_encuesta_atributo ADD CONSTRAINT ck_sge_encuesta_atributo_implementada CHECK (implementada IN ('S', 'N'));
-- ##ARCHIVO##ck_sge_encuesta_atributo_estado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_atributo
-- Check: ck_sge_encuesta_atributo_estado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_encuesta_atributo DROP CONSTRAINT ck_sge_encuesta_atributo_estado;
ALTER TABLE sge_encuesta_atributo ADD CONSTRAINT ck_sge_encuesta_atributo_estado CHECK (estado IN ('A', 'B'));

-- ##ARCHIVO##sge_encuesta_atributo_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_atributo
-- Actualizacion Nro de Secuencia: sge_encuesta_atributo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_encuesta_atributo_seq',(SELECT MAX(encuesta) FROM sge_encuesta_atributo));


-- ##ARCHIVO##sge_habilitacion_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_habilitacion
-- Secuencia: sge_habilitacion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_habilitacion_seq;
CREATE SEQUENCE sge_habilitacion_seq START 1;


-- ##ARCHIVO##sge_habilitacion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_habilitacion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_habilitacion;
CREATE  TABLE sge_habilitacion
(
	habilitacion INTEGER NOT NULL DEFAULT nextval('sge_habilitacion_seq'::text) ,
	fecha_desde Date NOT NULL,
	fecha_hasta Date,
	paginado Char(1) NOT NULL DEFAULT 'N',
	externa Char(1) NOT NULL DEFAULT 'N',
	anonima Char(1) NOT NULL DEFAULT 'N',
	estilo Integer NOT NULL,
	sistema Integer,
	password_se Varchar(32),
	descripcion Varchar(255) NOT NULL,
	texto_preliminar Varchar,
	url_imagenes_base Varchar,
	generar_cod_recuperacion Varchar(1) NOT NULL DEFAULT 'S',
	unidad_gestion Varchar,
	imprimir_respuestas_completas Char(1) DEFAULT 1,
	descarga_pdf Char(1) NOT NULL DEFAULT 'S',
	destacada Varchar(1) NOT NULL DEFAULT 'N',
	archivada Varchar(1) NOT NULL DEFAULT 'N',
	publica Varchar(1) NOT NULL DEFAULT 'N',
	mostrar_progreso Varchar(1) NOT NULL DEFAULT 'N'
);

-- ALTER TABLE sge_habilitacion DROP CONSTRAINT pk_sge_habilitacion;
ALTER TABLE sge_habilitacion ADD CONSTRAINT pk_sge_habilitacion PRIMARY KEY (habilitacion);
-- ++++++++++++++++++++++++++ Fin tabla sge_habilitacion +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_habilitacion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_habilitacion
-- Permisos para la tabla: sge_habilitacion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_habilitacion OWNER TO postgres;
GRANT ALL ON TABLE sge_habilitacion TO postgres;


-- ##ARCHIVO##ck_sge_habilitacion_paginado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_habilitacion
-- Check: ck_sge_habilitacion_paginado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_habilitacion DROP CONSTRAINT ck_sge_habilitacion_paginado;
ALTER TABLE sge_habilitacion ADD CONSTRAINT ck_sge_habilitacion_paginado CHECK (paginado IN ('S', 'N'));
-- ##ARCHIVO##ck_sge_habilitacion_externa##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_habilitacion
-- Check: ck_sge_habilitacion_externa
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_habilitacion DROP CONSTRAINT ck_sge_habilitacion_externa;
ALTER TABLE sge_habilitacion ADD CONSTRAINT ck_sge_habilitacion_externa CHECK (externa IN ('S', 'N'));
-- ##ARCHIVO##ck_sge_habilitacion_anonima##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_habilitacion
-- Check: ck_sge_habilitacion_anonima
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_habilitacion DROP CONSTRAINT ck_sge_habilitacion_anonima;
ALTER TABLE sge_habilitacion ADD CONSTRAINT ck_sge_habilitacion_anonima CHECK (anonima IN ('S', 'N'));


-- ##ARCHIVO##ck_sge_habilitacion_generar_cod_recuperacion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_habilitacion
-- Check: ck_sge_habilitacion_generar_cod_recuperacion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_habilitacion DROP CONSTRAINT ck_sge_habilitacion_generar_cod_recuperacion;
ALTER TABLE sge_habilitacion ADD CONSTRAINT ck_sge_habilitacion_generar_cod_recuperacion CHECK (generar_cod_recuperacion IN ('S', 'N'));


-- ##ARCHIVO##ck_sge_habilitacion_descarga_pdf##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_habilitacion
-- Check: ck_sge_habilitacion_descarga_pdf
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_habilitacion DROP CONSTRAINT ck_sge_habilitacion_descarga_pdf;
ALTER TABLE sge_habilitacion ADD CONSTRAINT ck_sge_habilitacion_descarga_pdf CHECK (descarga_pdf IN ('S', 'N'));
-- ##ARCHIVO##ck_sge_habilitacion_destacada##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_habilitacion
-- Check: ck_sge_habilitacion_destacada
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_habilitacion DROP CONSTRAINT ck_sge_habilitacion_destacada;
ALTER TABLE sge_habilitacion ADD CONSTRAINT ck_sge_habilitacion_destacada CHECK (destacada IN ('S', 'N'));
-- ##ARCHIVO##ck_sge_habilitacion_archivada##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_habilitacion
-- Check: ck_sge_habilitacion_archivada
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_habilitacion DROP CONSTRAINT ck_sge_habilitacion_archivada;
ALTER TABLE sge_habilitacion ADD CONSTRAINT ck_sge_habilitacion_archivada CHECK (archivada IN ('S', 'N'));
-- ##ARCHIVO##ck_sge_habilitacion_publica##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_habilitacion
-- Check: ck_sge_habilitacion_publica
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_habilitacion DROP CONSTRAINT ck_sge_habilitacion_publica;
ALTER TABLE sge_habilitacion ADD CONSTRAINT ck_sge_habilitacion_publica CHECK (publica IN ('S','N'));
-- ##ARCHIVO##ck_sge_habilitacion_mostrar_progreso##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_habilitacion
-- Check: ck_sge_habilitacion_mostrar_progreso
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_habilitacion DROP CONSTRAINT ck_sge_habilitacion_mostrar_progreso;
ALTER TABLE sge_habilitacion ADD CONSTRAINT ck_sge_habilitacion_mostrar_progreso CHECK (mostrar_progreso IN ('S','N'));
-- ##ARCHIVO##sge_habilitacion_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_habilitacion
-- Actualizacion Nro de Secuencia: sge_habilitacion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_habilitacion_seq',(SELECT MAX(habilitacion) FROM sge_habilitacion));


-- ##ARCHIVO##sge_pregunta_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta
-- Secuencia: sge_pregunta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_pregunta_seq;
CREATE SEQUENCE sge_pregunta_seq START 1;


-- ##ARCHIVO##sge_pregunta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_pregunta;
CREATE  TABLE sge_pregunta
(
	pregunta INTEGER NOT NULL DEFAULT nextval('sge_pregunta_seq'::text) ,
	nombre Varchar(4096) NOT NULL,
	componente_numero Integer NOT NULL,
	tabla_asociada Varchar(100),
	tabla_asociada_codigo Varchar(50),
	tabla_asociada_descripcion Varchar(50),
	tabla_asociada_orden_campo Varchar(50),
	tabla_asociada_orden_tipo Char(4),
	unidad_gestion Varchar,
	descripcion_resumida Varchar(30) NOT NULL,
	ayuda Varchar,
	oculta Varchar(1) NOT NULL DEFAULT 'N',
	visualizacion_horizontal Varchar(1) NOT NULL DEFAULT 'N'
);

-- ALTER TABLE sge_pregunta DROP CONSTRAINT pk_sge_pregunta;
ALTER TABLE sge_pregunta ADD CONSTRAINT pk_sge_pregunta PRIMARY KEY (pregunta);
-- ++++++++++++++++++++++++++ Fin tabla sge_pregunta +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_pregunta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta
-- Permisos para la tabla: sge_pregunta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_pregunta OWNER TO postgres;
GRANT ALL ON TABLE sge_pregunta TO postgres;


-- ##ARCHIVO##ck_sge_pregunta_tabla_asociada_orden_tipo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta
-- Check: ck_sge_pregunta_tabla_asociada_orden_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_pregunta DROP CONSTRAINT ck_sge_pregunta_tabla_asociada_orden_tipo;
ALTER TABLE sge_pregunta ADD CONSTRAINT ck_sge_pregunta_tabla_asociada_orden_tipo CHECK (tabla_asociada_orden_tipo IN ('ASC', 'DESC'));


-- ##ARCHIVO##ck_sge_pregunta_visualizacion_horizontal##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta
-- Check: ck_sge_pregunta_visualizacion_horizontal
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_pregunta DROP CONSTRAINT ck_sge_pregunta_visualizacion_horizontal;
ALTER TABLE sge_pregunta ADD CONSTRAINT ck_sge_pregunta_visualizacion_horizontal CHECK (visualizacion_horizontal IN ('S', 'N'));
-- ##ARCHIVO##sge_pregunta_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta
-- Actualizacion Nro de Secuencia: sge_pregunta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_pregunta_seq',(SELECT MAX(pregunta) FROM sge_pregunta));


-- ##ARCHIVO##sge_respuesta_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respuesta
-- Secuencia: sge_respuesta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_respuesta_seq;
CREATE SEQUENCE sge_respuesta_seq START 1;


-- ##ARCHIVO##sge_respuesta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respuesta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_respuesta;
CREATE  TABLE sge_respuesta
(
	respuesta INTEGER NOT NULL DEFAULT nextval('sge_respuesta_seq'::text) ,
	valor_tabulado Varchar(255) NOT NULL,
	unidad_gestion Varchar
);

-- ALTER TABLE sge_respuesta DROP CONSTRAINT pk_sge_respuesta;
ALTER TABLE sge_respuesta ADD CONSTRAINT pk_sge_respuesta PRIMARY KEY (respuesta);
-- ++++++++++++++++++++++++++ Fin tabla sge_respuesta +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_respuesta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respuesta
-- Permisos para la tabla: sge_respuesta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_respuesta OWNER TO postgres;
GRANT ALL ON TABLE sge_respuesta TO postgres;


-- ##ARCHIVO##sge_respuesta_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respuesta
-- Actualizacion Nro de Secuencia: sge_respuesta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_respuesta_seq',(SELECT MAX(respuesta) FROM sge_respuesta));


-- ##ARCHIVO##sge_encuesta_definicion_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_definicion
-- Secuencia: sge_encuesta_definicion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_encuesta_definicion_seq;
CREATE SEQUENCE sge_encuesta_definicion_seq START 1;


-- ##ARCHIVO##sge_encuesta_definicion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_definicion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_encuesta_definicion;
CREATE  TABLE sge_encuesta_definicion
(
	encuesta_definicion INTEGER NOT NULL DEFAULT nextval('sge_encuesta_definicion_seq'::text) ,
	encuesta Integer NOT NULL,
	bloque Integer NOT NULL,
	pregunta Integer NOT NULL,
	orden Smallint NOT NULL,
	obligatoria Char(1) NOT NULL DEFAULT 'N'
);

-- ALTER TABLE sge_encuesta_definicion DROP CONSTRAINT pk_sge_encuesta_definicion;
ALTER TABLE sge_encuesta_definicion ADD CONSTRAINT pk_sge_encuesta_definicion PRIMARY KEY (encuesta_definicion);
-- ++++++++++++++++++++++++++ Fin tabla sge_encuesta_definicion +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_encuesta_definicion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_definicion
-- Permisos para la tabla: sge_encuesta_definicion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_encuesta_definicion OWNER TO postgres;
GRANT ALL ON TABLE sge_encuesta_definicion TO postgres;


-- ##ARCHIVO##sge_encuesta_definicion_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_definicion
-- Actualizacion Nro de Secuencia: sge_encuesta_definicion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_encuesta_definicion_seq',(SELECT MAX(encuesta_definicion) FROM sge_encuesta_definicion));


-- ##ARCHIVO##sge_pregunta_respuesta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_respuesta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_pregunta_respuesta;
CREATE  TABLE sge_pregunta_respuesta
(
	respuesta Integer NOT NULL,
	pregunta Integer NOT NULL,
	orden Smallint NOT NULL
);

-- ALTER TABLE sge_pregunta_respuesta DROP CONSTRAINT pk_sge_pregunta_respuesta;
ALTER TABLE sge_pregunta_respuesta ADD CONSTRAINT pk_sge_pregunta_respuesta PRIMARY KEY (respuesta,pregunta);
-- ++++++++++++++++++++++++++ Fin tabla sge_pregunta_respuesta +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_pregunta_respuesta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_respuesta
-- Permisos para la tabla: sge_pregunta_respuesta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_pregunta_respuesta OWNER TO postgres;
GRANT ALL ON TABLE sge_pregunta_respuesta TO postgres;


-- ##ARCHIVO##sge_componente_pregunta_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_componente_pregunta
-- Secuencia: sge_componente_pregunta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_componente_pregunta_seq;
CREATE SEQUENCE sge_componente_pregunta_seq START 1;


-- ##ARCHIVO##sge_componente_pregunta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_componente_pregunta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_componente_pregunta;
CREATE  TABLE sge_componente_pregunta
(
	numero INTEGER NOT NULL DEFAULT nextval('sge_componente_pregunta_seq'::text) ,
	componente Varchar(35) NOT NULL,
	descripcion Varchar(255),
	tipo Char(1) NOT NULL
);

-- ALTER TABLE sge_componente_pregunta DROP CONSTRAINT pk_sge_componente_pregunta;
ALTER TABLE sge_componente_pregunta ADD CONSTRAINT pk_sge_componente_pregunta PRIMARY KEY (numero);
-- ++++++++++++++++++++++++++ Fin tabla sge_componente_pregunta +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_componente_pregunta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_componente_pregunta
-- Permisos para la tabla: sge_componente_pregunta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_componente_pregunta OWNER TO postgres;
GRANT ALL ON TABLE sge_componente_pregunta TO postgres;


-- ##ARCHIVO##ck_sge_componente_pregunta_tipo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_componente_pregunta
-- Check: ck_sge_componente_pregunta_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_componente_pregunta DROP CONSTRAINT ck_sge_componente_pregunta_tipo;
ALTER TABLE sge_componente_pregunta ADD CONSTRAINT ck_sge_componente_pregunta_tipo CHECK (tipo IN ('A', 'C', 'E'));
-- ##ARCHIVO##sge_componente_pregunta_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_componente_pregunta
-- Actualizacion Nro de Secuencia: sge_componente_pregunta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_componente_pregunta_seq',(SELECT MAX(numero) FROM sge_componente_pregunta));


-- ##ARCHIVO##sge_respondido_encuestado_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuestado
-- Secuencia: sge_respondido_encuestado_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_respondido_encuestado_seq;
CREATE SEQUENCE sge_respondido_encuestado_seq START 1;


-- ##ARCHIVO##sge_respondido_encuestado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuestado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_respondido_encuestado;
CREATE  TABLE sge_respondido_encuestado
(
	respondido_encuestado INTEGER NOT NULL DEFAULT nextval('sge_respondido_encuestado_seq'::text) ,
	formulario_habilitado Integer NOT NULL,
	respondido_formulario Integer,
	encuestado Integer NOT NULL,
	sistema Integer,
	codigo_externo Varchar(100),
	fecha Timestamp with time zone NOT NULL,
	terminado Char(1),
	ignorado Char(1),
	estado_sinc Char(4) DEFAULT 'PEND'
);

-- ALTER TABLE sge_respondido_encuestado DROP CONSTRAINT pk_sge_respondido_encuestado;
ALTER TABLE sge_respondido_encuestado ADD CONSTRAINT pk_sge_respondido_encuestado PRIMARY KEY (respondido_encuestado);
-- ++++++++++++++++++++++++++ Fin tabla sge_respondido_encuestado +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_respondido_encuestado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuestado
-- Permisos para la tabla: sge_respondido_encuestado_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_respondido_encuestado OWNER TO postgres;
GRANT ALL ON TABLE sge_respondido_encuestado TO postgres;


-- ##ARCHIVO##ck_sge_respondido_encuestado_terminado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuestado
-- Check: ck_sge_respondido_encuestado_terminado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_respondido_encuestado DROP CONSTRAINT ck_sge_respondido_encuestado_terminado;
ALTER TABLE sge_respondido_encuestado ADD CONSTRAINT ck_sge_respondido_encuestado_terminado CHECK (terminado IN ('S', 'N'));
-- ##ARCHIVO##ck_sge_respondido_encuestado_ignorado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuestado
-- Check: ck_sge_respondido_encuestado_ignorado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_respondido_encuestado DROP CONSTRAINT ck_sge_respondido_encuestado_ignorado;
ALTER TABLE sge_respondido_encuestado ADD CONSTRAINT ck_sge_respondido_encuestado_ignorado CHECK (ignorado  IN ('S', 'N'));
-- ##ARCHIVO##ck_sge_respondido_encuestado_estado_sinc##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuestado
-- Check: ck_sge_respondido_encuestado_estado_sinc
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_respondido_encuestado DROP CONSTRAINT ck_sge_respondido_encuestado_estado_sinc;
ALTER TABLE sge_respondido_encuestado ADD CONSTRAINT ck_sge_respondido_encuestado_estado_sinc CHECK (estado_sinc IN ('OK', 'ERR', 'PEND'));
-- ##ARCHIVO##sge_respondido_encuestado_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuestado
-- Actualizacion Nro de Secuencia: sge_respondido_encuestado_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_respondido_encuestado_seq',(SELECT MAX(respondido_encuestado) FROM sge_respondido_encuestado));


-- ##ARCHIVO##sge_institucion_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_institucion
-- Secuencia: sge_institucion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_institucion_seq;
CREATE SEQUENCE sge_institucion_seq START 1;


-- ##ARCHIVO##sge_institucion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_institucion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_institucion;
CREATE  TABLE sge_institucion
(
	codigo INTEGER NOT NULL DEFAULT nextval('sge_institucion_seq'::text) ,
	nombre Varchar(255) NOT NULL
);

-- ALTER TABLE sge_institucion DROP CONSTRAINT pk_sge_institucion;
ALTER TABLE sge_institucion ADD CONSTRAINT pk_sge_institucion PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla sge_institucion +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_institucion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_institucion
-- Permisos para la tabla: sge_institucion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_institucion OWNER TO postgres;
GRANT ALL ON TABLE sge_institucion TO postgres;


-- ##ARCHIVO##sge_institucion_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_institucion
-- Actualizacion Nro de Secuencia: sge_institucion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_institucion_seq',(SELECT MAX(codigo) FROM sge_institucion));


-- ##ARCHIVO##mgi_responsable_academica_tipo_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_responsable_academica_tipo
-- Secuencia: mgi_responsable_academica_tipo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE mgi_responsable_academica_tipo_seq;
CREATE SEQUENCE mgi_responsable_academica_tipo_seq START 1;


-- ##ARCHIVO##mgi_responsable_academica_tipo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_responsable_academica_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgi_responsable_academica_tipo;
CREATE  TABLE mgi_responsable_academica_tipo
(
	tipo_responsable_academica INTEGER NOT NULL DEFAULT nextval('mgi_responsable_academica_tipo_seq'::text) ,
	nombre Varchar(50) NOT NULL,
	descripcion Varchar(255)
);

-- ALTER TABLE mgi_responsable_academica_tipo DROP CONSTRAINT pk_mgi_responsable_academica_tipo;
ALTER TABLE mgi_responsable_academica_tipo ADD CONSTRAINT pk_mgi_responsable_academica_tipo PRIMARY KEY (tipo_responsable_academica);
-- ++++++++++++++++++++++++++ Fin tabla mgi_responsable_academica_tipo +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_mgi_responsable_academica_tipo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_responsable_academica_tipo
-- Permisos para la tabla: mgi_responsable_academica_tipo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mgi_responsable_academica_tipo OWNER TO postgres;
GRANT ALL ON TABLE mgi_responsable_academica_tipo TO postgres;


-- ##ARCHIVO##mgi_responsable_academica_tipo_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_responsable_academica_tipo
-- Actualizacion Nro de Secuencia: mgi_responsable_academica_tipo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('mgi_responsable_academica_tipo_seq',(SELECT MAX(tipo_responsable_academica) FROM mgi_responsable_academica_tipo));


-- ##ARCHIVO##mgi_responsable_academica_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_responsable_academica
-- Secuencia: mgi_responsable_academica_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE mgi_responsable_academica_seq;
CREATE SEQUENCE mgi_responsable_academica_seq START 1;


-- ##ARCHIVO##mgi_responsable_academica##
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

-- ##ARCHIVO##grant_mgi_responsable_academica##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_responsable_academica
-- Permisos para la tabla: mgi_responsable_academica_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mgi_responsable_academica OWNER TO postgres;
GRANT ALL ON TABLE mgi_responsable_academica TO postgres;


-- ##ARCHIVO##mgi_responsable_academica_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_responsable_academica
-- Actualizacion Nro de Secuencia: mgi_responsable_academica_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('mgi_responsable_academica_seq',(SELECT MAX(responsable_academica) FROM mgi_responsable_academica));


-- ##ARCHIVO##mgi_institucion_tipo_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_institucion_tipo
-- Secuencia: mgi_institucion_tipo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE mgi_institucion_tipo_seq;
CREATE SEQUENCE mgi_institucion_tipo_seq START 1;


-- ##ARCHIVO##mgi_institucion_tipo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_institucion_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgi_institucion_tipo;
CREATE  TABLE mgi_institucion_tipo
(
	tipo_institucion INTEGER NOT NULL DEFAULT nextval('mgi_institucion_tipo_seq'::text) ,
	nombre Varchar(50) NOT NULL,
	descripcion Varchar(255)
);

-- ALTER TABLE mgi_institucion_tipo DROP CONSTRAINT pk_mgi_institucion_tipo;
ALTER TABLE mgi_institucion_tipo ADD CONSTRAINT pk_mgi_institucion_tipo PRIMARY KEY (tipo_institucion);
-- ++++++++++++++++++++++++++ Fin tabla mgi_institucion_tipo +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_mgi_institucion_tipo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_institucion_tipo
-- Permisos para la tabla: mgi_institucion_tipo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mgi_institucion_tipo OWNER TO postgres;
GRANT ALL ON TABLE mgi_institucion_tipo TO postgres;


-- ##ARCHIVO##mgi_institucion_tipo_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_institucion_tipo
-- Actualizacion Nro de Secuencia: mgi_institucion_tipo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('mgi_institucion_tipo_seq',(SELECT MAX(tipo_institucion) FROM mgi_institucion_tipo));


-- ##ARCHIVO##mgi_institucion_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_institucion
-- Secuencia: mgi_institucion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE mgi_institucion_seq;
CREATE SEQUENCE mgi_institucion_seq START 1;


-- ##ARCHIVO##mgi_institucion##
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

-- ##ARCHIVO##grant_mgi_institucion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_institucion
-- Permisos para la tabla: mgi_institucion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mgi_institucion OWNER TO postgres;
GRANT ALL ON TABLE mgi_institucion TO postgres;


-- ##ARCHIVO##mgi_institucion_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_institucion
-- Actualizacion Nro de Secuencia: mgi_institucion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('mgi_institucion_seq',(SELECT MAX(institucion) FROM mgi_institucion));


-- ##ARCHIVO##mug_localidades##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_localidades
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mug_localidades;
CREATE  TABLE mug_localidades
(
	localidad Integer NOT NULL,
	nombre Varchar(100),
	nombre_abreviado Varchar(40) NOT NULL,
	dpto_partido Integer NOT NULL,
	ddn Integer
);

-- ALTER TABLE mug_localidades DROP CONSTRAINT pk_mug_localidades;
ALTER TABLE mug_localidades ADD CONSTRAINT pk_mug_localidades PRIMARY KEY (localidad);
-- ++++++++++++++++++++++++++ Fin tabla mug_localidades +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_mug_localidades##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_localidades
-- Permisos para la tabla: mug_localidades_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mug_localidades OWNER TO postgres;
GRANT ALL ON TABLE mug_localidades TO postgres;


-- ##ARCHIVO##mug_continentes##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_continentes
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mug_continentes;
CREATE  TABLE mug_continentes
(
	continente Char(2) NOT NULL,
	nombre Varchar(20) NOT NULL
);

-- ALTER TABLE mug_continentes DROP CONSTRAINT pk_mug_continentes;
ALTER TABLE mug_continentes ADD CONSTRAINT pk_mug_continentes PRIMARY KEY (continente);
-- ++++++++++++++++++++++++++ Fin tabla mug_continentes +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_mug_continentes##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_continentes
-- Permisos para la tabla: mug_continentes_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mug_continentes OWNER TO postgres;
GRANT ALL ON TABLE mug_continentes TO postgres;


-- ##ARCHIVO##mug_dptos_partidos##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_dptos_partidos
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mug_dptos_partidos;
CREATE  TABLE mug_dptos_partidos
(
	dpto_partido Integer NOT NULL,
	nombre Varchar(60) NOT NULL,
	provincia Integer NOT NULL,
	estado Char(1) NOT NULL
);

-- ALTER TABLE mug_dptos_partidos DROP CONSTRAINT pk_mug_dptos_partidos;
ALTER TABLE mug_dptos_partidos ADD CONSTRAINT pk_mug_dptos_partidos PRIMARY KEY (dpto_partido);
-- ++++++++++++++++++++++++++ Fin tabla mug_dptos_partidos +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_mug_dptos_partidos##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_dptos_partidos
-- Permisos para la tabla: mug_dptos_partidos_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mug_dptos_partidos OWNER TO postgres;
GRANT ALL ON TABLE mug_dptos_partidos TO postgres;


-- ##ARCHIVO##mug_paises##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_paises
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mug_paises;
CREATE  TABLE mug_paises
(
	pais Integer NOT NULL,
	nombre Varchar(60) NOT NULL,
	continente Char(2) NOT NULL,
	codigo_iso Char(2)
);

-- ALTER TABLE mug_paises DROP CONSTRAINT pk_mug_paises;
ALTER TABLE mug_paises ADD CONSTRAINT pk_mug_paises PRIMARY KEY (pais);
-- ++++++++++++++++++++++++++ Fin tabla mug_paises +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_mug_paises##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_paises
-- Permisos para la tabla: mug_paises_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mug_paises OWNER TO postgres;
GRANT ALL ON TABLE mug_paises TO postgres;


-- ##ARCHIVO##mug_provincias##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_provincias
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mug_provincias;
CREATE  TABLE mug_provincias
(
	provincia Integer NOT NULL,
	nombre Varchar(60) NOT NULL,
	pais Integer NOT NULL
);

-- ALTER TABLE mug_provincias DROP CONSTRAINT pk_mug_provincias;
ALTER TABLE mug_provincias ADD CONSTRAINT pk_mug_provincias PRIMARY KEY (provincia);
-- ++++++++++++++++++++++++++ Fin tabla mug_provincias +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_mug_provincias##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_provincias
-- Permisos para la tabla: mug_provincias_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mug_provincias OWNER TO postgres;
GRANT ALL ON TABLE mug_provincias TO postgres;


-- ##ARCHIVO##sge_encuestado_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuestado
-- Secuencia: sge_encuestado_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_encuestado_seq;
CREATE SEQUENCE sge_encuestado_seq START 1;


-- ##ARCHIVO##sge_encuestado##
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

-- ##ARCHIVO##grant_sge_encuestado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuestado
-- Permisos para la tabla: sge_encuestado_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_encuestado OWNER TO postgres;
GRANT ALL ON TABLE sge_encuestado TO postgres;


-- ##ARCHIVO##ck_sge_encuestado_guest##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuestado
-- Check: ck_sge_encuestado_guest
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_encuestado DROP CONSTRAINT ck_sge_encuestado_guest;
ALTER TABLE sge_encuestado ADD CONSTRAINT ck_sge_encuestado_guest CHECK (guest IN ('S', 'N'));
-- ##ARCHIVO##ck_sge_encuestado_externo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuestado
-- Check: ck_sge_encuestado_externo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_encuestado DROP CONSTRAINT ck_sge_encuestado_externo;
ALTER TABLE sge_encuestado ADD CONSTRAINT ck_sge_encuestado_externo CHECK (externo IN ('S', 'N'));


-- ##ARCHIVO##ck_sge_encuestado_sexo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuestado
-- Check: ck_sge_encuestado_sexo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_encuestado DROP CONSTRAINT ck_sge_encuestado_sexo;
ALTER TABLE sge_encuestado ADD CONSTRAINT ck_sge_encuestado_sexo CHECK (sexo IN ('F', 'M', null));


-- ##ARCHIVO##sge_encuestado_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuestado
-- Actualizacion Nro de Secuencia: sge_encuestado_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_encuestado_seq',(SELECT MAX(encuestado) FROM sge_encuestado));


-- ##ARCHIVO##mgi_titulo_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_titulo
-- Secuencia: mgi_titulo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE mgi_titulo_seq;
CREATE SEQUENCE mgi_titulo_seq START 1;


-- ##ARCHIVO##mgi_titulo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_titulo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgi_titulo;
CREATE  TABLE mgi_titulo
(
	titulo INTEGER NOT NULL DEFAULT nextval('mgi_titulo_seq'::text) ,
	nombre Varchar(255) NOT NULL,
	nombre_femenino Varchar(255) NOT NULL,
	codigo Varchar(20) NOT NULL,
	titulo_araucano Integer,
	estado Char(1) NOT NULL,
	unidad_gestion Varchar
);

-- ALTER TABLE mgi_titulo DROP CONSTRAINT pk_mgi_titulo;
ALTER TABLE mgi_titulo ADD CONSTRAINT pk_mgi_titulo PRIMARY KEY (titulo);
-- ++++++++++++++++++++++++++ Fin tabla mgi_titulo +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_mgi_titulo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_titulo
-- Permisos para la tabla: mgi_titulo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mgi_titulo OWNER TO postgres;
GRANT ALL ON TABLE mgi_titulo TO postgres;


-- ##ARCHIVO##mgi_titulo_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_titulo
-- Actualizacion Nro de Secuencia: mgi_titulo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('mgi_titulo_seq',(SELECT MAX(titulo) FROM mgi_titulo));


-- ##ARCHIVO##mgi_titulo_ra##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_titulo_ra
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgi_titulo_ra;
CREATE  TABLE mgi_titulo_ra
(
	responsable_academica Integer NOT NULL,
	titulo Integer NOT NULL
);

-- ALTER TABLE mgi_titulo_ra DROP CONSTRAINT pk_mgi_titulo_ra;
ALTER TABLE mgi_titulo_ra ADD CONSTRAINT pk_mgi_titulo_ra PRIMARY KEY (responsable_academica,titulo);
-- ++++++++++++++++++++++++++ Fin tabla mgi_titulo_ra +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_mgi_titulo_ra##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_titulo_ra
-- Permisos para la tabla: mgi_titulo_ra_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mgi_titulo_ra OWNER TO postgres;
GRANT ALL ON TABLE mgi_titulo_ra TO postgres;


-- ##ARCHIVO##sge_encuestado_titulo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuestado_titulo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_encuestado_titulo;
CREATE  TABLE sge_encuestado_titulo
(
	encuestado Integer NOT NULL,
	titulo Integer NOT NULL,
	anio Integer NOT NULL,
	fecha Date NOT NULL
);

-- ALTER TABLE sge_encuestado_titulo DROP CONSTRAINT pk_sge_encuestado_titulo;
ALTER TABLE sge_encuestado_titulo ADD CONSTRAINT pk_sge_encuestado_titulo PRIMARY KEY (encuestado,titulo);
-- ++++++++++++++++++++++++++ Fin tabla sge_encuestado_titulo +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_encuestado_titulo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuestado_titulo
-- Permisos para la tabla: sge_encuestado_titulo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_encuestado_titulo OWNER TO postgres;
GRANT ALL ON TABLE sge_encuestado_titulo TO postgres;


-- ##ARCHIVO##sge_grupo_definicion_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_grupo_definicion
-- Secuencia: sge_grupo_definicion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_grupo_definicion_seq;
CREATE SEQUENCE sge_grupo_definicion_seq START 1;


-- ##ARCHIVO##sge_grupo_definicion##
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

-- ##ARCHIVO##grant_sge_grupo_definicion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_grupo_definicion
-- Permisos para la tabla: sge_grupo_definicion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_grupo_definicion OWNER TO postgres;
GRANT ALL ON TABLE sge_grupo_definicion TO postgres;


-- ##ARCHIVO##ck_sge_grupo_definicion_estado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_grupo_definicion
-- Check: ck_sge_grupo_definicion_estado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_grupo_definicion DROP CONSTRAINT ck_sge_grupo_definicion_estado;
ALTER TABLE sge_grupo_definicion ADD CONSTRAINT ck_sge_grupo_definicion_estado CHECK (estado IN ('A', 'B', 'O'));


-- ##ARCHIVO##sge_grupo_definicion_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_grupo_definicion
-- Actualizacion Nro de Secuencia: sge_grupo_definicion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_grupo_definicion_seq',(SELECT MAX(grupo) FROM sge_grupo_definicion));


-- ##ARCHIVO##sge_grupo_detalle##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_grupo_detalle
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_grupo_detalle;
CREATE  TABLE sge_grupo_detalle
(
	grupo Integer NOT NULL,
	encuestado Integer NOT NULL
);

-- ALTER TABLE sge_grupo_detalle DROP CONSTRAINT pk_sge_grupo_detalle;
ALTER TABLE sge_grupo_detalle ADD CONSTRAINT pk_sge_grupo_detalle PRIMARY KEY (grupo,encuestado);
-- ++++++++++++++++++++++++++ Fin tabla sge_grupo_detalle +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_grupo_detalle##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_grupo_detalle
-- Permisos para la tabla: sge_grupo_detalle_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_grupo_detalle OWNER TO postgres;
GRANT ALL ON TABLE sge_grupo_detalle TO postgres;


-- ##ARCHIVO##sge_grupo_habilitado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_grupo_habilitado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_grupo_habilitado;
CREATE  TABLE sge_grupo_habilitado
(
	grupo Integer NOT NULL,
	formulario_habilitado Integer NOT NULL
);

-- ALTER TABLE sge_grupo_habilitado DROP CONSTRAINT pk_sge_grupo_habilitado;
ALTER TABLE sge_grupo_habilitado ADD CONSTRAINT pk_sge_grupo_habilitado PRIMARY KEY (grupo,formulario_habilitado);
-- ++++++++++++++++++++++++++ Fin tabla sge_grupo_habilitado +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_grupo_habilitado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_grupo_habilitado
-- Permisos para la tabla: sge_grupo_habilitado_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_grupo_habilitado OWNER TO postgres;
GRANT ALL ON TABLE sge_grupo_habilitado TO postgres;


-- ##ARCHIVO##mgi_propuesta_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_propuesta
-- Secuencia: mgi_propuesta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE mgi_propuesta_seq;
CREATE SEQUENCE mgi_propuesta_seq START 1;


-- ##ARCHIVO##mgi_propuesta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_propuesta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgi_propuesta;
CREATE  TABLE mgi_propuesta
(
	propuesta INTEGER NOT NULL DEFAULT nextval('mgi_propuesta_seq'::text) ,
	nombre Varchar(255) NOT NULL,
	codigo Varchar(20) NOT NULL,
	estado Char(1) NOT NULL,
	unidad_gestion Varchar
);

-- ALTER TABLE mgi_propuesta DROP CONSTRAINT pk_mgi_propuesta;
ALTER TABLE mgi_propuesta ADD CONSTRAINT pk_mgi_propuesta PRIMARY KEY (propuesta);
-- ++++++++++++++++++++++++++ Fin tabla mgi_propuesta +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_mgi_propuesta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_propuesta
-- Permisos para la tabla: mgi_propuesta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mgi_propuesta OWNER TO postgres;
GRANT ALL ON TABLE mgi_propuesta TO postgres;


-- ##ARCHIVO##mgi_propuesta_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_propuesta
-- Actualizacion Nro de Secuencia: mgi_propuesta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('mgi_propuesta_seq',(SELECT MAX(propuesta) FROM mgi_propuesta));


-- ##ARCHIVO##mgi_propuesta_ra##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_propuesta_ra
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgi_propuesta_ra;
CREATE  TABLE mgi_propuesta_ra
(
	responsable_academica Integer NOT NULL,
	propuesta Integer NOT NULL
);

-- ALTER TABLE mgi_propuesta_ra DROP CONSTRAINT pk_mgi_propuesta_ra;
ALTER TABLE mgi_propuesta_ra ADD CONSTRAINT pk_mgi_propuesta_ra PRIMARY KEY (responsable_academica,propuesta);
-- ++++++++++++++++++++++++++ Fin tabla mgi_propuesta_ra +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_mgi_propuesta_ra##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_propuesta_ra
-- Permisos para la tabla: mgi_propuesta_ra_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mgi_propuesta_ra OWNER TO postgres;
GRANT ALL ON TABLE mgi_propuesta_ra TO postgres;


-- ##ARCHIVO##mgi_titulo_propuesta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_titulo_propuesta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgi_titulo_propuesta;
CREATE  TABLE mgi_titulo_propuesta
(
	propuesta Integer NOT NULL,
	titulo Integer NOT NULL
);

-- ALTER TABLE mgi_titulo_propuesta DROP CONSTRAINT pk_mgi_titulo_propuesta;
ALTER TABLE mgi_titulo_propuesta ADD CONSTRAINT pk_mgi_titulo_propuesta PRIMARY KEY (propuesta,titulo);
-- ++++++++++++++++++++++++++ Fin tabla mgi_titulo_propuesta +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_mgi_titulo_propuesta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_titulo_propuesta
-- Permisos para la tabla: mgi_titulo_propuesta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mgi_titulo_propuesta OWNER TO postgres;
GRANT ALL ON TABLE mgi_titulo_propuesta TO postgres;


-- ##ARCHIVO##sge_encuesta_estilo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_estilo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_encuesta_estilo;
CREATE  TABLE sge_encuesta_estilo
(
	estilo Integer NOT NULL,
	nombre Varchar(60) NOT NULL,
	descripcion Varchar(255),
	archivo Varchar(255) NOT NULL
);

-- ALTER TABLE sge_encuesta_estilo DROP CONSTRAINT pk_sge_encuesta_estilo;
ALTER TABLE sge_encuesta_estilo ADD CONSTRAINT pk_sge_encuesta_estilo PRIMARY KEY (estilo);
-- ++++++++++++++++++++++++++ Fin tabla sge_encuesta_estilo +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_encuesta_estilo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_estilo
-- Permisos para la tabla: sge_encuesta_estilo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_encuesta_estilo OWNER TO postgres;
GRANT ALL ON TABLE sge_encuesta_estilo TO postgres;


-- ##ARCHIVO##mgn_mail_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_mail
-- Secuencia: mgn_mail_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE mgn_mail_seq;
CREATE SEQUENCE mgn_mail_seq START 1;


-- ##ARCHIVO##mgn_mail##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_mail
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgn_mail;
CREATE  TABLE mgn_mail
(
	mail INTEGER NOT NULL DEFAULT nextval('mgn_mail_seq'::text) ,
	asunto Varchar(200) NOT NULL,
	contenido Text NOT NULL,
	nombre Varchar(100),
	hora_envio Time,
	fecha_envio Date
);

-- ALTER TABLE mgn_mail DROP CONSTRAINT pk_mgn_mail;
ALTER TABLE mgn_mail ADD CONSTRAINT pk_mgn_mail PRIMARY KEY (mail);
-- ++++++++++++++++++++++++++ Fin tabla mgn_mail +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_mgn_mail##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_mail
-- Permisos para la tabla: mgn_mail_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mgn_mail OWNER TO postgres;
GRANT ALL ON TABLE mgn_mail TO postgres;


-- ##ARCHIVO##mgn_mail_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_mail
-- Actualizacion Nro de Secuencia: mgn_mail_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('mgn_mail_seq',(SELECT MAX(mail) FROM mgn_mail));


-- ##ARCHIVO##mgn_log_envio_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_log_envio
-- Secuencia: mgn_log_envio_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE mgn_log_envio_seq;
CREATE SEQUENCE mgn_log_envio_seq START 1;


-- ##ARCHIVO##mgn_log_envio##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_log_envio
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgn_log_envio;
CREATE  TABLE mgn_log_envio
(
	log INTEGER NOT NULL DEFAULT nextval('mgn_log_envio_seq'::text) ,
	mail Integer NOT NULL,
	encuestado Integer NOT NULL,
	mensaje Text,
	hash Varchar(200) NOT NULL
);

-- ALTER TABLE mgn_log_envio DROP CONSTRAINT pk_mgn_log_envio;
ALTER TABLE mgn_log_envio ADD CONSTRAINT pk_mgn_log_envio PRIMARY KEY (log);
-- ++++++++++++++++++++++++++ Fin tabla mgn_log_envio +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_mgn_log_envio##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_log_envio
-- Permisos para la tabla: mgn_log_envio_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mgn_log_envio OWNER TO postgres;
GRANT ALL ON TABLE mgn_log_envio TO postgres;


-- ##ARCHIVO##mgn_log_envio_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_log_envio
-- Actualizacion Nro de Secuencia: mgn_log_envio_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('mgn_log_envio_seq',(SELECT MAX(log) FROM mgn_log_envio));


-- ##ARCHIVO##sge_respondido_encuesta_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuesta
-- Secuencia: sge_respondido_encuesta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_respondido_encuesta_seq;
CREATE SEQUENCE sge_respondido_encuesta_seq START 1;


-- ##ARCHIVO##sge_respondido_encuesta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuesta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_respondido_encuesta;
CREATE  TABLE sge_respondido_encuesta
(
	respondido_encuesta INTEGER NOT NULL DEFAULT nextval('sge_respondido_encuesta_seq'::text) ,
	respondido_formulario Integer NOT NULL,
	formulario_habilitado_detalle Integer NOT NULL
);

-- ALTER TABLE sge_respondido_encuesta DROP CONSTRAINT pk_sge_respondido_encuesta;
ALTER TABLE sge_respondido_encuesta ADD CONSTRAINT pk_sge_respondido_encuesta PRIMARY KEY (respondido_encuesta);
-- ++++++++++++++++++++++++++ Fin tabla sge_respondido_encuesta +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_respondido_encuesta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuesta
-- Permisos para la tabla: sge_respondido_encuesta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_respondido_encuesta OWNER TO postgres;
GRANT ALL ON TABLE sge_respondido_encuesta TO postgres;


-- ##ARCHIVO##sge_respondido_encuesta_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuesta
-- Actualizacion Nro de Secuencia: sge_respondido_encuesta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_respondido_encuesta_seq',(SELECT MAX(respondido_encuesta) FROM sge_respondido_encuesta));


-- ##ARCHIVO##int_guarani_car_tit##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_car_tit
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS int_guarani_car_tit;
CREATE  TABLE int_guarani_car_tit
(
	fecha_proceso Char(10) NOT NULL,
	ra_codigo Varchar(5),
	carrera_codigo Varchar(5),
	titulo_codigo Varchar(5)
);


-- ++++++++++++++++++++++++++ Fin tabla int_guarani_car_tit +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_int_guarani_car_tit##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_car_tit
-- Permisos para la tabla: int_guarani_car_tit_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE int_guarani_car_tit OWNER TO postgres;
GRANT ALL ON TABLE int_guarani_car_tit TO postgres;


-- ##ARCHIVO##int_guarani_ra_car##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_ra_car
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS int_guarani_ra_car;
CREATE  TABLE int_guarani_ra_car
(
	fecha_proceso Char(10),
	ra_codigo Varchar(5),
	carrera_codigo Varchar(5)
);


-- ++++++++++++++++++++++++++ Fin tabla int_guarani_ra_car +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_int_guarani_ra_car##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_ra_car
-- Permisos para la tabla: int_guarani_ra_car_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE int_guarani_ra_car OWNER TO postgres;
GRANT ALL ON TABLE int_guarani_ra_car TO postgres;


-- ##ARCHIVO##int_guarani_carrera##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_carrera
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS int_guarani_carrera;
CREATE  TABLE int_guarani_carrera
(
	fecha_proceso Char(10),
	carrera_nombre Varchar(255),
	carrera_codigo Varchar(5),
	carrera_estado Char(1),
	unidad_gestion Varchar
);


-- ++++++++++++++++++++++++++ Fin tabla int_guarani_carrera +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_int_guarani_carrera##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_carrera
-- Permisos para la tabla: int_guarani_carrera_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE int_guarani_carrera OWNER TO postgres;
GRANT ALL ON TABLE int_guarani_carrera TO postgres;


-- ##ARCHIVO##int_guarani_ra_tit##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_ra_tit
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS int_guarani_ra_tit;
CREATE  TABLE int_guarani_ra_tit
(
	fecha_proceso Char(10),
	ra_codigo Varchar(5),
	titulo_codigo Varchar(5)
);


-- ++++++++++++++++++++++++++ Fin tabla int_guarani_ra_tit +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_int_guarani_ra_tit##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_ra_tit
-- Permisos para la tabla: int_guarani_ra_tit_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE int_guarani_ra_tit OWNER TO postgres;
GRANT ALL ON TABLE int_guarani_ra_tit TO postgres;


-- ##ARCHIVO##int_guarani_ra##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_ra
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS int_guarani_ra;
CREATE  TABLE int_guarani_ra
(
	fecha_proceso Char(10),
	ra_nombre Varchar(255),
	ra_codigo Varchar(5) NOT NULL,
	ra_tipo Integer,
	ra_institucion Varchar(5),
	ra_localidad Integer,
	ra_calle Varchar(100),
	ra_numero Varchar(10),
	ra_cp Varchar(15),
	ra_telefono Varchar(50),
	ra_fax Varchar(50),
	ra_mail Varchar(100),
	unidad_gestion Varchar
);


-- ++++++++++++++++++++++++++ Fin tabla int_guarani_ra +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_int_guarani_ra##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_ra
-- Permisos para la tabla: int_guarani_ra_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE int_guarani_ra OWNER TO postgres;
GRANT ALL ON TABLE int_guarani_ra TO postgres;


-- ##ARCHIVO##int_guarani_instit##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_instit
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS int_guarani_instit;
CREATE  TABLE int_guarani_instit
(
	fecha_proceso Char(10),
	institucion_nombre Varchar(255),
	institucion_codigo Varchar(50),
	institucion_araucano Integer
);


-- ++++++++++++++++++++++++++ Fin tabla int_guarani_instit +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_int_guarani_instit##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_instit
-- Permisos para la tabla: int_guarani_instit_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE int_guarani_instit OWNER TO postgres;
GRANT ALL ON TABLE int_guarani_instit TO postgres;


-- ##ARCHIVO##int_guarani_titulos##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_titulos
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS int_guarani_titulos;
CREATE  TABLE int_guarani_titulos
(
	fecha_proceso Char(10),
	titulo_nombre Varchar(255),
	titulo_nombre_femenino Varchar(255) NOT NULL,
	titulo_codigo Varchar(5),
	titulo_araucano Integer,
	titulo_estado Char(1),
	unidad_gestion Varchar
);


-- ++++++++++++++++++++++++++ Fin tabla int_guarani_titulos +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_int_guarani_titulos##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_titulos
-- Permisos para la tabla: int_guarani_titulos_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE int_guarani_titulos OWNER TO postgres;
GRANT ALL ON TABLE int_guarani_titulos TO postgres;


-- ##ARCHIVO##arau_titulos##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: arau_titulos
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS arau_titulos;
CREATE  TABLE arau_titulos
(
	titulo_araucano Integer NOT NULL,
	nombre Varchar(255) NOT NULL,
	tipo_titulo Varchar(5)
);

-- ALTER TABLE arau_titulos DROP CONSTRAINT pk_arau_titulos;
ALTER TABLE arau_titulos ADD CONSTRAINT pk_arau_titulos PRIMARY KEY (titulo_araucano);
-- ++++++++++++++++++++++++++ Fin tabla arau_titulos +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_arau_titulos##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: arau_titulos
-- Permisos para la tabla: arau_titulos_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE arau_titulos OWNER TO postgres;
GRANT ALL ON TABLE arau_titulos TO postgres;


-- ##ARCHIVO##arau_instituciones##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: arau_instituciones
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS arau_instituciones;
CREATE  TABLE arau_instituciones
(
	institucion_araucano Integer NOT NULL,
	nombre Varchar(255) NOT NULL
);

-- ALTER TABLE arau_instituciones DROP CONSTRAINT pk_arau_instituciones;
ALTER TABLE arau_instituciones ADD CONSTRAINT pk_arau_instituciones PRIMARY KEY (institucion_araucano);
-- ++++++++++++++++++++++++++ Fin tabla arau_instituciones +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_arau_instituciones##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: arau_instituciones
-- Permisos para la tabla: arau_instituciones_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE arau_instituciones OWNER TO postgres;
GRANT ALL ON TABLE arau_instituciones TO postgres;


-- ##ARCHIVO##arau_responsables_academicas##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: arau_responsables_academicas
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS arau_responsables_academicas;
CREATE  TABLE arau_responsables_academicas
(
	ra_araucano Integer NOT NULL,
	nombre Varchar(255) NOT NULL,
	institucion_araucano Integer
);

-- ALTER TABLE arau_responsables_academicas DROP CONSTRAINT pk_arau_responsables_academicas;
ALTER TABLE arau_responsables_academicas ADD CONSTRAINT pk_arau_responsables_academicas PRIMARY KEY (ra_araucano);
-- ++++++++++++++++++++++++++ Fin tabla arau_responsables_academicas +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_arau_responsables_academicas##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: arau_responsables_academicas
-- Permisos para la tabla: arau_responsables_academicas_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE arau_responsables_academicas OWNER TO postgres;
GRANT ALL ON TABLE arau_responsables_academicas TO postgres;


-- ##ARCHIVO##sge_reporte_tipo_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_reporte_tipo
-- Secuencia: sge_reporte_tipo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_reporte_tipo_seq;
CREATE SEQUENCE sge_reporte_tipo_seq START 1;


-- ##ARCHIVO##sge_reporte_tipo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_reporte_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_reporte_tipo;
CREATE  TABLE sge_reporte_tipo
(
	reporte_tipo INTEGER NOT NULL DEFAULT nextval('sge_reporte_tipo_seq'::text) ,
	nombre Varchar(100) NOT NULL,
	descripcion Varchar(255)
);

-- ALTER TABLE sge_reporte_tipo DROP CONSTRAINT pk_sge_reporte_tipo;
ALTER TABLE sge_reporte_tipo ADD CONSTRAINT pk_sge_reporte_tipo PRIMARY KEY (reporte_tipo);
-- ++++++++++++++++++++++++++ Fin tabla sge_reporte_tipo +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_reporte_tipo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_reporte_tipo
-- Permisos para la tabla: sge_reporte_tipo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_reporte_tipo OWNER TO postgres;
GRANT ALL ON TABLE sge_reporte_tipo TO postgres;


-- ##ARCHIVO##sge_reporte_tipo_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_reporte_tipo
-- Actualizacion Nro de Secuencia: sge_reporte_tipo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_reporte_tipo_seq',(SELECT MAX(reporte_tipo) FROM sge_reporte_tipo));


-- ##ARCHIVO##sge_reporte_exportado_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_reporte_exportado
-- Secuencia: sge_reporte_exportado_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_reporte_exportado_seq;
CREATE SEQUENCE sge_reporte_exportado_seq START 1;


-- ##ARCHIVO##sge_reporte_exportado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_reporte_exportado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_reporte_exportado;
CREATE  TABLE sge_reporte_exportado
(
	exportado_codigo INTEGER NOT NULL DEFAULT nextval('sge_reporte_exportado_seq'::text) ,
	formulario_habilitado Integer,
	reporte_tipo Integer NOT NULL,
	fecha_desde Date,
	fecha_hasta Date,
	inconclusas Integer,
	multiples Integer NOT NULL,
	archivo Varchar(255),
	codigos Integer NOT NULL DEFAULT 0,
	encuesta Integer,
	elemento Integer,
	usuario Varchar NOT NULL,
	fecha_reporte Time NOT NULL DEFAULT NOW(),
	grupo Integer,
	concepto Integer,
	habilitacion Integer,
	pregunta Integer,
	terminadas Char(1),
	filtro_pregunta Integer,
	filtro_pregunta_opcion_respuesta Integer
);

-- ALTER TABLE sge_reporte_exportado DROP CONSTRAINT pk_sge_reporte_exportado;
ALTER TABLE sge_reporte_exportado ADD CONSTRAINT pk_sge_reporte_exportado PRIMARY KEY (exportado_codigo);
-- ++++++++++++++++++++++++++ Fin tabla sge_reporte_exportado +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_reporte_exportado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_reporte_exportado
-- Permisos para la tabla: sge_reporte_exportado_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_reporte_exportado OWNER TO postgres;
GRANT ALL ON TABLE sge_reporte_exportado TO postgres;


-- ##ARCHIVO##sge_reporte_exportado_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_reporte_exportado
-- Actualizacion Nro de Secuencia: sge_reporte_exportado_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_reporte_exportado_seq',(SELECT MAX(exportado_codigo) FROM sge_reporte_exportado));


-- ##ARCHIVO##sge_documento_tipo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_documento_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_documento_tipo;
CREATE  TABLE sge_documento_tipo
(
	documento_tipo Integer NOT NULL,
	descripcion Varchar(40) NOT NULL
);

-- ALTER TABLE sge_documento_tipo DROP CONSTRAINT pk_sge_documento_tipo;
ALTER TABLE sge_documento_tipo ADD CONSTRAINT pk_sge_documento_tipo PRIMARY KEY (documento_tipo);
-- ++++++++++++++++++++++++++ Fin tabla sge_documento_tipo +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_documento_tipo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_documento_tipo
-- Permisos para la tabla: sge_documento_tipo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_documento_tipo OWNER TO postgres;
GRANT ALL ON TABLE sge_documento_tipo TO postgres;


-- ##ARCHIVO##int_guarani_persona##
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

-- ##ARCHIVO##grant_int_guarani_persona##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_persona
-- Permisos para la tabla: int_guarani_persona_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE int_guarani_persona OWNER TO postgres;
GRANT ALL ON TABLE int_guarani_persona TO postgres;


-- ##ARCHIVO##sge_encuesta_indicador##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_indicador
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_encuesta_indicador;
CREATE  TABLE sge_encuesta_indicador
(
	encuesta_definicion Integer NOT NULL,
	encuesta Integer NOT NULL
);

-- ALTER TABLE sge_encuesta_indicador DROP CONSTRAINT pk_sge_encuesta_indicador;
ALTER TABLE sge_encuesta_indicador ADD CONSTRAINT pk_sge_encuesta_indicador PRIMARY KEY (encuesta_definicion,encuesta);
-- ++++++++++++++++++++++++++ Fin tabla sge_encuesta_indicador +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_encuesta_indicador##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_indicador
-- Permisos para la tabla: sge_encuesta_indicador_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_encuesta_indicador OWNER TO postgres;
GRANT ALL ON TABLE sge_encuesta_indicador TO postgres;


-- ##ARCHIVO##sge_formulario_habilitado_indicador##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado_indicador
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_formulario_habilitado_indicador;
CREATE  TABLE sge_formulario_habilitado_indicador
(
	encuesta_definicion Integer NOT NULL,
	formulario_habilitado_detalle Integer NOT NULL,
	formulario_habilitado Integer NOT NULL
);

-- ALTER TABLE sge_formulario_habilitado_indicador DROP CONSTRAINT pk_sge_formulario_habilitado_indicador;
ALTER TABLE sge_formulario_habilitado_indicador ADD CONSTRAINT pk_sge_formulario_habilitado_indicador PRIMARY KEY (encuesta_definicion,formulario_habilitado_detalle,formulario_habilitado);
-- ++++++++++++++++++++++++++ Fin tabla sge_formulario_habilitado_indicador +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_formulario_habilitado_indicador##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado_indicador
-- Permisos para la tabla: sge_formulario_habilitado_indicador_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_formulario_habilitado_indicador OWNER TO postgres;
GRANT ALL ON TABLE sge_formulario_habilitado_indicador TO postgres;


-- ##ARCHIVO##sge_ws_conexion_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_ws_conexion
-- Secuencia: sge_ws_conexion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_ws_conexion_seq;
CREATE SEQUENCE sge_ws_conexion_seq START 1;


-- ##ARCHIVO##sge_ws_conexion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_ws_conexion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_ws_conexion;
CREATE  TABLE sge_ws_conexion
(
	conexion INTEGER NOT NULL DEFAULT nextval('sge_ws_conexion_seq'::text) ,
	unidad_gestion Varchar,
	conexion_nombre Varchar(100),
	ws_url Varchar(100),
	ws_user Varchar(60),
	ws_clave Varchar(200),
	activa Char(1),
	ws_tipo Char(4) NOT NULL DEFAULT 'rest'
);

-- ALTER TABLE sge_ws_conexion DROP CONSTRAINT pk_sge_ws_conexion;
ALTER TABLE sge_ws_conexion ADD CONSTRAINT pk_sge_ws_conexion PRIMARY KEY (conexion);
-- ++++++++++++++++++++++++++ Fin tabla sge_ws_conexion +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_ws_conexion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_ws_conexion
-- Permisos para la tabla: sge_ws_conexion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_ws_conexion OWNER TO postgres;
GRANT ALL ON TABLE sge_ws_conexion TO postgres;


-- ##ARCHIVO##ck_sge_ws_conexion_ws_tipo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_ws_conexion
-- Check: ck_sge_ws_conexion_ws_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_ws_conexion DROP CONSTRAINT ck_sge_ws_conexion_ws_tipo;
ALTER TABLE sge_ws_conexion ADD CONSTRAINT ck_sge_ws_conexion_ws_tipo CHECK (ws_tipo IN ('rest', 'soap'));
-- ##ARCHIVO##sge_ws_conexion_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_ws_conexion
-- Actualizacion Nro de Secuencia: sge_ws_conexion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_ws_conexion_seq',(SELECT MAX(conexion) FROM sge_ws_conexion));


-- ##ARCHIVO##sge_elemento_concepto_tipo_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_elemento_concepto_tipo
-- Secuencia: sge_elemento_concepto_tipo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_elemento_concepto_tipo_seq;
CREATE SEQUENCE sge_elemento_concepto_tipo_seq START 1;


-- ##ARCHIVO##sge_elemento_concepto_tipo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_elemento_concepto_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_elemento_concepto_tipo;
CREATE  TABLE sge_elemento_concepto_tipo
(
	elemento_concepto INTEGER NOT NULL DEFAULT nextval('sge_elemento_concepto_tipo_seq'::text) ,
	elemento Integer NOT NULL,
	concepto Integer NOT NULL,
	tipo_elemento Integer NOT NULL
);

-- ALTER TABLE sge_elemento_concepto_tipo DROP CONSTRAINT pk_sge_elemento_concepto_tipo;
ALTER TABLE sge_elemento_concepto_tipo ADD CONSTRAINT pk_sge_elemento_concepto_tipo PRIMARY KEY (elemento_concepto);
-- ++++++++++++++++++++++++++ Fin tabla sge_elemento_concepto_tipo +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_elemento_concepto_tipo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_elemento_concepto_tipo
-- Permisos para la tabla: sge_elemento_concepto_tipo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_elemento_concepto_tipo OWNER TO postgres;
GRANT ALL ON TABLE sge_elemento_concepto_tipo TO postgres;


-- ##ARCHIVO##sge_elemento_concepto_tipo_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_elemento_concepto_tipo
-- Actualizacion Nro de Secuencia: sge_elemento_concepto_tipo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_elemento_concepto_tipo_seq',(SELECT MAX(elemento_concepto) FROM sge_elemento_concepto_tipo));


-- ##ARCHIVO##sge_concepto_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_concepto
-- Secuencia: sge_concepto_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_concepto_seq;
CREATE SEQUENCE sge_concepto_seq START 1;


-- ##ARCHIVO##sge_concepto##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_concepto
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_concepto;
CREATE  TABLE sge_concepto
(
	concepto INTEGER NOT NULL DEFAULT nextval('sge_concepto_seq'::text) ,
	concepto_externo Varchar(100),
	descripcion Text NOT NULL,
	unidad_gestion Varchar NOT NULL,
	sistema Integer
);

-- ALTER TABLE sge_concepto DROP CONSTRAINT pk_sge_concepto;
ALTER TABLE sge_concepto ADD CONSTRAINT pk_sge_concepto PRIMARY KEY (concepto);
-- ++++++++++++++++++++++++++ Fin tabla sge_concepto +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_concepto##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_concepto
-- Permisos para la tabla: sge_concepto_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_concepto OWNER TO postgres;
GRANT ALL ON TABLE sge_concepto TO postgres;


-- ##ARCHIVO##sge_concepto_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_concepto
-- Actualizacion Nro de Secuencia: sge_concepto_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_concepto_seq',(SELECT MAX(concepto) FROM sge_concepto));


-- ##ARCHIVO##sge_sistema_externo_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_sistema_externo
-- Secuencia: sge_sistema_externo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_sistema_externo_seq;
CREATE SEQUENCE sge_sistema_externo_seq START 1;


-- ##ARCHIVO##sge_sistema_externo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_sistema_externo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_sistema_externo;
CREATE  TABLE sge_sistema_externo
(
	sistema INTEGER NOT NULL DEFAULT nextval('sge_sistema_externo_seq'::text) ,
	usuario Varchar(60) NOT NULL,
	nombre Varchar(100) NOT NULL,
	estado Char(1) NOT NULL
);

-- ALTER TABLE sge_sistema_externo DROP CONSTRAINT pk_sge_sistema_externo;
ALTER TABLE sge_sistema_externo ADD CONSTRAINT pk_sge_sistema_externo PRIMARY KEY (sistema);
-- ++++++++++++++++++++++++++ Fin tabla sge_sistema_externo +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_sistema_externo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_sistema_externo
-- Permisos para la tabla: sge_sistema_externo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_sistema_externo OWNER TO postgres;
GRANT ALL ON TABLE sge_sistema_externo TO postgres;


-- ##ARCHIVO##ck_sge_sistema_externo_estado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_sistema_externo
-- Check: ck_sge_sistema_externo_estado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_sistema_externo DROP CONSTRAINT ck_sge_sistema_externo_estado;
ALTER TABLE sge_sistema_externo ADD CONSTRAINT ck_sge_sistema_externo_estado CHECK (estado IN ('A', 'B'));
-- ##ARCHIVO##sge_sistema_externo_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_sistema_externo
-- Actualizacion Nro de Secuencia: sge_sistema_externo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_sistema_externo_seq',(SELECT MAX(sistema) FROM sge_sistema_externo));


-- ##ARCHIVO##mgn_encuesta_externa_log_envio##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_encuesta_externa_log_envio
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgn_encuesta_externa_log_envio;
CREATE  TABLE mgn_encuesta_externa_log_envio
(
	estado Char(1) NOT NULL,
	fecha_envio Date NOT NULL,
	hora_envio Time NOT NULL,
	log Varchar(250)
);


-- ++++++++++++++++++++++++++ Fin tabla mgn_encuesta_externa_log_envio +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_mgn_encuesta_externa_log_envio##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_encuesta_externa_log_envio
-- Permisos para la tabla: mgn_encuesta_externa_log_envio_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mgn_encuesta_externa_log_envio OWNER TO postgres;
GRANT ALL ON TABLE mgn_encuesta_externa_log_envio TO postgres;


-- ##ARCHIVO##sge_elemento_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_elemento
-- Secuencia: sge_elemento_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_elemento_seq;
CREATE SEQUENCE sge_elemento_seq START 1;


-- ##ARCHIVO##sge_elemento##
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

-- ##ARCHIVO##grant_sge_elemento##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_elemento
-- Permisos para la tabla: sge_elemento_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_elemento OWNER TO postgres;
GRANT ALL ON TABLE sge_elemento TO postgres;


-- ##ARCHIVO##sge_elemento_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_elemento
-- Actualizacion Nro de Secuencia: sge_elemento_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_elemento_seq',(SELECT MAX(elemento) FROM sge_elemento));


-- ##ARCHIVO##sge_tipo_elemento_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tipo_elemento
-- Secuencia: sge_tipo_elemento_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_tipo_elemento_seq;
CREATE SEQUENCE sge_tipo_elemento_seq START 1;


-- ##ARCHIVO##sge_tipo_elemento##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tipo_elemento
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_tipo_elemento;
CREATE  TABLE sge_tipo_elemento
(
	tipo_elemento INTEGER NOT NULL DEFAULT nextval('sge_tipo_elemento_seq'::text) ,
	descripcion Varchar(100),
	unidad_gestion Varchar,
	tipo_elemento_externo Varchar(100),
	sistema Integer
);

-- ALTER TABLE sge_tipo_elemento DROP CONSTRAINT pk_sge_tipo_elemento;
ALTER TABLE sge_tipo_elemento ADD CONSTRAINT pk_sge_tipo_elemento PRIMARY KEY (tipo_elemento);
-- ++++++++++++++++++++++++++ Fin tabla sge_tipo_elemento +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_tipo_elemento##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tipo_elemento
-- Permisos para la tabla: sge_tipo_elemento_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_tipo_elemento OWNER TO postgres;
GRANT ALL ON TABLE sge_tipo_elemento TO postgres;


-- ##ARCHIVO##sge_tipo_elemento_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tipo_elemento
-- Actualizacion Nro de Secuencia: sge_tipo_elemento_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_tipo_elemento_seq',(SELECT MAX(tipo_elemento) FROM sge_tipo_elemento));


-- ##ARCHIVO##sge_formulario_habilitado_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado
-- Secuencia: sge_formulario_habilitado_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_formulario_habilitado_seq;
CREATE SEQUENCE sge_formulario_habilitado_seq START 1;


-- ##ARCHIVO##sge_formulario_habilitado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_formulario_habilitado;
CREATE  TABLE sge_formulario_habilitado
(
	formulario_habilitado INTEGER NOT NULL DEFAULT nextval('sge_formulario_habilitado_seq'::text) ,
	habilitacion Integer NOT NULL,
	concepto Integer,
	nombre Text NOT NULL,
	estado Char(1) NOT NULL DEFAULT 'A',
	formulario_habilitado_externo Varchar(100)
);

-- ALTER TABLE sge_formulario_habilitado DROP CONSTRAINT pk_sge_formulario_habilitado;
ALTER TABLE sge_formulario_habilitado ADD CONSTRAINT pk_sge_formulario_habilitado PRIMARY KEY (formulario_habilitado);
-- ++++++++++++++++++++++++++ Fin tabla sge_formulario_habilitado +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_formulario_habilitado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado
-- Permisos para la tabla: sge_formulario_habilitado_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_formulario_habilitado OWNER TO postgres;
GRANT ALL ON TABLE sge_formulario_habilitado TO postgres;


-- ##ARCHIVO##ck_sge_formulario_habilitado_estado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado
-- Check: ck_sge_formulario_habilitado_estado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_formulario_habilitado DROP CONSTRAINT ck_sge_formulario_habilitado_estado;
ALTER TABLE sge_formulario_habilitado ADD CONSTRAINT ck_sge_formulario_habilitado_estado CHECK (estado IN ('A', 'B'));

-- ##ARCHIVO##sge_formulario_habilitado_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado
-- Actualizacion Nro de Secuencia: sge_formulario_habilitado_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_formulario_habilitado_seq',(SELECT MAX(formulario_habilitado) FROM sge_formulario_habilitado));


-- ##ARCHIVO##sge_formulario_definicion_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_definicion
-- Secuencia: sge_formulario_definicion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_formulario_definicion_seq;
CREATE SEQUENCE sge_formulario_definicion_seq START 1;


-- ##ARCHIVO##sge_formulario_definicion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_definicion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_formulario_definicion;
CREATE  TABLE sge_formulario_definicion
(
	formulario_definicion INTEGER NOT NULL DEFAULT nextval('sge_formulario_definicion_seq'::text) ,
	formulario Integer NOT NULL,
	encuesta Integer NOT NULL,
	tipo_elemento Integer,
	orden Smallint NOT NULL
);

-- ALTER TABLE sge_formulario_definicion DROP CONSTRAINT pk_sge_formulario_definicion;
ALTER TABLE sge_formulario_definicion ADD CONSTRAINT pk_sge_formulario_definicion PRIMARY KEY (formulario_definicion);
-- ++++++++++++++++++++++++++ Fin tabla sge_formulario_definicion +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_formulario_definicion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_definicion
-- Permisos para la tabla: sge_formulario_definicion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_formulario_definicion OWNER TO postgres;
GRANT ALL ON TABLE sge_formulario_definicion TO postgres;


-- ##ARCHIVO##sge_formulario_definicion_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_definicion
-- Actualizacion Nro de Secuencia: sge_formulario_definicion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_formulario_definicion_seq',(SELECT MAX(formulario_definicion) FROM sge_formulario_definicion));


-- ##ARCHIVO##sge_formulario_habilitado_detalle_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado_detalle
-- Secuencia: sge_formulario_habilitado_detalle_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_formulario_habilitado_detalle_seq;
CREATE SEQUENCE sge_formulario_habilitado_detalle_seq START 1;


-- ##ARCHIVO##sge_formulario_habilitado_detalle##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado_detalle
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_formulario_habilitado_detalle;
CREATE  TABLE sge_formulario_habilitado_detalle
(
	formulario_habilitado_detalle INTEGER NOT NULL DEFAULT nextval('sge_formulario_habilitado_detalle_seq'::text) ,
	formulario_habilitado Integer NOT NULL,
	encuesta Integer NOT NULL,
	elemento Integer,
	orden Integer NOT NULL,
	tipo_elemento Integer
);

-- ALTER TABLE sge_formulario_habilitado_detalle DROP CONSTRAINT pk_sge_formulario_habilitado_detalle;
ALTER TABLE sge_formulario_habilitado_detalle ADD CONSTRAINT pk_sge_formulario_habilitado_detalle PRIMARY KEY (formulario_habilitado_detalle);
-- ++++++++++++++++++++++++++ Fin tabla sge_formulario_habilitado_detalle +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_formulario_habilitado_detalle##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado_detalle
-- Permisos para la tabla: sge_formulario_habilitado_detalle_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_formulario_habilitado_detalle OWNER TO postgres;
GRANT ALL ON TABLE sge_formulario_habilitado_detalle TO postgres;


-- ##ARCHIVO##sge_formulario_habilitado_detalle_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado_detalle
-- Actualizacion Nro de Secuencia: sge_formulario_habilitado_detalle_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_formulario_habilitado_detalle_seq',(SELECT MAX(formulario_habilitado_detalle) FROM sge_formulario_habilitado_detalle));


-- ##ARCHIVO##sge_respondido_formulario_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_formulario
-- Secuencia: sge_respondido_formulario_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_respondido_formulario_seq;
CREATE SEQUENCE sge_respondido_formulario_seq START 1;


-- ##ARCHIVO##sge_respondido_formulario##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_formulario
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_respondido_formulario;
CREATE  TABLE sge_respondido_formulario
(
	respondido_formulario INTEGER NOT NULL DEFAULT nextval('sge_respondido_formulario_seq'::text) ,
	formulario_habilitado Integer NOT NULL,
	ingreso Integer,
	fecha Date,
	codigo_recuperacion Integer,
	version_digest Varchar(16) NOT NULL,
	terminado Char(1),
	fecha_terminado Date
);

-- ALTER TABLE sge_respondido_formulario DROP CONSTRAINT pk_sge_respondido_formulario;
ALTER TABLE sge_respondido_formulario ADD CONSTRAINT pk_sge_respondido_formulario PRIMARY KEY (respondido_formulario);
-- ++++++++++++++++++++++++++ Fin tabla sge_respondido_formulario +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_respondido_formulario##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_formulario
-- Permisos para la tabla: sge_respondido_formulario_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_respondido_formulario OWNER TO postgres;
GRANT ALL ON TABLE sge_respondido_formulario TO postgres;


-- ##ARCHIVO##ck_sge_respondido_formulario_terminado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_formulario
-- Check: ck_sge_respondido_formulario_terminado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_respondido_formulario DROP CONSTRAINT ck_sge_respondido_formulario_terminado;
ALTER TABLE sge_respondido_formulario ADD CONSTRAINT ck_sge_respondido_formulario_terminado CHECK (terminado IN ('S', 'N'));

-- ##ARCHIVO##sge_respondido_formulario_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_formulario
-- Actualizacion Nro de Secuencia: sge_respondido_formulario_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_respondido_formulario_seq',(SELECT MAX(respondido_formulario) FROM sge_respondido_formulario));


-- ##ARCHIVO##sge_respuesta_moderadas_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respuesta_moderadas
-- Secuencia: sge_respuesta_moderadas_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_respuesta_moderadas_seq;
CREATE SEQUENCE sge_respuesta_moderadas_seq START 1;


-- ##ARCHIVO##sge_respuesta_moderadas##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respuesta_moderadas
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_respuesta_moderadas;
CREATE  TABLE sge_respuesta_moderadas
(
	respuesta_moderada INTEGER NOT NULL DEFAULT nextval('sge_respuesta_moderadas_seq'::text) ,
	respondido_detalle Integer NOT NULL,
	texto_original Varchar NOT NULL,
	texto_nuevo Varchar NOT NULL,
	motivo Varchar NOT NULL,
	usuario Varchar(63) NOT NULL,
	fecha Timestamp with time zone NOT NULL,
	motivo_baja Varchar,
	usuario_baja Varchar(63),
	fecha_baja Timestamp with time zone
);

-- ALTER TABLE sge_respuesta_moderadas DROP CONSTRAINT pk_sge_respuesta_moderadas;
ALTER TABLE sge_respuesta_moderadas ADD CONSTRAINT pk_sge_respuesta_moderadas PRIMARY KEY (respuesta_moderada);
-- ++++++++++++++++++++++++++ Fin tabla sge_respuesta_moderadas +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_respuesta_moderadas##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respuesta_moderadas
-- Permisos para la tabla: sge_respuesta_moderadas_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_respuesta_moderadas OWNER TO postgres;
GRANT ALL ON TABLE sge_respuesta_moderadas TO postgres;


-- ##ARCHIVO##sge_respuesta_moderadas_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respuesta_moderadas
-- Actualizacion Nro de Secuencia: sge_respuesta_moderadas_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_respuesta_moderadas_seq',(SELECT MAX(respuesta_moderada) FROM sge_respuesta_moderadas));


-- ##ARCHIVO##sge_formulario_atributo_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_atributo
-- Secuencia: sge_formulario_atributo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_formulario_atributo_seq;
CREATE SEQUENCE sge_formulario_atributo_seq START 1;


-- ##ARCHIVO##sge_formulario_atributo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_atributo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_formulario_atributo;
CREATE  TABLE sge_formulario_atributo
(
	formulario INTEGER NOT NULL DEFAULT nextval('sge_formulario_atributo_seq'::text) ,
	nombre Varchar(255) NOT NULL,
	descripcion Varchar,
	texto_preliminar Varchar,
	estado Char(1) NOT NULL DEFAULT 'N'
);

-- ALTER TABLE sge_formulario_atributo DROP CONSTRAINT pk_sge_formulario_atributo;
ALTER TABLE sge_formulario_atributo ADD CONSTRAINT pk_sge_formulario_atributo PRIMARY KEY (formulario);
-- ++++++++++++++++++++++++++ Fin tabla sge_formulario_atributo +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_formulario_atributo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_atributo
-- Permisos para la tabla: sge_formulario_atributo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_formulario_atributo OWNER TO postgres;
GRANT ALL ON TABLE sge_formulario_atributo TO postgres;


-- ##ARCHIVO##ck_sge_formulario_atributo_estado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_atributo
-- Check: ck_sge_formulario_atributo_estado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_formulario_atributo DROP CONSTRAINT ck_sge_formulario_atributo_estado;
ALTER TABLE sge_formulario_atributo ADD CONSTRAINT ck_sge_formulario_atributo_estado CHECK (estado IN ('A', 'I'));
-- ##ARCHIVO##sge_formulario_atributo_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_atributo
-- Actualizacion Nro de Secuencia: sge_formulario_atributo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_formulario_atributo_seq',(SELECT MAX(formulario) FROM sge_formulario_atributo));


-- ##ARCHIVO##int_persona_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_persona
-- Secuencia: int_persona_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE int_persona_seq;
CREATE SEQUENCE int_persona_seq START 1;


-- ##ARCHIVO##int_persona##
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

-- ##ARCHIVO##grant_int_persona##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_persona
-- Permisos para la tabla: int_persona_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE int_persona OWNER TO postgres;
GRANT ALL ON TABLE int_persona TO postgres;


-- ##ARCHIVO##int_persona_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_persona
-- Actualizacion Nro de Secuencia: int_persona_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('int_persona_seq',(SELECT MAX(persona) FROM int_persona));


-- ##ARCHIVO##ing_vive##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_vive
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_vive;
CREATE  TABLE ing_vive
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_vive DROP CONSTRAINT pk_ing_vive;
ALTER TABLE ing_vive ADD CONSTRAINT pk_ing_vive PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_vive +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_ing_vive##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_vive
-- Permisos para la tabla: ing_vive_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE ing_vive OWNER TO postgres;
GRANT ALL ON TABLE ing_vive TO postgres;


-- ##ARCHIVO##ing_cantidad_horas_semanales##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_cantidad_horas_semanales
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_cantidad_horas_semanales;
CREATE  TABLE ing_cantidad_horas_semanales
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_cantidad_horas_semanales DROP CONSTRAINT pk_ing_cantidad_horas_semanales;
ALTER TABLE ing_cantidad_horas_semanales ADD CONSTRAINT pk_ing_cantidad_horas_semanales PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_cantidad_horas_semanales +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_ing_cantidad_horas_semanales##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_cantidad_horas_semanales
-- Permisos para la tabla: ing_cantidad_horas_semanales_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE ing_cantidad_horas_semanales OWNER TO postgres;
GRANT ALL ON TABLE ing_cantidad_horas_semanales TO postgres;


-- ##ARCHIVO##ing_cantidad_personas##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_cantidad_personas
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_cantidad_personas;
CREATE  TABLE ing_cantidad_personas
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_cantidad_personas DROP CONSTRAINT pk_ing_cantidad_personas;
ALTER TABLE ing_cantidad_personas ADD CONSTRAINT pk_ing_cantidad_personas PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_cantidad_personas +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_ing_cantidad_personas##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_cantidad_personas
-- Permisos para la tabla: ing_cantidad_personas_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE ing_cantidad_personas OWNER TO postgres;
GRANT ALL ON TABLE ing_cantidad_personas TO postgres;


-- ##ARCHIVO##ing_condicion_actividad##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_condicion_actividad
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_condicion_actividad;
CREATE  TABLE ing_condicion_actividad
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_condicion_actividad DROP CONSTRAINT pk_ing_condicion_actividad;
ALTER TABLE ing_condicion_actividad ADD CONSTRAINT pk_ing_condicion_actividad PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_condicion_actividad +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_ing_condicion_actividad##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_condicion_actividad
-- Permisos para la tabla: ing_condicion_actividad_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE ing_condicion_actividad OWNER TO postgres;
GRANT ALL ON TABLE ing_condicion_actividad TO postgres;


-- ##ARCHIVO##ing_estado_civil##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_estado_civil
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_estado_civil;
CREATE  TABLE ing_estado_civil
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_estado_civil DROP CONSTRAINT pk_ing_estado_civil;
ALTER TABLE ing_estado_civil ADD CONSTRAINT pk_ing_estado_civil PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_estado_civil +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_ing_estado_civil##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_estado_civil
-- Permisos para la tabla: ing_estado_civil_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE ing_estado_civil OWNER TO postgres;
GRANT ALL ON TABLE ing_estado_civil TO postgres;


-- ##ARCHIVO##ing_genero##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_genero
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_genero;
CREATE  TABLE ing_genero
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_genero DROP CONSTRAINT pk_ing_genero;
ALTER TABLE ing_genero ADD CONSTRAINT pk_ing_genero PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_genero +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_ing_genero##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_genero
-- Permisos para la tabla: ing_genero_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE ing_genero OWNER TO postgres;
GRANT ALL ON TABLE ing_genero TO postgres;


-- ##ARCHIVO##ing_nivel_instruccion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_nivel_instruccion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_nivel_instruccion;
CREATE  TABLE ing_nivel_instruccion
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_nivel_instruccion DROP CONSTRAINT pk_ing_nivel_instruccion;
ALTER TABLE ing_nivel_instruccion ADD CONSTRAINT pk_ing_nivel_instruccion PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_nivel_instruccion +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_ing_nivel_instruccion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_nivel_instruccion
-- Permisos para la tabla: ing_nivel_instruccion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE ing_nivel_instruccion OWNER TO postgres;
GRANT ALL ON TABLE ing_nivel_instruccion TO postgres;


-- ##ARCHIVO##ing_no_trabaja_no_busca##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_no_trabaja_no_busca
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_no_trabaja_no_busca;
CREATE  TABLE ing_no_trabaja_no_busca
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_no_trabaja_no_busca DROP CONSTRAINT pk_ing_no_trabaja_no_busca;
ALTER TABLE ing_no_trabaja_no_busca ADD CONSTRAINT pk_ing_no_trabaja_no_busca PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_no_trabaja_no_busca +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_ing_no_trabaja_no_busca##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_no_trabaja_no_busca
-- Permisos para la tabla: ing_no_trabaja_no_busca_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE ing_no_trabaja_no_busca OWNER TO postgres;
GRANT ALL ON TABLE ing_no_trabaja_no_busca TO postgres;


-- ##ARCHIVO##ing_ocupacion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_ocupacion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_ocupacion;
CREATE  TABLE ing_ocupacion
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_ocupacion DROP CONSTRAINT pk_ing_ocupacion;
ALTER TABLE ing_ocupacion ADD CONSTRAINT pk_ing_ocupacion PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_ocupacion +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_ing_ocupacion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_ocupacion
-- Permisos para la tabla: ing_ocupacion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE ing_ocupacion OWNER TO postgres;
GRANT ALL ON TABLE ing_ocupacion TO postgres;


-- ##ARCHIVO##ing_relacion_carrera##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_relacion_carrera
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_relacion_carrera;
CREATE  TABLE ing_relacion_carrera
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_relacion_carrera DROP CONSTRAINT pk_ing_relacion_carrera;
ALTER TABLE ing_relacion_carrera ADD CONSTRAINT pk_ing_relacion_carrera PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_relacion_carrera +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_ing_relacion_carrera##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_relacion_carrera
-- Permisos para la tabla: ing_relacion_carrera_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE ing_relacion_carrera OWNER TO postgres;
GRANT ALL ON TABLE ing_relacion_carrera TO postgres;


-- ##ARCHIVO##ing_tipo_documento##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_tipo_documento
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_tipo_documento;
CREATE  TABLE ing_tipo_documento
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_tipo_documento DROP CONSTRAINT pk_ing_tipo_documento;
ALTER TABLE ing_tipo_documento ADD CONSTRAINT pk_ing_tipo_documento PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_tipo_documento +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_ing_tipo_documento##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_tipo_documento
-- Permisos para la tabla: ing_tipo_documento_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE ing_tipo_documento OWNER TO postgres;
GRANT ALL ON TABLE ing_tipo_documento TO postgres;


-- ##ARCHIVO##ing_tipo_trabajo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_tipo_trabajo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_tipo_trabajo;
CREATE  TABLE ing_tipo_trabajo
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_tipo_trabajo DROP CONSTRAINT pk_ing_tipo_trabajo;
ALTER TABLE ing_tipo_trabajo ADD CONSTRAINT pk_ing_tipo_trabajo PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_tipo_trabajo +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_ing_tipo_trabajo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_tipo_trabajo
-- Permisos para la tabla: ing_tipo_trabajo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE ing_tipo_trabajo OWNER TO postgres;
GRANT ALL ON TABLE ing_tipo_trabajo TO postgres;


-- ##ARCHIVO##sge_parametro_configuracion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_parametro_configuracion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_parametro_configuracion;
CREATE  TABLE sge_parametro_configuracion
(
	seccion Varchar NOT NULL,
	parametro Varchar NOT NULL,
	valor Varchar
);

-- ALTER TABLE sge_parametro_configuracion DROP CONSTRAINT pk_sge_parametro_configuracion;
ALTER TABLE sge_parametro_configuracion ADD CONSTRAINT pk_sge_parametro_configuracion PRIMARY KEY (seccion,parametro);
-- ++++++++++++++++++++++++++ Fin tabla sge_parametro_configuracion +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_parametro_configuracion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_parametro_configuracion
-- Permisos para la tabla: sge_parametro_configuracion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_parametro_configuracion OWNER TO postgres;
GRANT ALL ON TABLE sge_parametro_configuracion TO postgres;


-- ##ARCHIVO##int_ingenieria_relevamiento##
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

-- ##ARCHIVO##grant_int_ingenieria_relevamiento##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_ingenieria_relevamiento
-- Permisos para la tabla: int_ingenieria_relevamiento_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE int_ingenieria_relevamiento OWNER TO postgres;
GRANT ALL ON TABLE int_ingenieria_relevamiento TO postgres;


-- ##ARCHIVO##sge_log_formulario_definicion_habilitacion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_log_formulario_definicion_habilitacion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_log_formulario_definicion_habilitacion;
CREATE  TABLE sge_log_formulario_definicion_habilitacion
(
	habilitacion Integer NOT NULL,
	encuesta Integer NOT NULL,
	tipo_elemento Integer,
	orden Smallint NOT NULL
);


-- ++++++++++++++++++++++++++ Fin tabla sge_log_formulario_definicion_habilitacion +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_log_formulario_definicion_habilitacion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_log_formulario_definicion_habilitacion
-- Permisos para la tabla: sge_log_formulario_definicion_habilitacion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_log_formulario_definicion_habilitacion OWNER TO postgres;
GRANT ALL ON TABLE sge_log_formulario_definicion_habilitacion TO postgres;


-- ##ARCHIVO##sge_respondido_por##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_por
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_respondido_por;
CREATE  TABLE sge_respondido_por
(
	respondido_formulario Integer NOT NULL,
	encuestado Integer NOT NULL
);

-- ALTER TABLE sge_respondido_por DROP CONSTRAINT pk_sge_respondido_por;
ALTER TABLE sge_respondido_por ADD CONSTRAINT pk_sge_respondido_por PRIMARY KEY (respondido_formulario,encuestado);
-- ++++++++++++++++++++++++++ Fin tabla sge_respondido_por +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_respondido_por##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_por
-- Permisos para la tabla: sge_respondido_por_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_respondido_por OWNER TO postgres;
GRANT ALL ON TABLE sge_respondido_por TO postgres;


-- ##ARCHIVO##sge_unidad_gestion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_unidad_gestion;
CREATE  TABLE sge_unidad_gestion
(
	unidad_gestion Varchar NOT NULL,
	nombre Varchar NOT NULL
);

-- ALTER TABLE sge_unidad_gestion DROP CONSTRAINT pk_sge_unidad_gestion;
ALTER TABLE sge_unidad_gestion ADD CONSTRAINT pk_sge_unidad_gestion PRIMARY KEY (unidad_gestion);
-- ++++++++++++++++++++++++++ Fin tabla sge_unidad_gestion +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_unidad_gestion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_unidad_gestion
-- Permisos para la tabla: sge_unidad_gestion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_unidad_gestion OWNER TO postgres;
GRANT ALL ON TABLE sge_unidad_gestion TO postgres;


-- ##ARCHIVO##sge_pregunta_dependencia_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_dependencia
-- Secuencia: sge_pregunta_dependencia_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_pregunta_dependencia_seq;
CREATE SEQUENCE sge_pregunta_dependencia_seq START 1;


-- ##ARCHIVO##sge_pregunta_dependencia##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_dependencia
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_pregunta_dependencia;
CREATE  TABLE sge_pregunta_dependencia
(
	pregunta_dependencia INTEGER NOT NULL DEFAULT nextval('sge_pregunta_dependencia_seq'::text) ,
	encuesta_definicion Integer NOT NULL
);

-- ALTER TABLE sge_pregunta_dependencia DROP CONSTRAINT pk_sge_pregunta_dependencia;
ALTER TABLE sge_pregunta_dependencia ADD CONSTRAINT pk_sge_pregunta_dependencia PRIMARY KEY (pregunta_dependencia);
-- ++++++++++++++++++++++++++ Fin tabla sge_pregunta_dependencia +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_pregunta_dependencia##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_dependencia
-- Permisos para la tabla: sge_pregunta_dependencia_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_pregunta_dependencia OWNER TO postgres;
GRANT ALL ON TABLE sge_pregunta_dependencia TO postgres;


-- ##ARCHIVO##sge_pregunta_dependencia_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_dependencia
-- Actualizacion Nro de Secuencia: sge_pregunta_dependencia_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_pregunta_dependencia_seq',(SELECT MAX(pregunta_dependencia) FROM sge_pregunta_dependencia));


-- ##ARCHIVO##sge_pregunta_dependencia_definicion_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_dependencia_definicion
-- Secuencia: sge_pregunta_dependencia_definicion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_pregunta_dependencia_definicion_seq;
CREATE SEQUENCE sge_pregunta_dependencia_definicion_seq START 1;


-- ##ARCHIVO##sge_pregunta_dependencia_definicion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_dependencia_definicion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_pregunta_dependencia_definicion;
CREATE  TABLE sge_pregunta_dependencia_definicion
(
	dependencia_definicion INTEGER NOT NULL DEFAULT nextval('sge_pregunta_dependencia_definicion_seq'::text) ,
	pregunta_dependencia Integer NOT NULL,
	bloque Integer NOT NULL,
	pregunta Integer,
	condicion Varchar NOT NULL,
	valor Varchar,
	accion Varchar NOT NULL,
	encuesta_definicion Integer
);

-- ALTER TABLE sge_pregunta_dependencia_definicion DROP CONSTRAINT pk_sge_pregunta_dependencia_definicion;
ALTER TABLE sge_pregunta_dependencia_definicion ADD CONSTRAINT pk_sge_pregunta_dependencia_definicion PRIMARY KEY (dependencia_definicion);
-- ++++++++++++++++++++++++++ Fin tabla sge_pregunta_dependencia_definicion +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_pregunta_dependencia_definicion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_dependencia_definicion
-- Permisos para la tabla: sge_pregunta_dependencia_definicion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_pregunta_dependencia_definicion OWNER TO postgres;
GRANT ALL ON TABLE sge_pregunta_dependencia_definicion TO postgres;


-- ##ARCHIVO##sge_pregunta_dependencia_definicion_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_dependencia_definicion
-- Actualizacion Nro de Secuencia: sge_pregunta_dependencia_definicion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_pregunta_dependencia_definicion_seq',(SELECT MAX(dependencia_definicion) FROM sge_pregunta_dependencia_definicion));


-- ##ARCHIVO##sge_tabla_asociada_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tabla_asociada
-- Secuencia: sge_tabla_asociada_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_tabla_asociada_seq;
CREATE SEQUENCE sge_tabla_asociada_seq START 1;


-- ##ARCHIVO##sge_tabla_asociada##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tabla_asociada
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_tabla_asociada;
CREATE  TABLE sge_tabla_asociada
(
	tabla_asociada INTEGER NOT NULL DEFAULT nextval('sge_tabla_asociada_seq'::text) ,
	unidad_gestion Varchar NOT NULL,
	tabla_asociada_nombre Varchar NOT NULL
);

-- ALTER TABLE sge_tabla_asociada DROP CONSTRAINT pk_sge_tabla_asociada;
ALTER TABLE sge_tabla_asociada ADD CONSTRAINT pk_sge_tabla_asociada PRIMARY KEY (tabla_asociada);
-- ++++++++++++++++++++++++++ Fin tabla sge_tabla_asociada +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_tabla_asociada##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tabla_asociada
-- Permisos para la tabla: sge_tabla_asociada_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_tabla_asociada OWNER TO postgres;
GRANT ALL ON TABLE sge_tabla_asociada TO postgres;


-- ##ARCHIVO##sge_tabla_asociada_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tabla_asociada
-- Actualizacion Nro de Secuencia: sge_tabla_asociada_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_tabla_asociada_seq',(SELECT MAX(tabla_asociada) FROM sge_tabla_asociada));


-- ##ARCHIVO##mgn_mail_formulario_habilitado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_mail_formulario_habilitado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgn_mail_formulario_habilitado;
CREATE  TABLE mgn_mail_formulario_habilitado
(
	mail Integer NOT NULL,
	formulario_habilitado Integer NOT NULL,
	encuestado Integer NOT NULL
);

-- ALTER TABLE mgn_mail_formulario_habilitado DROP CONSTRAINT pk_mgn_mail_formulario_habilitado;
ALTER TABLE mgn_mail_formulario_habilitado ADD CONSTRAINT pk_mgn_mail_formulario_habilitado PRIMARY KEY (mail,formulario_habilitado,encuestado);
-- ++++++++++++++++++++++++++ Fin tabla mgn_mail_formulario_habilitado +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_mgn_mail_formulario_habilitado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_mail_formulario_habilitado
-- Permisos para la tabla: mgn_mail_formulario_habilitado_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mgn_mail_formulario_habilitado OWNER TO postgres;
GRANT ALL ON TABLE mgn_mail_formulario_habilitado TO postgres;


-- ##ARCHIVO##sge_puntaje_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje
-- Secuencia: sge_puntaje_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_puntaje_seq;
CREATE SEQUENCE sge_puntaje_seq START 1;


-- ##ARCHIVO##sge_puntaje##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_puntaje;
CREATE  TABLE sge_puntaje
(
	puntaje INTEGER NOT NULL DEFAULT nextval('sge_puntaje_seq'::text) ,
	nombre Varchar,
	implementado Char(1),
	fecha_hora_creacion Timestamp with time zone,
	encuesta Integer NOT NULL
);

-- ALTER TABLE sge_puntaje DROP CONSTRAINT pk_sge_puntaje;
ALTER TABLE sge_puntaje ADD CONSTRAINT pk_sge_puntaje PRIMARY KEY (puntaje);
-- ++++++++++++++++++++++++++ Fin tabla sge_puntaje +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_puntaje##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje
-- Permisos para la tabla: sge_puntaje_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_puntaje OWNER TO postgres;
GRANT ALL ON TABLE sge_puntaje TO postgres;


-- ##ARCHIVO##sge_puntaje_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje
-- Actualizacion Nro de Secuencia: sge_puntaje_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_puntaje_seq',(SELECT MAX(puntaje) FROM sge_puntaje));


-- ##ARCHIVO##sge_puntaje_pregunta_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_pregunta
-- Secuencia: sge_puntaje_pregunta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_puntaje_pregunta_seq;
CREATE SEQUENCE sge_puntaje_pregunta_seq START 1;


-- ##ARCHIVO##sge_puntaje_pregunta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_pregunta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_puntaje_pregunta;
CREATE  TABLE sge_puntaje_pregunta
(
	puntaje_pregunta INTEGER NOT NULL DEFAULT nextval('sge_puntaje_pregunta_seq'::text) ,
	puntaje Integer NOT NULL,
	encuesta_definicion Integer NOT NULL,
	pregunta Integer NOT NULL,
	puntos Integer
);

-- ALTER TABLE sge_puntaje_pregunta DROP CONSTRAINT pk_sge_puntaje_pregunta;
ALTER TABLE sge_puntaje_pregunta ADD CONSTRAINT pk_sge_puntaje_pregunta PRIMARY KEY (puntaje_pregunta);
-- ++++++++++++++++++++++++++ Fin tabla sge_puntaje_pregunta +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_puntaje_pregunta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_pregunta
-- Permisos para la tabla: sge_puntaje_pregunta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_puntaje_pregunta OWNER TO postgres;
GRANT ALL ON TABLE sge_puntaje_pregunta TO postgres;


-- ##ARCHIVO##sge_puntaje_pregunta_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_pregunta
-- Actualizacion Nro de Secuencia: sge_puntaje_pregunta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_puntaje_pregunta_seq',(SELECT MAX(puntaje_pregunta) FROM sge_puntaje_pregunta));


-- ##ARCHIVO##sge_puntaje_respuesta_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_respuesta
-- Secuencia: sge_puntaje_respuesta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_puntaje_respuesta_seq;
CREATE SEQUENCE sge_puntaje_respuesta_seq START 1;


-- ##ARCHIVO##sge_puntaje_respuesta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_respuesta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_puntaje_respuesta;
CREATE  TABLE sge_puntaje_respuesta
(
	puntaje_respuesta INTEGER NOT NULL DEFAULT nextval('sge_puntaje_respuesta_seq'::text) ,
	puntos Integer,
	puntaje_pregunta Integer NOT NULL,
	pregunta Integer NOT NULL,
	respuesta Integer NOT NULL
);

-- ALTER TABLE sge_puntaje_respuesta DROP CONSTRAINT pk_sge_puntaje_respuesta;
ALTER TABLE sge_puntaje_respuesta ADD CONSTRAINT pk_sge_puntaje_respuesta PRIMARY KEY (puntaje_respuesta);
-- ++++++++++++++++++++++++++ Fin tabla sge_puntaje_respuesta +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_puntaje_respuesta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_respuesta
-- Permisos para la tabla: sge_puntaje_respuesta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_puntaje_respuesta OWNER TO postgres;
GRANT ALL ON TABLE sge_puntaje_respuesta TO postgres;


-- ##ARCHIVO##sge_puntaje_respuesta_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_respuesta
-- Actualizacion Nro de Secuencia: sge_puntaje_respuesta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_puntaje_respuesta_seq',(SELECT MAX(puntaje_respuesta) FROM sge_puntaje_respuesta));


-- ##ARCHIVO##sge_puntaje_aplicacion_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_aplicacion
-- Secuencia: sge_puntaje_aplicacion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_puntaje_aplicacion_seq;
CREATE SEQUENCE sge_puntaje_aplicacion_seq START 1;


-- ##ARCHIVO##sge_puntaje_aplicacion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_aplicacion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_puntaje_aplicacion;
CREATE  TABLE sge_puntaje_aplicacion
(
	puntaje_aplicacion INTEGER NOT NULL DEFAULT nextval('sge_puntaje_aplicacion_seq'::text) ,
	puntaje Integer NOT NULL,
	formulario_habilitado Integer,
	formulario_habilitado_detalle Integer,
	evaluacion Integer NOT NULL
);

-- ALTER TABLE sge_puntaje_aplicacion DROP CONSTRAINT pk_sge_puntaje_aplicacion;
ALTER TABLE sge_puntaje_aplicacion ADD CONSTRAINT pk_sge_puntaje_aplicacion PRIMARY KEY (puntaje_aplicacion);
-- ++++++++++++++++++++++++++ Fin tabla sge_puntaje_aplicacion +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_puntaje_aplicacion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_aplicacion
-- Permisos para la tabla: sge_puntaje_aplicacion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_puntaje_aplicacion OWNER TO postgres;
GRANT ALL ON TABLE sge_puntaje_aplicacion TO postgres;


-- ##ARCHIVO##sge_puntaje_aplicacion_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_aplicacion
-- Actualizacion Nro de Secuencia: sge_puntaje_aplicacion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_puntaje_aplicacion_seq',(SELECT MAX(puntaje_aplicacion) FROM sge_puntaje_aplicacion));


-- ##ARCHIVO##sge_evaluacion_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_evaluacion
-- Secuencia: sge_evaluacion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_evaluacion_seq;
CREATE SEQUENCE sge_evaluacion_seq START 1;


-- ##ARCHIVO##sge_evaluacion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_evaluacion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_evaluacion;
CREATE  TABLE sge_evaluacion
(
	evaluacion INTEGER NOT NULL DEFAULT nextval('sge_evaluacion_seq'::text) ,
	nombre Varchar NOT NULL,
	cerrada Char(1) NOT NULL DEFAULT 'N',
	habilitacion Integer NOT NULL
);

-- ALTER TABLE sge_evaluacion DROP CONSTRAINT pk_sge_evaluacion;
ALTER TABLE sge_evaluacion ADD CONSTRAINT pk_sge_evaluacion PRIMARY KEY (evaluacion);
-- ++++++++++++++++++++++++++ Fin tabla sge_evaluacion +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_evaluacion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_evaluacion
-- Permisos para la tabla: sge_evaluacion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_evaluacion OWNER TO postgres;
GRANT ALL ON TABLE sge_evaluacion TO postgres;


-- ##ARCHIVO##ck_sge_evaluacion_cerrada##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_evaluacion
-- Check: ck_sge_evaluacion_cerrada
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- ALTER TABLE sge_evaluacion DROP CONSTRAINT ck_sge_evaluacion_cerrada;
ALTER TABLE sge_evaluacion ADD CONSTRAINT ck_sge_evaluacion_cerrada CHECK (cerrada IN ('N','S'));

-- ##ARCHIVO##sge_evaluacion_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_evaluacion
-- Actualizacion Nro de Secuencia: sge_evaluacion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_evaluacion_seq',(SELECT MAX(evaluacion) FROM sge_evaluacion));


-- ##ARCHIVO##sge_pregunta_fecha_calculo_tiempo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_fecha_calculo_tiempo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_pregunta_fecha_calculo_tiempo;
CREATE  TABLE sge_pregunta_fecha_calculo_tiempo
(
	pregunta_fecha Integer NOT NULL,
	pregunta_dependiente Integer NOT NULL
);

-- ALTER TABLE sge_pregunta_fecha_calculo_tiempo DROP CONSTRAINT pk_sge_pregunta_fecha_calculo_tiempo;
ALTER TABLE sge_pregunta_fecha_calculo_tiempo ADD CONSTRAINT pk_sge_pregunta_fecha_calculo_tiempo PRIMARY KEY (pregunta_fecha);
-- ++++++++++++++++++++++++++ Fin tabla sge_pregunta_fecha_calculo_tiempo +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_pregunta_fecha_calculo_tiempo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_fecha_calculo_tiempo
-- Permisos para la tabla: sge_pregunta_fecha_calculo_tiempo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_pregunta_fecha_calculo_tiempo OWNER TO postgres;
GRANT ALL ON TABLE sge_pregunta_fecha_calculo_tiempo TO postgres;


-- ##ARCHIVO##mug_cod_postales_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_cod_postales
-- Secuencia: mug_cod_postales_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE mug_cod_postales_seq;
CREATE SEQUENCE mug_cod_postales_seq START 1;


-- ##ARCHIVO##mug_cod_postales##
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

-- ##ARCHIVO##grant_mug_cod_postales##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_cod_postales
-- Permisos para la tabla: mug_cod_postales_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mug_cod_postales OWNER TO postgres;
GRANT ALL ON TABLE mug_cod_postales TO postgres;


-- ##ARCHIVO##mug_cod_postales_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_cod_postales
-- Actualizacion Nro de Secuencia: mug_cod_postales_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('mug_cod_postales_seq',(SELECT MAX(id) FROM mug_cod_postales));


-- ##ARCHIVO##sge_pregunta_cascada##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_cascada
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_pregunta_cascada;
CREATE  TABLE sge_pregunta_cascada
(
	pregunta_disparadora Integer NOT NULL,
	pregunta_receptora Integer NOT NULL
);

-- ALTER TABLE sge_pregunta_cascada DROP CONSTRAINT pk_sge_pregunta_cascada;
ALTER TABLE sge_pregunta_cascada ADD CONSTRAINT pk_sge_pregunta_cascada PRIMARY KEY (pregunta_disparadora);
-- ++++++++++++++++++++++++++ Fin tabla sge_pregunta_cascada +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_pregunta_cascada##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_cascada
-- Permisos para la tabla: sge_pregunta_cascada_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_pregunta_cascada OWNER TO postgres;
GRANT ALL ON TABLE sge_pregunta_cascada TO postgres;


-- ##ARCHIVO##mdi_pueblo_originario_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mdi_pueblo_originario
-- Secuencia: mdi_pueblo_originario_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE mdi_pueblo_originario_seq;
CREATE SEQUENCE mdi_pueblo_originario_seq START 1;


-- ##ARCHIVO##mdi_pueblo_originario##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mdi_pueblo_originario
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mdi_pueblo_originario;
CREATE  TABLE mdi_pueblo_originario
(
	pueblo_originario INTEGER NOT NULL DEFAULT nextval('mdi_pueblo_originario_seq'::text) ,
	nombre Varchar
);

-- ALTER TABLE mdi_pueblo_originario DROP CONSTRAINT pk_mdi_pueblo_originario;
ALTER TABLE mdi_pueblo_originario ADD CONSTRAINT pk_mdi_pueblo_originario PRIMARY KEY (pueblo_originario);
-- ++++++++++++++++++++++++++ Fin tabla mdi_pueblo_originario +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_mdi_pueblo_originario##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mdi_pueblo_originario
-- Permisos para la tabla: mdi_pueblo_originario_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mdi_pueblo_originario OWNER TO postgres;
GRANT ALL ON TABLE mdi_pueblo_originario TO postgres;


-- ##ARCHIVO##mdi_pueblo_originario_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mdi_pueblo_originario
-- Actualizacion Nro de Secuencia: mdi_pueblo_originario_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('mdi_pueblo_originario_seq',(SELECT MAX(pueblo_originario) FROM mdi_pueblo_originario));


-- ##ARCHIVO##mdi_carrera_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mdi_carrera
-- Secuencia: mdi_carrera_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE mdi_carrera_seq;
CREATE SEQUENCE mdi_carrera_seq START 1;


-- ##ARCHIVO##mdi_carrera##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mdi_carrera
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mdi_carrera;
CREATE  TABLE mdi_carrera
(
	codigo INTEGER NOT NULL DEFAULT nextval('mdi_carrera_seq'::text) ,
	descripcion Varchar
);

-- ALTER TABLE mdi_carrera DROP CONSTRAINT pk_mdi_carrera;
ALTER TABLE mdi_carrera ADD CONSTRAINT pk_mdi_carrera PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla mdi_carrera +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_mdi_carrera##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mdi_carrera
-- Permisos para la tabla: mdi_carrera_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mdi_carrera OWNER TO postgres;
GRANT ALL ON TABLE mdi_carrera TO postgres;


-- ##ARCHIVO##mdi_carrera_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mdi_carrera
-- Actualizacion Nro de Secuencia: mdi_carrera_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('mdi_carrera_seq',(SELECT MAX(codigo) FROM mdi_carrera));


-- ##ARCHIVO##mdi_primario##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mdi_primario
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mdi_primario;
CREATE  TABLE mdi_primario
(
	nombre Varchar(255) NOT NULL,
	cue Integer NOT NULL,
	localidad Integer,
	codigo_postal Varchar(15),
	colegio_original Integer
);

-- ALTER TABLE mdi_primario DROP CONSTRAINT pk_mdi_primario;
ALTER TABLE mdi_primario ADD CONSTRAINT pk_mdi_primario PRIMARY KEY (cue);
-- ++++++++++++++++++++++++++ Fin tabla mdi_primario +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_mdi_primario##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mdi_primario
-- Permisos para la tabla: mdi_primario_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mdi_primario OWNER TO postgres;
GRANT ALL ON TABLE mdi_primario TO postgres;


-- ##ARCHIVO##mdi_secundario##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mdi_secundario
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mdi_secundario;
CREATE  TABLE mdi_secundario
(
	nombre Varchar(255) NOT NULL,
	cue Integer NOT NULL,
	localidad Integer,
	codigo_postal Varchar(15),
	colegio_original Integer
);

-- ALTER TABLE mdi_secundario DROP CONSTRAINT pk_mdi_secundario;
ALTER TABLE mdi_secundario ADD CONSTRAINT pk_mdi_secundario PRIMARY KEY (cue);
-- ++++++++++++++++++++++++++ Fin tabla mdi_secundario +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_mdi_secundario##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mdi_secundario
-- Permisos para la tabla: mdi_secundario_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mdi_secundario OWNER TO postgres;
GRANT ALL ON TABLE mdi_secundario TO postgres;


-- ##ARCHIVO##mdi_secundario_titulo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mdi_secundario_titulo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mdi_secundario_titulo;
CREATE  TABLE mdi_secundario_titulo
(
	titulo Integer NOT NULL,
	nombre Varchar(255) NOT NULL
);

-- ALTER TABLE mdi_secundario_titulo DROP CONSTRAINT pk_mdi_secundario_titulo;
ALTER TABLE mdi_secundario_titulo ADD CONSTRAINT pk_mdi_secundario_titulo PRIMARY KEY (titulo);
-- ++++++++++++++++++++++++++ Fin tabla mdi_secundario_titulo +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_mdi_secundario_titulo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mdi_secundario_titulo
-- Permisos para la tabla: mdi_secundario_titulo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mdi_secundario_titulo OWNER TO postgres;
GRANT ALL ON TABLE mdi_secundario_titulo TO postgres;


-- ##ARCHIVO##sge_tabla_externa_seq##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tabla_externa
-- Secuencia: sge_tabla_externa_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP SEQUENCE sge_tabla_externa_seq;
CREATE SEQUENCE sge_tabla_externa_seq START 1;


-- ##ARCHIVO##sge_tabla_externa##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tabla_externa
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_tabla_externa;
CREATE  TABLE sge_tabla_externa
(
	tabla_externa INTEGER NOT NULL DEFAULT nextval('sge_tabla_externa_seq'::text) ,
	unidad_gestion Varchar NOT NULL,
	tabla_externa_nombre Varchar NOT NULL
);

-- ALTER TABLE sge_tabla_externa DROP CONSTRAINT pk_sge_tabla_externa;
ALTER TABLE sge_tabla_externa ADD CONSTRAINT pk_sge_tabla_externa PRIMARY KEY (tabla_externa);
-- ++++++++++++++++++++++++++ Fin tabla sge_tabla_externa +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_sge_tabla_externa##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tabla_externa
-- Permisos para la tabla: sge_tabla_externa_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE sge_tabla_externa OWNER TO postgres;
GRANT ALL ON TABLE sge_tabla_externa TO postgres;


-- ##ARCHIVO##sge_tabla_externa_setval##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tabla_externa
-- Actualizacion Nro de Secuencia: sge_tabla_externa_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_tabla_externa_seq',(SELECT MAX(tabla_externa) FROM sge_tabla_externa));


-- ##ARCHIVO##mdp_identidad_genero##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mdp_identidad_genero
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mdp_identidad_genero;
CREATE  TABLE mdp_identidad_genero
(
	identidad_genero Integer NOT NULL,
	nombre Varchar(255) NOT NULL
);

-- ALTER TABLE mdp_identidad_genero DROP CONSTRAINT pk_mdp_identidad_genero;
ALTER TABLE mdp_identidad_genero ADD CONSTRAINT pk_mdp_identidad_genero PRIMARY KEY (identidad_genero);
-- ++++++++++++++++++++++++++ Fin tabla mdp_identidad_genero +++++++++++++++++++++++++++++

-- ##ARCHIVO##grant_mdp_identidad_genero##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mdp_identidad_genero
-- Permisos para la tabla: mdp_identidad_genero_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ALTER TABLE mdp_identidad_genero OWNER TO postgres;
GRANT ALL ON TABLE mdp_identidad_genero TO postgres;


-- ##ARCHIVO##ix_respondido_detalle_moderada##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3 
-- Tabla: sge_respondido_detalle
-- Indice: ix_respondido_detalle_moderada
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ix_respondido_detalle_moderada;
CREATE INDEX ix_respondido_detalle_moderada ON sge_respondido_detalle (moderada);
-- ##ARCHIVO##ix_elemento_concepto_tipo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3 
-- Tabla: sge_elemento_concepto_tipo
-- Indice: ix_elemento_concepto_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ix_elemento_concepto_tipo;
CREATE UNIQUE INDEX ix_elemento_concepto_tipo ON sge_elemento_concepto_tipo (elemento,concepto,tipo_elemento);
-- ##ARCHIVO##ix_evaluacion_formhab_formhabdet_puntaje##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3 
-- Tabla: sge_puntaje_aplicacion
-- Indice: ix_evaluacion_formhab_formhabdet_puntaje
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ix_evaluacion_formhab_formhabdet_puntaje;
CREATE UNIQUE INDEX ix_evaluacion_formhab_formhabdet_puntaje ON sge_puntaje_aplicacion (evaluacion,formulario_habilitado,formulario_habilitado_detalle,puntaje);


-- ##ARCHIVO##fk_sge_encuesta_definicion_sge_bloque##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_definicion
-- FK: fk_sge_encuesta_definicion_sge_bloque
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_encuesta_definicion_sge_bloque;
CREATE INDEX ifk_sge_encuesta_definicion_sge_bloque ON  sge_encuesta_definicion (bloque);

-- ALTER TABLE sge_encuesta_definicion DROP CONSTRAINT fk_sge_encuesta_definicion_sge_bloque; 
ALTER TABLE sge_encuesta_definicion 
	ADD CONSTRAINT fk_sge_encuesta_definicion_sge_bloque FOREIGN KEY (bloque) 
	REFERENCES sge_bloque (bloque) deferrable;


-- ##ARCHIVO##fk_sge_pregunta_dependencia_definicion_sge_bloque##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_dependencia_definicion
-- FK: fk_sge_pregunta_dependencia_definicion_sge_bloque
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_dependencia_definicion_sge_bloque;
CREATE INDEX ifk_sge_pregunta_dependencia_definicion_sge_bloque ON  sge_pregunta_dependencia_definicion (bloque);

-- ALTER TABLE sge_pregunta_dependencia_definicion DROP CONSTRAINT fk_sge_pregunta_dependencia_definicion_sge_bloque; 
ALTER TABLE sge_pregunta_dependencia_definicion 
	ADD CONSTRAINT fk_sge_pregunta_dependencia_definicion_sge_bloque FOREIGN KEY (bloque) 
	REFERENCES sge_bloque (bloque) deferrable;


-- ##ARCHIVO##fk_sge_respuesta_moderadas_sge_respondido_detalle##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respuesta_moderadas
-- FK: fk_sge_respuesta_moderadas_sge_respondido_detalle
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respuesta_moderadas_sge_respondido_detalle;
CREATE INDEX ifk_sge_respuesta_moderadas_sge_respondido_detalle ON  sge_respuesta_moderadas (respondido_detalle);

-- ALTER TABLE sge_respuesta_moderadas DROP CONSTRAINT fk_sge_respuesta_moderadas_sge_respondido_detalle; 
ALTER TABLE sge_respuesta_moderadas 
	ADD CONSTRAINT fk_sge_respuesta_moderadas_sge_respondido_detalle FOREIGN KEY (respondido_detalle) 
	REFERENCES sge_respondido_detalle (respondido_detalle) deferrable;


-- ##ARCHIVO##fk_sge_formulario_definicion_sge_encuesta_atributo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_definicion
-- FK: fk_sge_formulario_definicion_sge_encuesta_atributo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_formulario_definicion_sge_encuesta_atributo;
CREATE INDEX ifk_sge_formulario_definicion_sge_encuesta_atributo ON  sge_formulario_definicion (encuesta);

-- ALTER TABLE sge_formulario_definicion DROP CONSTRAINT fk_sge_formulario_definicion_sge_encuesta_atributo; 
ALTER TABLE sge_formulario_definicion 
	ADD CONSTRAINT fk_sge_formulario_definicion_sge_encuesta_atributo FOREIGN KEY (encuesta) 
	REFERENCES sge_encuesta_atributo (encuesta) deferrable;


-- ##ARCHIVO##fk_sge_encuesta_definicion_sge_encuesta_atributo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_definicion
-- FK: fk_sge_encuesta_definicion_sge_encuesta_atributo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_encuesta_definicion_sge_encuesta_atributo;
CREATE INDEX ifk_sge_encuesta_definicion_sge_encuesta_atributo ON  sge_encuesta_definicion (encuesta);

-- ALTER TABLE sge_encuesta_definicion DROP CONSTRAINT fk_sge_encuesta_definicion_sge_encuesta_atributo; 
ALTER TABLE sge_encuesta_definicion 
	ADD CONSTRAINT fk_sge_encuesta_definicion_sge_encuesta_atributo FOREIGN KEY (encuesta) 
	REFERENCES sge_encuesta_atributo (encuesta) deferrable;


-- ##ARCHIVO##fk_sge_formulario_habilitado_detalle_sge_encuesta_atributo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado_detalle
-- FK: fk_sge_formulario_habilitado_detalle_sge_encuesta_atributo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_formulario_habilitado_detalle_sge_encuesta_atributo;
CREATE INDEX ifk_sge_formulario_habilitado_detalle_sge_encuesta_atributo ON  sge_formulario_habilitado_detalle (encuesta);

-- ALTER TABLE sge_formulario_habilitado_detalle DROP CONSTRAINT fk_sge_formulario_habilitado_detalle_sge_encuesta_atributo; 
ALTER TABLE sge_formulario_habilitado_detalle 
	ADD CONSTRAINT fk_sge_formulario_habilitado_detalle_sge_encuesta_atributo FOREIGN KEY (encuesta) 
	REFERENCES sge_encuesta_atributo (encuesta) deferrable;


-- ##ARCHIVO##fk_sge_encuesta_indicador_sge_encuesta_atributo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_indicador
-- FK: fk_sge_encuesta_indicador_sge_encuesta_atributo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_encuesta_indicador_sge_encuesta_atributo;
CREATE INDEX ifk_sge_encuesta_indicador_sge_encuesta_atributo ON  sge_encuesta_indicador (encuesta);

-- ALTER TABLE sge_encuesta_indicador DROP CONSTRAINT fk_sge_encuesta_indicador_sge_encuesta_atributo; 
ALTER TABLE sge_encuesta_indicador 
	ADD CONSTRAINT fk_sge_encuesta_indicador_sge_encuesta_atributo FOREIGN KEY (encuesta) 
	REFERENCES sge_encuesta_atributo (encuesta) deferrable;


-- ##ARCHIVO##fk_sge_log_formulario_definicion_habilitacion_sge_encuesta_atributo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_log_formulario_definicion_habilitacion
-- FK: fk_sge_log_formulario_definicion_habilitacion_sge_encuesta_atributo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_log_formulario_definicion_habilitacion_sge_encuesta_atributo;
CREATE INDEX ifk_sge_log_formulario_definicion_habilitacion_sge_encuesta_atributo ON  sge_log_formulario_definicion_habilitacion (encuesta);

-- ALTER TABLE sge_log_formulario_definicion_habilitacion DROP CONSTRAINT fk_sge_log_formulario_definicion_habilitacion_sge_encuesta_atributo; 
ALTER TABLE sge_log_formulario_definicion_habilitacion 
	ADD CONSTRAINT fk_sge_log_formulario_definicion_habilitacion_sge_encuesta_atributo FOREIGN KEY (encuesta) 
	REFERENCES sge_encuesta_atributo (encuesta) deferrable;


-- ##ARCHIVO##fk_sge_puntaje_sge_encuesta_atributo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje
-- FK: fk_sge_puntaje_sge_encuesta_atributo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_puntaje_sge_encuesta_atributo;
CREATE INDEX ifk_sge_puntaje_sge_encuesta_atributo ON  sge_puntaje (encuesta);

-- ALTER TABLE sge_puntaje DROP CONSTRAINT fk_sge_puntaje_sge_encuesta_atributo; 
ALTER TABLE sge_puntaje 
	ADD CONSTRAINT fk_sge_puntaje_sge_encuesta_atributo FOREIGN KEY (encuesta) 
	REFERENCES sge_encuesta_atributo (encuesta) deferrable;


-- ##ARCHIVO##fk_sge_formulario_habilitado_sge_habilitacion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado
-- FK: fk_sge_formulario_habilitado_sge_habilitacion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_formulario_habilitado_sge_habilitacion;
CREATE INDEX ifk_sge_formulario_habilitado_sge_habilitacion ON  sge_formulario_habilitado (habilitacion);

-- ALTER TABLE sge_formulario_habilitado DROP CONSTRAINT fk_sge_formulario_habilitado_sge_habilitacion; 
ALTER TABLE sge_formulario_habilitado 
	ADD CONSTRAINT fk_sge_formulario_habilitado_sge_habilitacion FOREIGN KEY (habilitacion) 
	REFERENCES sge_habilitacion (habilitacion) deferrable;


-- ##ARCHIVO##fk_sge_log_formulario_definicion_habilitacion_sge_habilitacion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_log_formulario_definicion_habilitacion
-- FK: fk_sge_log_formulario_definicion_habilitacion_sge_habilitacion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_log_formulario_definicion_habilitacion_sge_habilitacion;
CREATE INDEX ifk_sge_log_formulario_definicion_habilitacion_sge_habilitacion ON  sge_log_formulario_definicion_habilitacion (habilitacion);

-- ALTER TABLE sge_log_formulario_definicion_habilitacion DROP CONSTRAINT fk_sge_log_formulario_definicion_habilitacion_sge_habilitacion; 
ALTER TABLE sge_log_formulario_definicion_habilitacion 
	ADD CONSTRAINT fk_sge_log_formulario_definicion_habilitacion_sge_habilitacion FOREIGN KEY (habilitacion) 
	REFERENCES sge_habilitacion (habilitacion) deferrable;


-- ##ARCHIVO##fk_sge_evaluacion_sge_habilitacion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_evaluacion
-- FK: fk_sge_evaluacion_sge_habilitacion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_evaluacion_sge_habilitacion;
CREATE INDEX ifk_sge_evaluacion_sge_habilitacion ON  sge_evaluacion (habilitacion);

-- ALTER TABLE sge_evaluacion DROP CONSTRAINT fk_sge_evaluacion_sge_habilitacion; 
ALTER TABLE sge_evaluacion 
	ADD CONSTRAINT fk_sge_evaluacion_sge_habilitacion FOREIGN KEY (habilitacion) 
	REFERENCES sge_habilitacion (habilitacion) deferrable;


-- ##ARCHIVO##fk_sge_pregunta_respuesta_sge_pregunta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_respuesta
-- FK: fk_sge_pregunta_respuesta_sge_pregunta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_respuesta_sge_pregunta;
CREATE INDEX ifk_sge_pregunta_respuesta_sge_pregunta ON  sge_pregunta_respuesta (pregunta);

-- ALTER TABLE sge_pregunta_respuesta DROP CONSTRAINT fk_sge_pregunta_respuesta_sge_pregunta; 
ALTER TABLE sge_pregunta_respuesta 
	ADD CONSTRAINT fk_sge_pregunta_respuesta_sge_pregunta FOREIGN KEY (pregunta) 
	REFERENCES sge_pregunta (pregunta) deferrable;


-- ##ARCHIVO##fk_sge_encuesta_definicion_sge_pregunta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_definicion
-- FK: fk_sge_encuesta_definicion_sge_pregunta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_encuesta_definicion_sge_pregunta;
CREATE INDEX ifk_sge_encuesta_definicion_sge_pregunta ON  sge_encuesta_definicion (pregunta);

-- ALTER TABLE sge_encuesta_definicion DROP CONSTRAINT fk_sge_encuesta_definicion_sge_pregunta; 
ALTER TABLE sge_encuesta_definicion 
	ADD CONSTRAINT fk_sge_encuesta_definicion_sge_pregunta FOREIGN KEY (pregunta) 
	REFERENCES sge_pregunta (pregunta) deferrable;


-- ##ARCHIVO##fk_sge_pregunta_dependencia_definicion_sge_pregunta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_dependencia_definicion
-- FK: fk_sge_pregunta_dependencia_definicion_sge_pregunta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_dependencia_definicion_sge_pregunta;
CREATE INDEX ifk_sge_pregunta_dependencia_definicion_sge_pregunta ON  sge_pregunta_dependencia_definicion (pregunta);

-- ALTER TABLE sge_pregunta_dependencia_definicion DROP CONSTRAINT fk_sge_pregunta_dependencia_definicion_sge_pregunta; 
ALTER TABLE sge_pregunta_dependencia_definicion 
	ADD CONSTRAINT fk_sge_pregunta_dependencia_definicion_sge_pregunta FOREIGN KEY (pregunta) 
	REFERENCES sge_pregunta (pregunta) deferrable;


-- ##ARCHIVO##fk_sge_puntaje_pregunta_sge_pregunta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_pregunta
-- FK: fk_sge_puntaje_pregunta_sge_pregunta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_puntaje_pregunta_sge_pregunta;
CREATE INDEX ifk_sge_puntaje_pregunta_sge_pregunta ON  sge_puntaje_pregunta (pregunta);

-- ALTER TABLE sge_puntaje_pregunta DROP CONSTRAINT fk_sge_puntaje_pregunta_sge_pregunta; 
ALTER TABLE sge_puntaje_pregunta 
	ADD CONSTRAINT fk_sge_puntaje_pregunta_sge_pregunta FOREIGN KEY (pregunta) 
	REFERENCES sge_pregunta (pregunta) deferrable;


-- ##ARCHIVO##fk_sge_puntaje_respuesta_sge_pregunta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_respuesta
-- FK: fk_sge_puntaje_respuesta_sge_pregunta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_puntaje_respuesta_sge_pregunta;
CREATE INDEX ifk_sge_puntaje_respuesta_sge_pregunta ON  sge_puntaje_respuesta (pregunta);

-- ALTER TABLE sge_puntaje_respuesta DROP CONSTRAINT fk_sge_puntaje_respuesta_sge_pregunta; 
ALTER TABLE sge_puntaje_respuesta 
	ADD CONSTRAINT fk_sge_puntaje_respuesta_sge_pregunta FOREIGN KEY (pregunta) 
	REFERENCES sge_pregunta (pregunta) deferrable;


-- ##ARCHIVO##fk_sge_pregunta_fecha_calculo_tiempo_sge_pregunta_fecha##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_fecha_calculo_tiempo
-- FK: fk_sge_pregunta_fecha_calculo_tiempo_sge_pregunta_fecha
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_fecha_calculo_tiempo_sge_pregunta_fecha;
CREATE INDEX ifk_sge_pregunta_fecha_calculo_tiempo_sge_pregunta_fecha ON  sge_pregunta_fecha_calculo_tiempo (pregunta_fecha);

-- ALTER TABLE sge_pregunta_fecha_calculo_tiempo DROP CONSTRAINT fk_sge_pregunta_fecha_calculo_tiempo_sge_pregunta_fecha; 
ALTER TABLE sge_pregunta_fecha_calculo_tiempo 
	ADD CONSTRAINT fk_sge_pregunta_fecha_calculo_tiempo_sge_pregunta_fecha FOREIGN KEY (pregunta_fecha) 
	REFERENCES sge_pregunta (pregunta);


-- ##ARCHIVO##fk_sge_pregunta_fecha_calculo_tiempo_sge_pregunta_dependiente##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_fecha_calculo_tiempo
-- FK: fk_sge_pregunta_fecha_calculo_tiempo_sge_pregunta_dependiente
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_fecha_calculo_tiempo_sge_pregunta_dependiente;
CREATE INDEX ifk_sge_pregunta_fecha_calculo_tiempo_sge_pregunta_dependiente ON  sge_pregunta_fecha_calculo_tiempo (pregunta_dependiente);

-- ALTER TABLE sge_pregunta_fecha_calculo_tiempo DROP CONSTRAINT fk_sge_pregunta_fecha_calculo_tiempo_sge_pregunta_dependiente; 
ALTER TABLE sge_pregunta_fecha_calculo_tiempo 
	ADD CONSTRAINT fk_sge_pregunta_fecha_calculo_tiempo_sge_pregunta_dependiente FOREIGN KEY (pregunta_dependiente) 
	REFERENCES sge_pregunta (pregunta);


-- ##ARCHIVO##fk_sge_pregunta_sge_pregunta_cascada_disparadora##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_cascada
-- FK: fk_sge_pregunta_sge_pregunta_cascada_disparadora
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_sge_pregunta_cascada_disparadora;
CREATE INDEX ifk_sge_pregunta_sge_pregunta_cascada_disparadora ON  sge_pregunta_cascada (pregunta_disparadora);

-- ALTER TABLE sge_pregunta_cascada DROP CONSTRAINT fk_sge_pregunta_sge_pregunta_cascada_disparadora; 
ALTER TABLE sge_pregunta_cascada 
	ADD CONSTRAINT fk_sge_pregunta_sge_pregunta_cascada_disparadora FOREIGN KEY (pregunta_disparadora) 
	REFERENCES sge_pregunta (pregunta) deferrable;


-- ##ARCHIVO##fk_sge_pregunta_sge_pregunta_cascada_receptora##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_cascada
-- FK: fk_sge_pregunta_sge_pregunta_cascada_receptora
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_sge_pregunta_cascada_receptora;
CREATE INDEX ifk_sge_pregunta_sge_pregunta_cascada_receptora ON  sge_pregunta_cascada (pregunta_receptora);

-- ALTER TABLE sge_pregunta_cascada DROP CONSTRAINT fk_sge_pregunta_sge_pregunta_cascada_receptora; 
ALTER TABLE sge_pregunta_cascada 
	ADD CONSTRAINT fk_sge_pregunta_sge_pregunta_cascada_receptora FOREIGN KEY (pregunta_receptora) 
	REFERENCES sge_pregunta (pregunta) deferrable;


-- ##ARCHIVO##fk_sge_pregunta_respuesta_sge_respuesta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_respuesta
-- FK: fk_sge_pregunta_respuesta_sge_respuesta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_respuesta_sge_respuesta;
CREATE INDEX ifk_sge_pregunta_respuesta_sge_respuesta ON  sge_pregunta_respuesta (respuesta);

-- ALTER TABLE sge_pregunta_respuesta DROP CONSTRAINT fk_sge_pregunta_respuesta_sge_respuesta; 
ALTER TABLE sge_pregunta_respuesta 
	ADD CONSTRAINT fk_sge_pregunta_respuesta_sge_respuesta FOREIGN KEY (respuesta) 
	REFERENCES sge_respuesta (respuesta) deferrable;


-- ##ARCHIVO##fk_sge_puntaje_respuesta_sge_respuesta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_respuesta
-- FK: fk_sge_puntaje_respuesta_sge_respuesta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_puntaje_respuesta_sge_respuesta;
CREATE INDEX ifk_sge_puntaje_respuesta_sge_respuesta ON  sge_puntaje_respuesta (respuesta);

-- ALTER TABLE sge_puntaje_respuesta DROP CONSTRAINT fk_sge_puntaje_respuesta_sge_respuesta; 
ALTER TABLE sge_puntaje_respuesta 
	ADD CONSTRAINT fk_sge_puntaje_respuesta_sge_respuesta FOREIGN KEY (respuesta) 
	REFERENCES sge_respuesta (respuesta) deferrable;


-- ##ARCHIVO##fk_sge_respondido_detalle_sge_encuesta_definicion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_detalle
-- FK: fk_sge_respondido_detalle_sge_encuesta_definicion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respondido_detalle_sge_encuesta_definicion;
CREATE INDEX ifk_sge_respondido_detalle_sge_encuesta_definicion ON  sge_respondido_detalle (encuesta_definicion);

-- ALTER TABLE sge_respondido_detalle DROP CONSTRAINT fk_sge_respondido_detalle_sge_encuesta_definicion; 
ALTER TABLE sge_respondido_detalle 
	ADD CONSTRAINT fk_sge_respondido_detalle_sge_encuesta_definicion FOREIGN KEY (encuesta_definicion) 
	REFERENCES sge_encuesta_definicion (encuesta_definicion) deferrable;


-- ##ARCHIVO##fk_sge_encuesta_indicador_sge_encuesta_definicion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_indicador
-- FK: fk_sge_encuesta_indicador_sge_encuesta_definicion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_encuesta_indicador_sge_encuesta_definicion;
CREATE INDEX ifk_sge_encuesta_indicador_sge_encuesta_definicion ON  sge_encuesta_indicador (encuesta_definicion);

-- ALTER TABLE sge_encuesta_indicador DROP CONSTRAINT fk_sge_encuesta_indicador_sge_encuesta_definicion; 
ALTER TABLE sge_encuesta_indicador 
	ADD CONSTRAINT fk_sge_encuesta_indicador_sge_encuesta_definicion FOREIGN KEY (encuesta_definicion) 
	REFERENCES sge_encuesta_definicion (encuesta_definicion) deferrable;


-- ##ARCHIVO##fk_sge_formulario_habilitado_indicador_sge_encuesta_definicion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado_indicador
-- FK: fk_sge_formulario_habilitado_indicador_sge_encuesta_definicion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_formulario_habilitado_indicador_sge_encuesta_definicion;
CREATE INDEX ifk_sge_formulario_habilitado_indicador_sge_encuesta_definicion ON  sge_formulario_habilitado_indicador (encuesta_definicion);

-- ALTER TABLE sge_formulario_habilitado_indicador DROP CONSTRAINT fk_sge_formulario_habilitado_indicador_sge_encuesta_definicion; 
ALTER TABLE sge_formulario_habilitado_indicador 
	ADD CONSTRAINT fk_sge_formulario_habilitado_indicador_sge_encuesta_definicion FOREIGN KEY (encuesta_definicion) 
	REFERENCES sge_encuesta_definicion (encuesta_definicion) deferrable;


-- ##ARCHIVO##fk_sge_pregunta_dependencia_sge_encuesta_definicion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_dependencia
-- FK: fk_sge_pregunta_dependencia_sge_encuesta_definicion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_dependencia_sge_encuesta_definicion;
CREATE INDEX ifk_sge_pregunta_dependencia_sge_encuesta_definicion ON  sge_pregunta_dependencia (encuesta_definicion);

-- ALTER TABLE sge_pregunta_dependencia DROP CONSTRAINT fk_sge_pregunta_dependencia_sge_encuesta_definicion; 
ALTER TABLE sge_pregunta_dependencia 
	ADD CONSTRAINT fk_sge_pregunta_dependencia_sge_encuesta_definicion FOREIGN KEY (encuesta_definicion) 
	REFERENCES sge_encuesta_definicion (encuesta_definicion) deferrable;


-- ##ARCHIVO##fk_sge_pregunta_dependencia_definicion_sge_encuesta_definicion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_dependencia_definicion
-- FK: fk_sge_pregunta_dependencia_definicion_sge_encuesta_definicion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_dependencia_definicion_sge_encuesta_definicion;
CREATE INDEX ifk_sge_pregunta_dependencia_definicion_sge_encuesta_definicion ON  sge_pregunta_dependencia_definicion (encuesta_definicion);

-- ALTER TABLE sge_pregunta_dependencia_definicion DROP CONSTRAINT fk_sge_pregunta_dependencia_definicion_sge_encuesta_definicion; 
ALTER TABLE sge_pregunta_dependencia_definicion 
	ADD CONSTRAINT fk_sge_pregunta_dependencia_definicion_sge_encuesta_definicion FOREIGN KEY (encuesta_definicion) 
	REFERENCES sge_encuesta_definicion (encuesta_definicion) deferrable;


-- ##ARCHIVO##fk_sge_puntaje_pregunta_sge_encuesta_definicion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_pregunta
-- FK: fk_sge_puntaje_pregunta_sge_encuesta_definicion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_puntaje_pregunta_sge_encuesta_definicion;
CREATE INDEX ifk_sge_puntaje_pregunta_sge_encuesta_definicion ON  sge_puntaje_pregunta (encuesta_definicion);

-- ALTER TABLE sge_puntaje_pregunta DROP CONSTRAINT fk_sge_puntaje_pregunta_sge_encuesta_definicion; 
ALTER TABLE sge_puntaje_pregunta 
	ADD CONSTRAINT fk_sge_puntaje_pregunta_sge_encuesta_definicion FOREIGN KEY (encuesta_definicion) 
	REFERENCES sge_encuesta_definicion (encuesta_definicion) deferrable;


-- ##ARCHIVO##fk_sge_pregunta_sge_componente_pregunta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta
-- FK: fk_sge_pregunta_sge_componente_pregunta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_sge_componente_pregunta;
CREATE INDEX ifk_sge_pregunta_sge_componente_pregunta ON  sge_pregunta (componente_numero);

-- ALTER TABLE sge_pregunta DROP CONSTRAINT fk_sge_pregunta_sge_componente_pregunta; 
ALTER TABLE sge_pregunta 
	ADD CONSTRAINT fk_sge_pregunta_sge_componente_pregunta FOREIGN KEY (componente_numero) 
	REFERENCES sge_componente_pregunta (numero) deferrable;


-- ##ARCHIVO##fk_mgi_resp_acad_mgi_resp_acad_tipos##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_responsable_academica
-- FK: fk_mgi_resp_acad_mgi_resp_acad_tipos
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_resp_acad_mgi_resp_acad_tipos;
CREATE INDEX ifk_mgi_resp_acad_mgi_resp_acad_tipos ON  mgi_responsable_academica (tipo_responsable_academica);

-- ALTER TABLE mgi_responsable_academica DROP CONSTRAINT fk_mgi_resp_acad_mgi_resp_acad_tipos; 
ALTER TABLE mgi_responsable_academica 
	ADD CONSTRAINT fk_mgi_resp_acad_mgi_resp_acad_tipos FOREIGN KEY (tipo_responsable_academica) 
	REFERENCES mgi_responsable_academica_tipo (tipo_responsable_academica) deferrable;


-- ##ARCHIVO##fk_mgi_titulo_ra_mgi_responsable_academica##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_titulo_ra
-- FK: fk_mgi_titulo_ra_mgi_responsable_academica
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_titulo_ra_mgi_responsable_academica;
CREATE INDEX ifk_mgi_titulo_ra_mgi_responsable_academica ON  mgi_titulo_ra (responsable_academica);

-- ALTER TABLE mgi_titulo_ra DROP CONSTRAINT fk_mgi_titulo_ra_mgi_responsable_academica; 
ALTER TABLE mgi_titulo_ra 
	ADD CONSTRAINT fk_mgi_titulo_ra_mgi_responsable_academica FOREIGN KEY (responsable_academica) 
	REFERENCES mgi_responsable_academica (responsable_academica) deferrable;


-- ##ARCHIVO##fk_mgi_propuesta_ra_mgi_responsable_academica##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_propuesta_ra
-- FK: fk_mgi_propuesta_ra_mgi_responsable_academica
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_propuesta_ra_mgi_responsable_academica;
CREATE INDEX ifk_mgi_propuesta_ra_mgi_responsable_academica ON  mgi_propuesta_ra (responsable_academica);

-- ALTER TABLE mgi_propuesta_ra DROP CONSTRAINT fk_mgi_propuesta_ra_mgi_responsable_academica; 
ALTER TABLE mgi_propuesta_ra 
	ADD CONSTRAINT fk_mgi_propuesta_ra_mgi_responsable_academica FOREIGN KEY (responsable_academica) 
	REFERENCES mgi_responsable_academica (responsable_academica) deferrable;


-- ##ARCHIVO##fk_mgi_institucion_mgi_institucion_tipo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_institucion
-- FK: fk_mgi_institucion_mgi_institucion_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_institucion_mgi_institucion_tipo;
CREATE INDEX ifk_mgi_institucion_mgi_institucion_tipo ON  mgi_institucion (tipo_institucion);

-- ALTER TABLE mgi_institucion DROP CONSTRAINT fk_mgi_institucion_mgi_institucion_tipo; 
ALTER TABLE mgi_institucion 
	ADD CONSTRAINT fk_mgi_institucion_mgi_institucion_tipo FOREIGN KEY (tipo_institucion) 
	REFERENCES mgi_institucion_tipo (tipo_institucion) deferrable;


-- ##ARCHIVO##fk_mgi_responsable_academica_mgi_institucion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_responsable_academica
-- FK: fk_mgi_responsable_academica_mgi_institucion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_responsable_academica_mgi_institucion;
CREATE INDEX ifk_mgi_responsable_academica_mgi_institucion ON  mgi_responsable_academica (institucion);

-- ALTER TABLE mgi_responsable_academica DROP CONSTRAINT fk_mgi_responsable_academica_mgi_institucion; 
ALTER TABLE mgi_responsable_academica 
	ADD CONSTRAINT fk_mgi_responsable_academica_mgi_institucion FOREIGN KEY (institucion) 
	REFERENCES mgi_institucion (institucion) deferrable;


-- ##ARCHIVO##fk_mgi_institucion_mug_localidades##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_institucion
-- FK: fk_mgi_institucion_mug_localidades
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_institucion_mug_localidades;
CREATE INDEX ifk_mgi_institucion_mug_localidades ON  mgi_institucion (localidad);

-- ALTER TABLE mgi_institucion DROP CONSTRAINT fk_mgi_institucion_mug_localidades; 
ALTER TABLE mgi_institucion 
	ADD CONSTRAINT fk_mgi_institucion_mug_localidades FOREIGN KEY (localidad) 
	REFERENCES mug_localidades (localidad) deferrable;


-- ##ARCHIVO##fk_mgi_responsable_academica_mug_localidades##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_responsable_academica
-- FK: fk_mgi_responsable_academica_mug_localidades
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_responsable_academica_mug_localidades;
CREATE INDEX ifk_mgi_responsable_academica_mug_localidades ON  mgi_responsable_academica (localidad);

-- ALTER TABLE mgi_responsable_academica DROP CONSTRAINT fk_mgi_responsable_academica_mug_localidades; 
ALTER TABLE mgi_responsable_academica 
	ADD CONSTRAINT fk_mgi_responsable_academica_mug_localidades FOREIGN KEY (localidad) 
	REFERENCES mug_localidades (localidad) deferrable;


-- ##ARCHIVO##fk_mug_cod_postales_mug_localidades##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_cod_postales
-- FK: fk_mug_cod_postales_mug_localidades
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mug_cod_postales_mug_localidades;
CREATE INDEX ifk_mug_cod_postales_mug_localidades ON  mug_cod_postales (localidad);

-- ALTER TABLE mug_cod_postales DROP CONSTRAINT fk_mug_cod_postales_mug_localidades; 
ALTER TABLE mug_cod_postales 
	ADD CONSTRAINT fk_mug_cod_postales_mug_localidades FOREIGN KEY (localidad) 
	REFERENCES mug_localidades (localidad) deferrable;


-- ##ARCHIVO##fk_mug_paises_mug_continentes##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_paises
-- FK: fk_mug_paises_mug_continentes
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mug_paises_mug_continentes;
CREATE INDEX ifk_mug_paises_mug_continentes ON  mug_paises (continente);

-- ALTER TABLE mug_paises DROP CONSTRAINT fk_mug_paises_mug_continentes; 
ALTER TABLE mug_paises 
	ADD CONSTRAINT fk_mug_paises_mug_continentes FOREIGN KEY (continente) 
	REFERENCES mug_continentes (continente) deferrable;


-- ##ARCHIVO##fk_mug_localidades_mug_dptos_partidos##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_localidades
-- FK: fk_mug_localidades_mug_dptos_partidos
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mug_localidades_mug_dptos_partidos;
CREATE INDEX ifk_mug_localidades_mug_dptos_partidos ON  mug_localidades (dpto_partido);

-- ALTER TABLE mug_localidades DROP CONSTRAINT fk_mug_localidades_mug_dptos_partidos; 
ALTER TABLE mug_localidades 
	ADD CONSTRAINT fk_mug_localidades_mug_dptos_partidos FOREIGN KEY (dpto_partido) 
	REFERENCES mug_dptos_partidos (dpto_partido) deferrable;


-- ##ARCHIVO##fk_mug_provincias_mug_paises##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_provincias
-- FK: fk_mug_provincias_mug_paises
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mug_provincias_mug_paises;
CREATE INDEX ifk_mug_provincias_mug_paises ON  mug_provincias (pais);

-- ALTER TABLE mug_provincias DROP CONSTRAINT fk_mug_provincias_mug_paises; 
ALTER TABLE mug_provincias 
	ADD CONSTRAINT fk_mug_provincias_mug_paises FOREIGN KEY (pais) 
	REFERENCES mug_paises (pais) deferrable;


-- ##ARCHIVO##fk_mug_dptos_partidos_mug_provincias##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_dptos_partidos
-- FK: fk_mug_dptos_partidos_mug_provincias
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mug_dptos_partidos_mug_provincias;
CREATE INDEX ifk_mug_dptos_partidos_mug_provincias ON  mug_dptos_partidos (provincia);

-- ALTER TABLE mug_dptos_partidos DROP CONSTRAINT fk_mug_dptos_partidos_mug_provincias; 
ALTER TABLE mug_dptos_partidos 
	ADD CONSTRAINT fk_mug_dptos_partidos_mug_provincias FOREIGN KEY (provincia) 
	REFERENCES mug_provincias (provincia) deferrable;


-- ##ARCHIVO##fk_sge_encuestado_titulo_sge_encuestado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuestado_titulo
-- FK: fk_sge_encuestado_titulo_sge_encuestado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_encuestado_titulo_sge_encuestado;
CREATE INDEX ifk_sge_encuestado_titulo_sge_encuestado ON  sge_encuestado_titulo (encuestado);

-- ALTER TABLE sge_encuestado_titulo DROP CONSTRAINT fk_sge_encuestado_titulo_sge_encuestado; 
ALTER TABLE sge_encuestado_titulo 
	ADD CONSTRAINT fk_sge_encuestado_titulo_sge_encuestado FOREIGN KEY (encuestado) 
	REFERENCES sge_encuestado (encuestado) deferrable;


-- ##ARCHIVO##fk_sge_grupo_detalle_sge_encuestado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_grupo_detalle
-- FK: fk_sge_grupo_detalle_sge_encuestado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_grupo_detalle_sge_encuestado;
CREATE INDEX ifk_sge_grupo_detalle_sge_encuestado ON  sge_grupo_detalle (encuestado);

-- ALTER TABLE sge_grupo_detalle DROP CONSTRAINT fk_sge_grupo_detalle_sge_encuestado; 
ALTER TABLE sge_grupo_detalle 
	ADD CONSTRAINT fk_sge_grupo_detalle_sge_encuestado FOREIGN KEY (encuestado) 
	REFERENCES sge_encuestado (encuestado) deferrable;


-- ##ARCHIVO##fk_mgn_log_envio_sge_encuestado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_log_envio
-- FK: fk_mgn_log_envio_sge_encuestado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgn_log_envio_sge_encuestado;
CREATE INDEX ifk_mgn_log_envio_sge_encuestado ON  mgn_log_envio (encuestado);

-- ALTER TABLE mgn_log_envio DROP CONSTRAINT fk_mgn_log_envio_sge_encuestado; 
ALTER TABLE mgn_log_envio 
	ADD CONSTRAINT fk_mgn_log_envio_sge_encuestado FOREIGN KEY (encuestado) 
	REFERENCES sge_encuestado (encuestado) deferrable;


-- ##ARCHIVO##fk_sge_respondido_encuestado_sge_encuestado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuestado
-- FK: fk_sge_respondido_encuestado_sge_encuestado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respondido_encuestado_sge_encuestado;
CREATE INDEX ifk_sge_respondido_encuestado_sge_encuestado ON  sge_respondido_encuestado (encuestado);

-- ALTER TABLE sge_respondido_encuestado DROP CONSTRAINT fk_sge_respondido_encuestado_sge_encuestado; 
ALTER TABLE sge_respondido_encuestado 
	ADD CONSTRAINT fk_sge_respondido_encuestado_sge_encuestado FOREIGN KEY (encuestado) 
	REFERENCES sge_encuestado (encuestado) deferrable;


-- ##ARCHIVO##fk_sge_respondido_por_sge_encuestado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_por
-- FK: fk_sge_respondido_por_sge_encuestado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respondido_por_sge_encuestado;
CREATE INDEX ifk_sge_respondido_por_sge_encuestado ON  sge_respondido_por (encuestado);

-- ALTER TABLE sge_respondido_por DROP CONSTRAINT fk_sge_respondido_por_sge_encuestado; 
ALTER TABLE sge_respondido_por 
	ADD CONSTRAINT fk_sge_respondido_por_sge_encuestado FOREIGN KEY (encuestado) 
	REFERENCES sge_encuestado (encuestado) deferrable;


-- ##ARCHIVO##fk_mgn_mail_formulario_habilitado_sge_encuestado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_mail_formulario_habilitado
-- FK: fk_mgn_mail_formulario_habilitado_sge_encuestado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgn_mail_formulario_habilitado_sge_encuestado;
CREATE INDEX ifk_mgn_mail_formulario_habilitado_sge_encuestado ON  mgn_mail_formulario_habilitado (encuestado);

-- ALTER TABLE mgn_mail_formulario_habilitado DROP CONSTRAINT fk_mgn_mail_formulario_habilitado_sge_encuestado; 
ALTER TABLE mgn_mail_formulario_habilitado 
	ADD CONSTRAINT fk_mgn_mail_formulario_habilitado_sge_encuestado FOREIGN KEY (encuestado) 
	REFERENCES sge_encuestado (encuestado) deferrable;


-- ##ARCHIVO##fk_mgi_titulo_ra_mgi_titulo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_titulo_ra
-- FK: fk_mgi_titulo_ra_mgi_titulo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_titulo_ra_mgi_titulo;
CREATE INDEX ifk_mgi_titulo_ra_mgi_titulo ON  mgi_titulo_ra (titulo);

-- ALTER TABLE mgi_titulo_ra DROP CONSTRAINT fk_mgi_titulo_ra_mgi_titulo; 
ALTER TABLE mgi_titulo_ra 
	ADD CONSTRAINT fk_mgi_titulo_ra_mgi_titulo FOREIGN KEY (titulo) 
	REFERENCES mgi_titulo (titulo) deferrable;


-- ##ARCHIVO##fk_sge_encuestado_titulo_mgi_titulo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuestado_titulo
-- FK: fk_sge_encuestado_titulo_mgi_titulo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_encuestado_titulo_mgi_titulo;
CREATE INDEX ifk_sge_encuestado_titulo_mgi_titulo ON  sge_encuestado_titulo (titulo);

-- ALTER TABLE sge_encuestado_titulo DROP CONSTRAINT fk_sge_encuestado_titulo_mgi_titulo; 
ALTER TABLE sge_encuestado_titulo 
	ADD CONSTRAINT fk_sge_encuestado_titulo_mgi_titulo FOREIGN KEY (titulo) 
	REFERENCES mgi_titulo (titulo) deferrable;


-- ##ARCHIVO##fk_mgi_titulo_propuesta_mgi_titulo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_titulo_propuesta
-- FK: fk_mgi_titulo_propuesta_mgi_titulo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_titulo_propuesta_mgi_titulo;
CREATE INDEX ifk_mgi_titulo_propuesta_mgi_titulo ON  mgi_titulo_propuesta (titulo);

-- ALTER TABLE mgi_titulo_propuesta DROP CONSTRAINT fk_mgi_titulo_propuesta_mgi_titulo; 
ALTER TABLE mgi_titulo_propuesta 
	ADD CONSTRAINT fk_mgi_titulo_propuesta_mgi_titulo FOREIGN KEY (titulo) 
	REFERENCES mgi_titulo (titulo) deferrable;


-- ##ARCHIVO##fk_sge_grupo_detalle_sge_grupo_definicion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_grupo_detalle
-- FK: fk_sge_grupo_detalle_sge_grupo_definicion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_grupo_detalle_sge_grupo_definicion;
CREATE INDEX ifk_sge_grupo_detalle_sge_grupo_definicion ON  sge_grupo_detalle (grupo);

-- ALTER TABLE sge_grupo_detalle DROP CONSTRAINT fk_sge_grupo_detalle_sge_grupo_definicion; 
ALTER TABLE sge_grupo_detalle 
	ADD CONSTRAINT fk_sge_grupo_detalle_sge_grupo_definicion FOREIGN KEY (grupo) 
	REFERENCES sge_grupo_definicion (grupo) deferrable;


-- ##ARCHIVO##fk_sge_grupo_habilitado_sge_grupo_definicion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_grupo_habilitado
-- FK: fk_sge_grupo_habilitado_sge_grupo_definicion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_grupo_habilitado_sge_grupo_definicion;
CREATE INDEX ifk_sge_grupo_habilitado_sge_grupo_definicion ON  sge_grupo_habilitado (grupo);

-- ALTER TABLE sge_grupo_habilitado DROP CONSTRAINT fk_sge_grupo_habilitado_sge_grupo_definicion; 
ALTER TABLE sge_grupo_habilitado 
	ADD CONSTRAINT fk_sge_grupo_habilitado_sge_grupo_definicion FOREIGN KEY (grupo) 
	REFERENCES sge_grupo_definicion (grupo) deferrable;


-- ##ARCHIVO##fk_mgi_propuesta_ra_mgi_propuesta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_propuesta_ra
-- FK: fk_mgi_propuesta_ra_mgi_propuesta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_propuesta_ra_mgi_propuesta;
CREATE INDEX ifk_mgi_propuesta_ra_mgi_propuesta ON  mgi_propuesta_ra (propuesta);

-- ALTER TABLE mgi_propuesta_ra DROP CONSTRAINT fk_mgi_propuesta_ra_mgi_propuesta; 
ALTER TABLE mgi_propuesta_ra 
	ADD CONSTRAINT fk_mgi_propuesta_ra_mgi_propuesta FOREIGN KEY (propuesta) 
	REFERENCES mgi_propuesta (propuesta) deferrable;


-- ##ARCHIVO##fk_mgi_titulo_propuesta_mgi_propuesta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_titulo_propuesta
-- FK: fk_mgi_titulo_propuesta_mgi_propuesta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_titulo_propuesta_mgi_propuesta;
CREATE INDEX ifk_mgi_titulo_propuesta_mgi_propuesta ON  mgi_titulo_propuesta (propuesta);

-- ALTER TABLE mgi_titulo_propuesta DROP CONSTRAINT fk_mgi_titulo_propuesta_mgi_propuesta; 
ALTER TABLE mgi_titulo_propuesta 
	ADD CONSTRAINT fk_mgi_titulo_propuesta_mgi_propuesta FOREIGN KEY (propuesta) 
	REFERENCES mgi_propuesta (propuesta) deferrable;


-- ##ARCHIVO##fk_sge_habilitacion_sge_encuesta_estilo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_habilitacion
-- FK: fk_sge_habilitacion_sge_encuesta_estilo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_habilitacion_sge_encuesta_estilo;
CREATE INDEX ifk_sge_habilitacion_sge_encuesta_estilo ON  sge_habilitacion (estilo);

-- ALTER TABLE sge_habilitacion DROP CONSTRAINT fk_sge_habilitacion_sge_encuesta_estilo; 
ALTER TABLE sge_habilitacion 
	ADD CONSTRAINT fk_sge_habilitacion_sge_encuesta_estilo FOREIGN KEY (estilo) 
	REFERENCES sge_encuesta_estilo (estilo) deferrable;


-- ##ARCHIVO##fk_mgn_log_envio_mgn_mail##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_log_envio
-- FK: fk_mgn_log_envio_mgn_mail
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgn_log_envio_mgn_mail;
CREATE INDEX ifk_mgn_log_envio_mgn_mail ON  mgn_log_envio (mail);

-- ALTER TABLE mgn_log_envio DROP CONSTRAINT fk_mgn_log_envio_mgn_mail; 
ALTER TABLE mgn_log_envio 
	ADD CONSTRAINT fk_mgn_log_envio_mgn_mail FOREIGN KEY (mail) 
	REFERENCES mgn_mail (mail) deferrable;


-- ##ARCHIVO##fk_mgn_mail_formulario_habilitado_mgn_mail##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_mail_formulario_habilitado
-- FK: fk_mgn_mail_formulario_habilitado_mgn_mail
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgn_mail_formulario_habilitado_mgn_mail;
CREATE INDEX ifk_mgn_mail_formulario_habilitado_mgn_mail ON  mgn_mail_formulario_habilitado (mail);

-- ALTER TABLE mgn_mail_formulario_habilitado DROP CONSTRAINT fk_mgn_mail_formulario_habilitado_mgn_mail; 
ALTER TABLE mgn_mail_formulario_habilitado 
	ADD CONSTRAINT fk_mgn_mail_formulario_habilitado_mgn_mail FOREIGN KEY (mail) 
	REFERENCES mgn_mail (mail) deferrable;


-- ##ARCHIVO##fk_sge_respondido_detalle_sge_respondido_encuesta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_detalle
-- FK: fk_sge_respondido_detalle_sge_respondido_encuesta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respondido_detalle_sge_respondido_encuesta;
CREATE INDEX ifk_sge_respondido_detalle_sge_respondido_encuesta ON  sge_respondido_detalle (respondido_encuesta);

-- ALTER TABLE sge_respondido_detalle DROP CONSTRAINT fk_sge_respondido_detalle_sge_respondido_encuesta; 
ALTER TABLE sge_respondido_detalle 
	ADD CONSTRAINT fk_sge_respondido_detalle_sge_respondido_encuesta FOREIGN KEY (respondido_encuesta) 
	REFERENCES sge_respondido_encuesta (respondido_encuesta) deferrable;


-- ##ARCHIVO##fk_mgi_titulo_arau_titulos##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_titulo
-- FK: fk_mgi_titulo_arau_titulos
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_titulo_arau_titulos;
CREATE INDEX ifk_mgi_titulo_arau_titulos ON  mgi_titulo (titulo_araucano);

-- ALTER TABLE mgi_titulo DROP CONSTRAINT fk_mgi_titulo_arau_titulos; 
ALTER TABLE mgi_titulo 
	ADD CONSTRAINT fk_mgi_titulo_arau_titulos FOREIGN KEY (titulo_araucano) 
	REFERENCES arau_titulos (titulo_araucano) deferrable;


-- ##ARCHIVO##fk_mgi_institucion_arau_instituciones##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_institucion
-- FK: fk_mgi_institucion_arau_instituciones
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_institucion_arau_instituciones;
CREATE INDEX ifk_mgi_institucion_arau_instituciones ON  mgi_institucion (institucion_araucano);

-- ALTER TABLE mgi_institucion DROP CONSTRAINT fk_mgi_institucion_arau_instituciones; 
ALTER TABLE mgi_institucion 
	ADD CONSTRAINT fk_mgi_institucion_arau_instituciones FOREIGN KEY (institucion_araucano) 
	REFERENCES arau_instituciones (institucion_araucano) deferrable;


-- ##ARCHIVO##fk_mgi_responsable_academica_arau_responsables_academicas##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_responsable_academica
-- FK: fk_mgi_responsable_academica_arau_responsables_academicas
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_responsable_academica_arau_responsables_academicas;
CREATE INDEX ifk_mgi_responsable_academica_arau_responsables_academicas ON  mgi_responsable_academica (ra_araucano);

-- ALTER TABLE mgi_responsable_academica DROP CONSTRAINT fk_mgi_responsable_academica_arau_responsables_academicas; 
ALTER TABLE mgi_responsable_academica 
	ADD CONSTRAINT fk_mgi_responsable_academica_arau_responsables_academicas FOREIGN KEY (ra_araucano) 
	REFERENCES arau_responsables_academicas (ra_araucano) deferrable;


-- ##ARCHIVO##fk_sge_reporte_exportado_sge_reporte_tipo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_reporte_exportado
-- FK: fk_sge_reporte_exportado_sge_reporte_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_reporte_exportado_sge_reporte_tipo;
CREATE INDEX ifk_sge_reporte_exportado_sge_reporte_tipo ON  sge_reporte_exportado (reporte_tipo);

-- ALTER TABLE sge_reporte_exportado DROP CONSTRAINT fk_sge_reporte_exportado_sge_reporte_tipo; 
ALTER TABLE sge_reporte_exportado 
	ADD CONSTRAINT fk_sge_reporte_exportado_sge_reporte_tipo FOREIGN KEY (reporte_tipo) 
	REFERENCES sge_reporte_tipo (reporte_tipo) deferrable;


-- ##ARCHIVO##fk_sge_encuestado_sge_documento_tipo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuestado
-- FK: fk_sge_encuestado_sge_documento_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_encuestado_sge_documento_tipo;
CREATE INDEX ifk_sge_encuestado_sge_documento_tipo ON  sge_encuestado (documento_tipo);

-- ALTER TABLE sge_encuestado DROP CONSTRAINT fk_sge_encuestado_sge_documento_tipo; 
ALTER TABLE sge_encuestado 
	ADD CONSTRAINT fk_sge_encuestado_sge_documento_tipo FOREIGN KEY (documento_tipo) 
	REFERENCES sge_documento_tipo (documento_tipo) deferrable;


-- ##ARCHIVO##fk_sge_elemento_concepto_tipo_sge_concepto##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_elemento_concepto_tipo
-- FK: fk_sge_elemento_concepto_tipo_sge_concepto
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_elemento_concepto_tipo_sge_concepto;
CREATE INDEX ifk_sge_elemento_concepto_tipo_sge_concepto ON  sge_elemento_concepto_tipo (concepto);

-- ALTER TABLE sge_elemento_concepto_tipo DROP CONSTRAINT fk_sge_elemento_concepto_tipo_sge_concepto; 
ALTER TABLE sge_elemento_concepto_tipo 
	ADD CONSTRAINT fk_sge_elemento_concepto_tipo_sge_concepto FOREIGN KEY (concepto) 
	REFERENCES sge_concepto (concepto) deferrable;


-- ##ARCHIVO##fk_sge_formulario_habilitado_sge_concepto##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado
-- FK: fk_sge_formulario_habilitado_sge_concepto
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_formulario_habilitado_sge_concepto;
CREATE INDEX ifk_sge_formulario_habilitado_sge_concepto ON  sge_formulario_habilitado (concepto);

-- ALTER TABLE sge_formulario_habilitado DROP CONSTRAINT fk_sge_formulario_habilitado_sge_concepto; 
ALTER TABLE sge_formulario_habilitado 
	ADD CONSTRAINT fk_sge_formulario_habilitado_sge_concepto FOREIGN KEY (concepto) 
	REFERENCES sge_concepto (concepto) deferrable;


-- ##ARCHIVO##fk_sge_habilitacion_sge_sistema_externo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_habilitacion
-- FK: fk_sge_habilitacion_sge_sistema_externo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_habilitacion_sge_sistema_externo;
CREATE INDEX ifk_sge_habilitacion_sge_sistema_externo ON  sge_habilitacion (sistema);

-- ALTER TABLE sge_habilitacion DROP CONSTRAINT fk_sge_habilitacion_sge_sistema_externo; 
ALTER TABLE sge_habilitacion 
	ADD CONSTRAINT fk_sge_habilitacion_sge_sistema_externo FOREIGN KEY (sistema) 
	REFERENCES sge_sistema_externo (sistema) deferrable;


-- ##ARCHIVO##fk_sge_respondido_encuestado_sge_sistema_externo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuestado
-- FK: fk_sge_respondido_encuestado_sge_sistema_externo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respondido_encuestado_sge_sistema_externo;
CREATE INDEX ifk_sge_respondido_encuestado_sge_sistema_externo ON  sge_respondido_encuestado (sistema);

-- ALTER TABLE sge_respondido_encuestado DROP CONSTRAINT fk_sge_respondido_encuestado_sge_sistema_externo; 
ALTER TABLE sge_respondido_encuestado 
	ADD CONSTRAINT fk_sge_respondido_encuestado_sge_sistema_externo FOREIGN KEY (sistema) 
	REFERENCES sge_sistema_externo (sistema) deferrable;


-- ##ARCHIVO##fk_sge_tipo_elemento_sge_sistema_externo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tipo_elemento
-- FK: fk_sge_tipo_elemento_sge_sistema_externo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_tipo_elemento_sge_sistema_externo;
CREATE INDEX ifk_sge_tipo_elemento_sge_sistema_externo ON  sge_tipo_elemento (sistema);

-- ALTER TABLE sge_tipo_elemento DROP CONSTRAINT fk_sge_tipo_elemento_sge_sistema_externo; 
ALTER TABLE sge_tipo_elemento 
	ADD CONSTRAINT fk_sge_tipo_elemento_sge_sistema_externo FOREIGN KEY (sistema) 
	REFERENCES sge_sistema_externo (sistema) deferrable;


-- ##ARCHIVO##fk_sge_concepto_sge_sistema_externo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_concepto
-- FK: fk_sge_concepto_sge_sistema_externo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_concepto_sge_sistema_externo;
CREATE INDEX ifk_sge_concepto_sge_sistema_externo ON  sge_concepto (sistema);

-- ALTER TABLE sge_concepto DROP CONSTRAINT fk_sge_concepto_sge_sistema_externo; 
ALTER TABLE sge_concepto 
	ADD CONSTRAINT fk_sge_concepto_sge_sistema_externo FOREIGN KEY (sistema) 
	REFERENCES sge_sistema_externo (sistema) deferrable;


-- ##ARCHIVO##fk_sge_elemento_sge_sistema_externo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_elemento
-- FK: fk_sge_elemento_sge_sistema_externo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_elemento_sge_sistema_externo;
CREATE INDEX ifk_sge_elemento_sge_sistema_externo ON  sge_elemento (sistema);

-- ALTER TABLE sge_elemento DROP CONSTRAINT fk_sge_elemento_sge_sistema_externo; 
ALTER TABLE sge_elemento 
	ADD CONSTRAINT fk_sge_elemento_sge_sistema_externo FOREIGN KEY (sistema) 
	REFERENCES sge_sistema_externo (sistema) deferrable;


-- ##ARCHIVO##fk_sge_elemento_concepto_tipo_sge_elemento##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_elemento_concepto_tipo
-- FK: fk_sge_elemento_concepto_tipo_sge_elemento
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_elemento_concepto_tipo_sge_elemento;
CREATE INDEX ifk_sge_elemento_concepto_tipo_sge_elemento ON  sge_elemento_concepto_tipo (elemento);

-- ALTER TABLE sge_elemento_concepto_tipo DROP CONSTRAINT fk_sge_elemento_concepto_tipo_sge_elemento; 
ALTER TABLE sge_elemento_concepto_tipo 
	ADD CONSTRAINT fk_sge_elemento_concepto_tipo_sge_elemento FOREIGN KEY (elemento) 
	REFERENCES sge_elemento (elemento) deferrable;


-- ##ARCHIVO##fk_sge_formulario_habilitado_detalle_sge_elemento##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado_detalle
-- FK: fk_sge_formulario_habilitado_detalle_sge_elemento
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_formulario_habilitado_detalle_sge_elemento;
CREATE INDEX ifk_sge_formulario_habilitado_detalle_sge_elemento ON  sge_formulario_habilitado_detalle (elemento);

-- ALTER TABLE sge_formulario_habilitado_detalle DROP CONSTRAINT fk_sge_formulario_habilitado_detalle_sge_elemento; 
ALTER TABLE sge_formulario_habilitado_detalle 
	ADD CONSTRAINT fk_sge_formulario_habilitado_detalle_sge_elemento FOREIGN KEY (elemento) 
	REFERENCES sge_elemento (elemento) deferrable;


-- ##ARCHIVO##fk_sge_elemento_concepto_tipo_sge_tipo_elemento##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_elemento_concepto_tipo
-- FK: fk_sge_elemento_concepto_tipo_sge_tipo_elemento
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_elemento_concepto_tipo_sge_tipo_elemento;
CREATE INDEX ifk_sge_elemento_concepto_tipo_sge_tipo_elemento ON  sge_elemento_concepto_tipo (tipo_elemento);

-- ALTER TABLE sge_elemento_concepto_tipo DROP CONSTRAINT fk_sge_elemento_concepto_tipo_sge_tipo_elemento; 
ALTER TABLE sge_elemento_concepto_tipo 
	ADD CONSTRAINT fk_sge_elemento_concepto_tipo_sge_tipo_elemento FOREIGN KEY (tipo_elemento) 
	REFERENCES sge_tipo_elemento (tipo_elemento) deferrable;


-- ##ARCHIVO##fk_sge_formulario_definicion_sge_tipo_elemento##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_definicion
-- FK: fk_sge_formulario_definicion_sge_tipo_elemento
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_formulario_definicion_sge_tipo_elemento;
CREATE INDEX ifk_sge_formulario_definicion_sge_tipo_elemento ON  sge_formulario_definicion (tipo_elemento);

-- ALTER TABLE sge_formulario_definicion DROP CONSTRAINT fk_sge_formulario_definicion_sge_tipo_elemento; 
ALTER TABLE sge_formulario_definicion 
	ADD CONSTRAINT fk_sge_formulario_definicion_sge_tipo_elemento FOREIGN KEY (tipo_elemento) 
	REFERENCES sge_tipo_elemento (tipo_elemento) deferrable;


-- ##ARCHIVO##fk_sge_formulario_habilitado_detalle_sge_tipo_elemento##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado_detalle
-- FK: fk_sge_formulario_habilitado_detalle_sge_tipo_elemento
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_formulario_habilitado_detalle_sge_tipo_elemento;
CREATE INDEX ifk_sge_formulario_habilitado_detalle_sge_tipo_elemento ON  sge_formulario_habilitado_detalle (tipo_elemento);

-- ALTER TABLE sge_formulario_habilitado_detalle DROP CONSTRAINT fk_sge_formulario_habilitado_detalle_sge_tipo_elemento; 
ALTER TABLE sge_formulario_habilitado_detalle 
	ADD CONSTRAINT fk_sge_formulario_habilitado_detalle_sge_tipo_elemento FOREIGN KEY (tipo_elemento) 
	REFERENCES sge_tipo_elemento (tipo_elemento) deferrable;


-- ##ARCHIVO##fk_sge_log_formulario_definicion_habilitacion_sge_tipo_elemento##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_log_formulario_definicion_habilitacion
-- FK: fk_sge_log_formulario_definicion_habilitacion_sge_tipo_elemento
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_log_formulario_definicion_habilitacion_sge_tipo_elemento;
CREATE INDEX ifk_sge_log_formulario_definicion_habilitacion_sge_tipo_elemento ON  sge_log_formulario_definicion_habilitacion (tipo_elemento);

-- ALTER TABLE sge_log_formulario_definicion_habilitacion DROP CONSTRAINT fk_sge_log_formulario_definicion_habilitacion_sge_tipo_elemento; 
ALTER TABLE sge_log_formulario_definicion_habilitacion 
	ADD CONSTRAINT fk_sge_log_formulario_definicion_habilitacion_sge_tipo_elemento FOREIGN KEY (tipo_elemento) 
	REFERENCES sge_tipo_elemento (tipo_elemento) deferrable;


-- ##ARCHIVO##fk_sge_respondido_formulario_sge_formulario_habilitado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_formulario
-- FK: fk_sge_respondido_formulario_sge_formulario_habilitado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respondido_formulario_sge_formulario_habilitado;
CREATE INDEX ifk_sge_respondido_formulario_sge_formulario_habilitado ON  sge_respondido_formulario (formulario_habilitado);

-- ALTER TABLE sge_respondido_formulario DROP CONSTRAINT fk_sge_respondido_formulario_sge_formulario_habilitado; 
ALTER TABLE sge_respondido_formulario 
	ADD CONSTRAINT fk_sge_respondido_formulario_sge_formulario_habilitado FOREIGN KEY (formulario_habilitado) 
	REFERENCES sge_formulario_habilitado (formulario_habilitado) deferrable;


-- ##ARCHIVO##fk_sge_formulario_habilitado_detalle_sge_formulario_habilitado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado_detalle
-- FK: fk_sge_formulario_habilitado_detalle_sge_formulario_habilitado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_formulario_habilitado_detalle_sge_formulario_habilitado;
CREATE INDEX ifk_sge_formulario_habilitado_detalle_sge_formulario_habilitado ON  sge_formulario_habilitado_detalle (formulario_habilitado);

-- ALTER TABLE sge_formulario_habilitado_detalle DROP CONSTRAINT fk_sge_formulario_habilitado_detalle_sge_formulario_habilitado; 
ALTER TABLE sge_formulario_habilitado_detalle 
	ADD CONSTRAINT fk_sge_formulario_habilitado_detalle_sge_formulario_habilitado FOREIGN KEY (formulario_habilitado) 
	REFERENCES sge_formulario_habilitado (formulario_habilitado) deferrable;


-- ##ARCHIVO##fk_sge_grupo_habilitado_sge_formulario_habilitado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_grupo_habilitado
-- FK: fk_sge_grupo_habilitado_sge_formulario_habilitado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_grupo_habilitado_sge_formulario_habilitado;
CREATE INDEX ifk_sge_grupo_habilitado_sge_formulario_habilitado ON  sge_grupo_habilitado (formulario_habilitado);

-- ALTER TABLE sge_grupo_habilitado DROP CONSTRAINT fk_sge_grupo_habilitado_sge_formulario_habilitado; 
ALTER TABLE sge_grupo_habilitado 
	ADD CONSTRAINT fk_sge_grupo_habilitado_sge_formulario_habilitado FOREIGN KEY (formulario_habilitado) 
	REFERENCES sge_formulario_habilitado (formulario_habilitado) deferrable;


-- ##ARCHIVO##fk_sge_respondido_encuestado_sge_formulario_habilitado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuestado
-- FK: fk_sge_respondido_encuestado_sge_formulario_habilitado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respondido_encuestado_sge_formulario_habilitado;
CREATE INDEX ifk_sge_respondido_encuestado_sge_formulario_habilitado ON  sge_respondido_encuestado (formulario_habilitado);

-- ALTER TABLE sge_respondido_encuestado DROP CONSTRAINT fk_sge_respondido_encuestado_sge_formulario_habilitado; 
ALTER TABLE sge_respondido_encuestado 
	ADD CONSTRAINT fk_sge_respondido_encuestado_sge_formulario_habilitado FOREIGN KEY (formulario_habilitado) 
	REFERENCES sge_formulario_habilitado (formulario_habilitado) deferrable;


-- ##ARCHIVO##fk_sge_reporte_exportado_sge_formulario_habilitado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_reporte_exportado
-- FK: fk_sge_reporte_exportado_sge_formulario_habilitado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_reporte_exportado_sge_formulario_habilitado;
CREATE INDEX ifk_sge_reporte_exportado_sge_formulario_habilitado ON  sge_reporte_exportado (formulario_habilitado);

-- ALTER TABLE sge_reporte_exportado DROP CONSTRAINT fk_sge_reporte_exportado_sge_formulario_habilitado; 
ALTER TABLE sge_reporte_exportado 
	ADD CONSTRAINT fk_sge_reporte_exportado_sge_formulario_habilitado FOREIGN KEY (formulario_habilitado) 
	REFERENCES sge_formulario_habilitado (formulario_habilitado) deferrable;


-- ##ARCHIVO##fk_sge_form_hab_indicador_sge_form_hab##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado_indicador
-- FK: fk_sge_form_hab_indicador_sge_form_hab
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_form_hab_indicador_sge_form_hab;
CREATE INDEX ifk_sge_form_hab_indicador_sge_form_hab ON  sge_formulario_habilitado_indicador (formulario_habilitado);

-- ALTER TABLE sge_formulario_habilitado_indicador DROP CONSTRAINT fk_sge_form_hab_indicador_sge_form_hab; 
ALTER TABLE sge_formulario_habilitado_indicador 
	ADD CONSTRAINT fk_sge_form_hab_indicador_sge_form_hab FOREIGN KEY (formulario_habilitado) 
	REFERENCES sge_formulario_habilitado (formulario_habilitado) deferrable;


-- ##ARCHIVO##fk_mgn_mail_formulario_habilitado_sge_formulario_habilitado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_mail_formulario_habilitado
-- FK: fk_mgn_mail_formulario_habilitado_sge_formulario_habilitado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgn_mail_formulario_habilitado_sge_formulario_habilitado;
CREATE INDEX ifk_mgn_mail_formulario_habilitado_sge_formulario_habilitado ON  mgn_mail_formulario_habilitado (formulario_habilitado);

-- ALTER TABLE mgn_mail_formulario_habilitado DROP CONSTRAINT fk_mgn_mail_formulario_habilitado_sge_formulario_habilitado; 
ALTER TABLE mgn_mail_formulario_habilitado 
	ADD CONSTRAINT fk_mgn_mail_formulario_habilitado_sge_formulario_habilitado FOREIGN KEY (formulario_habilitado) 
	REFERENCES sge_formulario_habilitado (formulario_habilitado) deferrable;


-- ##ARCHIVO##fk_sge_puntaje_aplicacion_sge_formulario_habilitado##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_aplicacion
-- FK: fk_sge_puntaje_aplicacion_sge_formulario_habilitado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_puntaje_aplicacion_sge_formulario_habilitado;
CREATE INDEX ifk_sge_puntaje_aplicacion_sge_formulario_habilitado ON  sge_puntaje_aplicacion (formulario_habilitado);

-- ALTER TABLE sge_puntaje_aplicacion DROP CONSTRAINT fk_sge_puntaje_aplicacion_sge_formulario_habilitado; 
ALTER TABLE sge_puntaje_aplicacion 
	ADD CONSTRAINT fk_sge_puntaje_aplicacion_sge_formulario_habilitado FOREIGN KEY (formulario_habilitado) 
	REFERENCES sge_formulario_habilitado (formulario_habilitado) deferrable;


-- ##ARCHIVO##fk_sge_respondido_encuesta_sge_formulario_habilitado_detalle##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuesta
-- FK: fk_sge_respondido_encuesta_sge_formulario_habilitado_detalle
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respondido_encuesta_sge_formulario_habilitado_detalle;
CREATE INDEX ifk_sge_respondido_encuesta_sge_formulario_habilitado_detalle ON  sge_respondido_encuesta (formulario_habilitado_detalle);

-- ALTER TABLE sge_respondido_encuesta DROP CONSTRAINT fk_sge_respondido_encuesta_sge_formulario_habilitado_detalle; 
ALTER TABLE sge_respondido_encuesta 
	ADD CONSTRAINT fk_sge_respondido_encuesta_sge_formulario_habilitado_detalle FOREIGN KEY (formulario_habilitado_detalle) 
	REFERENCES sge_formulario_habilitado_detalle (formulario_habilitado_detalle) deferrable;


-- ##ARCHIVO##fk_sge_form_hab_indicador_sge_form_hab_detalle##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado_indicador
-- FK: fk_sge_form_hab_indicador_sge_form_hab_detalle
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_form_hab_indicador_sge_form_hab_detalle;
CREATE INDEX ifk_sge_form_hab_indicador_sge_form_hab_detalle ON  sge_formulario_habilitado_indicador (formulario_habilitado_detalle);

-- ALTER TABLE sge_formulario_habilitado_indicador DROP CONSTRAINT fk_sge_form_hab_indicador_sge_form_hab_detalle; 
ALTER TABLE sge_formulario_habilitado_indicador 
	ADD CONSTRAINT fk_sge_form_hab_indicador_sge_form_hab_detalle FOREIGN KEY (formulario_habilitado_detalle) 
	REFERENCES sge_formulario_habilitado_detalle (formulario_habilitado_detalle) deferrable;


-- ##ARCHIVO##fk_sge_puntaje_aplicacion_sge_formulario_habilitado_detalle##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_aplicacion
-- FK: fk_sge_puntaje_aplicacion_sge_formulario_habilitado_detalle
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_puntaje_aplicacion_sge_formulario_habilitado_detalle;
CREATE INDEX ifk_sge_puntaje_aplicacion_sge_formulario_habilitado_detalle ON  sge_puntaje_aplicacion (formulario_habilitado_detalle);

-- ALTER TABLE sge_puntaje_aplicacion DROP CONSTRAINT fk_sge_puntaje_aplicacion_sge_formulario_habilitado_detalle; 
ALTER TABLE sge_puntaje_aplicacion 
	ADD CONSTRAINT fk_sge_puntaje_aplicacion_sge_formulario_habilitado_detalle FOREIGN KEY (formulario_habilitado_detalle) 
	REFERENCES sge_formulario_habilitado_detalle (formulario_habilitado_detalle) deferrable;


-- ##ARCHIVO##fk_sge_respondido_encuesta_sge_respondido_formulario##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuesta
-- FK: fk_sge_respondido_encuesta_sge_respondido_formulario
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respondido_encuesta_sge_respondido_formulario;
CREATE INDEX ifk_sge_respondido_encuesta_sge_respondido_formulario ON  sge_respondido_encuesta (respondido_formulario);

-- ALTER TABLE sge_respondido_encuesta DROP CONSTRAINT fk_sge_respondido_encuesta_sge_respondido_formulario; 
ALTER TABLE sge_respondido_encuesta 
	ADD CONSTRAINT fk_sge_respondido_encuesta_sge_respondido_formulario FOREIGN KEY (respondido_formulario) 
	REFERENCES sge_respondido_formulario (respondido_formulario) deferrable;


-- ##ARCHIVO##fk_sge_respondido_encuestado_sge_respondido_formulario##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuestado
-- FK: fk_sge_respondido_encuestado_sge_respondido_formulario
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respondido_encuestado_sge_respondido_formulario;
CREATE INDEX ifk_sge_respondido_encuestado_sge_respondido_formulario ON  sge_respondido_encuestado (respondido_formulario);

-- ALTER TABLE sge_respondido_encuestado DROP CONSTRAINT fk_sge_respondido_encuestado_sge_respondido_formulario; 
ALTER TABLE sge_respondido_encuestado 
	ADD CONSTRAINT fk_sge_respondido_encuestado_sge_respondido_formulario FOREIGN KEY (respondido_formulario) 
	REFERENCES sge_respondido_formulario (respondido_formulario) deferrable;


-- ##ARCHIVO##fk_sge_respondido_por_sge_respondido_formulario##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_por
-- FK: fk_sge_respondido_por_sge_respondido_formulario
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respondido_por_sge_respondido_formulario;
CREATE INDEX ifk_sge_respondido_por_sge_respondido_formulario ON  sge_respondido_por (respondido_formulario);

-- ALTER TABLE sge_respondido_por DROP CONSTRAINT fk_sge_respondido_por_sge_respondido_formulario; 
ALTER TABLE sge_respondido_por 
	ADD CONSTRAINT fk_sge_respondido_por_sge_respondido_formulario FOREIGN KEY (respondido_formulario) 
	REFERENCES sge_respondido_formulario (respondido_formulario) deferrable;


-- ##ARCHIVO##fk_sge_formulario_definicion_sge_formulario_atributo##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_definicion
-- FK: fk_sge_formulario_definicion_sge_formulario_atributo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_formulario_definicion_sge_formulario_atributo;
CREATE INDEX ifk_sge_formulario_definicion_sge_formulario_atributo ON  sge_formulario_definicion (formulario);

-- ALTER TABLE sge_formulario_definicion DROP CONSTRAINT fk_sge_formulario_definicion_sge_formulario_atributo; 
ALTER TABLE sge_formulario_definicion 
	ADD CONSTRAINT fk_sge_formulario_definicion_sge_formulario_atributo FOREIGN KEY (formulario) 
	REFERENCES sge_formulario_atributo (formulario) deferrable;


-- ##ARCHIVO##fk_sge_encuesta_atributo_sge_unidad_gestion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_atributo
-- FK: fk_sge_encuesta_atributo_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_encuesta_atributo_sge_unidad_gestion;
CREATE INDEX ifk_sge_encuesta_atributo_sge_unidad_gestion ON  sge_encuesta_atributo (unidad_gestion);

-- ALTER TABLE sge_encuesta_atributo DROP CONSTRAINT fk_sge_encuesta_atributo_sge_unidad_gestion; 
ALTER TABLE sge_encuesta_atributo 
	ADD CONSTRAINT fk_sge_encuesta_atributo_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


-- ##ARCHIVO##fk_sge_pregunta_sge_unidad_gestion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta
-- FK: fk_sge_pregunta_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_sge_unidad_gestion;
CREATE INDEX ifk_sge_pregunta_sge_unidad_gestion ON  sge_pregunta (unidad_gestion);

-- ALTER TABLE sge_pregunta DROP CONSTRAINT fk_sge_pregunta_sge_unidad_gestion; 
ALTER TABLE sge_pregunta 
	ADD CONSTRAINT fk_sge_pregunta_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


-- ##ARCHIVO##fk_sge_respuesta_sge_unidad_gestion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respuesta
-- FK: fk_sge_respuesta_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respuesta_sge_unidad_gestion;
CREATE INDEX ifk_sge_respuesta_sge_unidad_gestion ON  sge_respuesta (unidad_gestion);

-- ALTER TABLE sge_respuesta DROP CONSTRAINT fk_sge_respuesta_sge_unidad_gestion; 
ALTER TABLE sge_respuesta 
	ADD CONSTRAINT fk_sge_respuesta_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


-- ##ARCHIVO##fk_sge_concepto_sge_unidad_gestion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_concepto
-- FK: fk_sge_concepto_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_concepto_sge_unidad_gestion;
CREATE INDEX ifk_sge_concepto_sge_unidad_gestion ON  sge_concepto (unidad_gestion);

-- ALTER TABLE sge_concepto DROP CONSTRAINT fk_sge_concepto_sge_unidad_gestion; 
ALTER TABLE sge_concepto 
	ADD CONSTRAINT fk_sge_concepto_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


-- ##ARCHIVO##fk_sge_elemento_sge_unidad_gestion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_elemento
-- FK: fk_sge_elemento_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_elemento_sge_unidad_gestion;
CREATE INDEX ifk_sge_elemento_sge_unidad_gestion ON  sge_elemento (unidad_gestion);

-- ALTER TABLE sge_elemento DROP CONSTRAINT fk_sge_elemento_sge_unidad_gestion; 
ALTER TABLE sge_elemento 
	ADD CONSTRAINT fk_sge_elemento_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


-- ##ARCHIVO##fk_sge_tipo_elemento_sge_unidad_gestion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tipo_elemento
-- FK: fk_sge_tipo_elemento_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_tipo_elemento_sge_unidad_gestion;
CREATE INDEX ifk_sge_tipo_elemento_sge_unidad_gestion ON  sge_tipo_elemento (unidad_gestion);

-- ALTER TABLE sge_tipo_elemento DROP CONSTRAINT fk_sge_tipo_elemento_sge_unidad_gestion; 
ALTER TABLE sge_tipo_elemento 
	ADD CONSTRAINT fk_sge_tipo_elemento_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


-- ##ARCHIVO##fk_sge_habilitacion_sge_unidad_gestion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_habilitacion
-- FK: fk_sge_habilitacion_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_habilitacion_sge_unidad_gestion;
CREATE INDEX ifk_sge_habilitacion_sge_unidad_gestion ON  sge_habilitacion (unidad_gestion);

-- ALTER TABLE sge_habilitacion DROP CONSTRAINT fk_sge_habilitacion_sge_unidad_gestion; 
ALTER TABLE sge_habilitacion 
	ADD CONSTRAINT fk_sge_habilitacion_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


-- ##ARCHIVO##fk_mgi_responsable_academica_sge_unidad_gestion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_responsable_academica
-- FK: fk_mgi_responsable_academica_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_responsable_academica_sge_unidad_gestion;
CREATE INDEX ifk_mgi_responsable_academica_sge_unidad_gestion ON  mgi_responsable_academica (unidad_gestion);

-- ALTER TABLE mgi_responsable_academica DROP CONSTRAINT fk_mgi_responsable_academica_sge_unidad_gestion; 
ALTER TABLE mgi_responsable_academica 
	ADD CONSTRAINT fk_mgi_responsable_academica_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


-- ##ARCHIVO##fk_mgi_propuesta_sge_unidad_gestion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_propuesta
-- FK: fk_mgi_propuesta_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_propuesta_sge_unidad_gestion;
CREATE INDEX ifk_mgi_propuesta_sge_unidad_gestion ON  mgi_propuesta (unidad_gestion);

-- ALTER TABLE mgi_propuesta DROP CONSTRAINT fk_mgi_propuesta_sge_unidad_gestion; 
ALTER TABLE mgi_propuesta 
	ADD CONSTRAINT fk_mgi_propuesta_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


-- ##ARCHIVO##fk_mgi_titulo_sge_unidad_gestion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_titulo
-- FK: fk_mgi_titulo_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_titulo_sge_unidad_gestion;
CREATE INDEX ifk_mgi_titulo_sge_unidad_gestion ON  mgi_titulo (unidad_gestion);

-- ALTER TABLE mgi_titulo DROP CONSTRAINT fk_mgi_titulo_sge_unidad_gestion; 
ALTER TABLE mgi_titulo 
	ADD CONSTRAINT fk_mgi_titulo_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


-- ##ARCHIVO##fk_int_guarani_titulos_sge_unidad_gestion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_titulos
-- FK: fk_int_guarani_titulos_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_int_guarani_titulos_sge_unidad_gestion;
CREATE INDEX ifk_int_guarani_titulos_sge_unidad_gestion ON  int_guarani_titulos (unidad_gestion);

-- ALTER TABLE int_guarani_titulos DROP CONSTRAINT fk_int_guarani_titulos_sge_unidad_gestion; 
ALTER TABLE int_guarani_titulos 
	ADD CONSTRAINT fk_int_guarani_titulos_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


-- ##ARCHIVO##fk_int_guarani_carrera_sge_unidad_gestion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_carrera
-- FK: fk_int_guarani_carrera_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_int_guarani_carrera_sge_unidad_gestion;
CREATE INDEX ifk_int_guarani_carrera_sge_unidad_gestion ON  int_guarani_carrera (unidad_gestion);

-- ALTER TABLE int_guarani_carrera DROP CONSTRAINT fk_int_guarani_carrera_sge_unidad_gestion; 
ALTER TABLE int_guarani_carrera 
	ADD CONSTRAINT fk_int_guarani_carrera_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


-- ##ARCHIVO##fk_int_guarani_ra_sge_unidad_gestion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_ra
-- FK: fk_int_guarani_ra_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_int_guarani_ra_sge_unidad_gestion;
CREATE INDEX ifk_int_guarani_ra_sge_unidad_gestion ON  int_guarani_ra (unidad_gestion);

-- ALTER TABLE int_guarani_ra DROP CONSTRAINT fk_int_guarani_ra_sge_unidad_gestion; 
ALTER TABLE int_guarani_ra 
	ADD CONSTRAINT fk_int_guarani_ra_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


-- ##ARCHIVO##fk_int_guarani_persona_sge_unidad_gestion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_persona
-- FK: fk_int_guarani_persona_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_int_guarani_persona_sge_unidad_gestion;
CREATE INDEX ifk_int_guarani_persona_sge_unidad_gestion ON  int_guarani_persona (unidad_gestion);

-- ALTER TABLE int_guarani_persona DROP CONSTRAINT fk_int_guarani_persona_sge_unidad_gestion; 
ALTER TABLE int_guarani_persona 
	ADD CONSTRAINT fk_int_guarani_persona_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


-- ##ARCHIVO##fk_sge_ws_conexion_sge_unidad_gestion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_ws_conexion
-- FK: fk_sge_ws_conexion_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_ws_conexion_sge_unidad_gestion;
CREATE INDEX ifk_sge_ws_conexion_sge_unidad_gestion ON  sge_ws_conexion (unidad_gestion);

-- ALTER TABLE sge_ws_conexion DROP CONSTRAINT fk_sge_ws_conexion_sge_unidad_gestion; 
ALTER TABLE sge_ws_conexion 
	ADD CONSTRAINT fk_sge_ws_conexion_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


-- ##ARCHIVO##fk_sge_tabla_asociada_sge_unidad_gestion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tabla_asociada
-- FK: fk_sge_tabla_asociada_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_tabla_asociada_sge_unidad_gestion;
CREATE INDEX ifk_sge_tabla_asociada_sge_unidad_gestion ON  sge_tabla_asociada (unidad_gestion);

-- ALTER TABLE sge_tabla_asociada DROP CONSTRAINT fk_sge_tabla_asociada_sge_unidad_gestion; 
ALTER TABLE sge_tabla_asociada 
	ADD CONSTRAINT fk_sge_tabla_asociada_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


-- ##ARCHIVO##fk_sge_tabla_externa_sge_unidad_gestion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tabla_externa
-- FK: fk_sge_tabla_externa_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_tabla_externa_sge_unidad_gestion;
CREATE INDEX ifk_sge_tabla_externa_sge_unidad_gestion ON  sge_tabla_externa (unidad_gestion);

-- ALTER TABLE sge_tabla_externa DROP CONSTRAINT fk_sge_tabla_externa_sge_unidad_gestion; 
ALTER TABLE sge_tabla_externa 
	ADD CONSTRAINT fk_sge_tabla_externa_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion);


-- ##ARCHIVO##fk_sge_pregunta_dependencia_definicion_sge_pregunta_dependencia##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_dependencia_definicion
-- FK: fk_sge_pregunta_dependencia_definicion_sge_pregunta_dependencia
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_dependencia_definicion_sge_pregunta_dependencia;
CREATE INDEX ifk_sge_pregunta_dependencia_definicion_sge_pregunta_dependencia ON  sge_pregunta_dependencia_definicion (pregunta_dependencia);

-- ALTER TABLE sge_pregunta_dependencia_definicion DROP CONSTRAINT fk_sge_pregunta_dependencia_definicion_sge_pregunta_dependencia; 
ALTER TABLE sge_pregunta_dependencia_definicion 
	ADD CONSTRAINT fk_sge_pregunta_dependencia_definicion_sge_pregunta_dependencia FOREIGN KEY (pregunta_dependencia) 
	REFERENCES sge_pregunta_dependencia (pregunta_dependencia) deferrable;


-- ##ARCHIVO##fk_sge_puntaje_pregunta_sge_puntaje##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_pregunta
-- FK: fk_sge_puntaje_pregunta_sge_puntaje
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_puntaje_pregunta_sge_puntaje;
CREATE INDEX ifk_sge_puntaje_pregunta_sge_puntaje ON  sge_puntaje_pregunta (puntaje);

-- ALTER TABLE sge_puntaje_pregunta DROP CONSTRAINT fk_sge_puntaje_pregunta_sge_puntaje; 
ALTER TABLE sge_puntaje_pregunta 
	ADD CONSTRAINT fk_sge_puntaje_pregunta_sge_puntaje FOREIGN KEY (puntaje) 
	REFERENCES sge_puntaje (puntaje) deferrable;


-- ##ARCHIVO##fk_sge_puntaje_aplicacion_sge_puntaje##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_aplicacion
-- FK: fk_sge_puntaje_aplicacion_sge_puntaje
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_puntaje_aplicacion_sge_puntaje;
CREATE INDEX ifk_sge_puntaje_aplicacion_sge_puntaje ON  sge_puntaje_aplicacion (puntaje);

-- ALTER TABLE sge_puntaje_aplicacion DROP CONSTRAINT fk_sge_puntaje_aplicacion_sge_puntaje; 
ALTER TABLE sge_puntaje_aplicacion 
	ADD CONSTRAINT fk_sge_puntaje_aplicacion_sge_puntaje FOREIGN KEY (puntaje) 
	REFERENCES sge_puntaje (puntaje) deferrable;


-- ##ARCHIVO##fk_sge_puntaje_respuesta_sge_puntaje_pregunta##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_respuesta
-- FK: fk_sge_puntaje_respuesta_sge_puntaje_pregunta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_puntaje_respuesta_sge_puntaje_pregunta;
CREATE INDEX ifk_sge_puntaje_respuesta_sge_puntaje_pregunta ON  sge_puntaje_respuesta (puntaje_pregunta);

-- ALTER TABLE sge_puntaje_respuesta DROP CONSTRAINT fk_sge_puntaje_respuesta_sge_puntaje_pregunta; 
ALTER TABLE sge_puntaje_respuesta 
	ADD CONSTRAINT fk_sge_puntaje_respuesta_sge_puntaje_pregunta FOREIGN KEY (puntaje_pregunta) 
	REFERENCES sge_puntaje_pregunta (puntaje_pregunta) deferrable;


-- ##ARCHIVO##fk_sge_puntaje_aplicacion_sge_evaluacion##
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_aplicacion
-- FK: fk_sge_puntaje_aplicacion_sge_evaluacion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_puntaje_aplicacion_sge_evaluacion;
CREATE INDEX ifk_sge_puntaje_aplicacion_sge_evaluacion ON  sge_puntaje_aplicacion (evaluacion);

-- ALTER TABLE sge_puntaje_aplicacion DROP CONSTRAINT fk_sge_puntaje_aplicacion_sge_evaluacion; 
ALTER TABLE sge_puntaje_aplicacion 
	ADD CONSTRAINT fk_sge_puntaje_aplicacion_sge_evaluacion FOREIGN KEY (evaluacion) 
	REFERENCES sge_evaluacion (evaluacion) deferrable;


