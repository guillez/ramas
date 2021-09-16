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

