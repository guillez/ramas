-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_sistema_externo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_sistema_externo;
CREATE  TABLE sge_sistema_externo
(
	sistema INTEGER NOT NULL DEFAULT nextval('sge_sistema_externo_seq'::text) ,
	usuario Varchar(60) NOT NULL,
	nombre Varchar(100) NOT NULL,
	estado Char(1) NOT NULL
);

-- ALTER TABLE sge_sistema_externo DROP CONSTRAINT pk_sge_sistema_externo;
ALTER TABLE sge_sistema_externo ADD CONSTRAINT pk_sge_sistema_externo PRIMARY KEY (sistema);
-- ++++++++++++++++++++++++++ Fin tabla sge_sistema_externo +++++++++++++++++++++++++++++

