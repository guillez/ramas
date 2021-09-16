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

