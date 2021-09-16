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

