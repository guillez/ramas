-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mdi_primario
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mdi_primario;
CREATE  TABLE mdi_primario
(
	nombre Varchar(255) NOT NULL,
	cue Integer NOT NULL,
	localidad Integer,
	codigo_postal Varchar(15),
	colegio_original Integer
);

-- ALTER TABLE mdi_primario DROP CONSTRAINT pk_mdi_primario;
ALTER TABLE mdi_primario ADD CONSTRAINT pk_mdi_primario PRIMARY KEY (cue);
-- ++++++++++++++++++++++++++ Fin tabla mdi_primario +++++++++++++++++++++++++++++

