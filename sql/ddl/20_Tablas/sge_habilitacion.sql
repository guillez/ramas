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

