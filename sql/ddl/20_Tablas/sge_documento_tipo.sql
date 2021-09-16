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

