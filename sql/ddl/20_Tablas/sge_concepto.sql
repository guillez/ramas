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

