-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mdi_secundario
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mdi_secundario;
CREATE  TABLE mdi_secundario
(
	nombre Varchar(255) NOT NULL,
	cue Integer NOT NULL,
	localidad Integer,
	codigo_postal Varchar(15),
	colegio_original Integer
);

-- ALTER TABLE mdi_secundario DROP CONSTRAINT pk_mdi_secundario;
ALTER TABLE mdi_secundario ADD CONSTRAINT pk_mdi_secundario PRIMARY KEY (cue);
-- ++++++++++++++++++++++++++ Fin tabla mdi_secundario +++++++++++++++++++++++++++++

