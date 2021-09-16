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

