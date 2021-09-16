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

