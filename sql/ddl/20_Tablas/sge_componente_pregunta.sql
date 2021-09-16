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

