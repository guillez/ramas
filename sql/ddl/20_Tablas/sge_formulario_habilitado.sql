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

