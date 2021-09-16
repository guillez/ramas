-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_dptos_partidos
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mug_dptos_partidos;
CREATE  TABLE mug_dptos_partidos
(
	dpto_partido Integer NOT NULL,
	nombre Varchar(60) NOT NULL,
	provincia Integer NOT NULL,
	estado Char(1) NOT NULL
);

-- ALTER TABLE mug_dptos_partidos DROP CONSTRAINT pk_mug_dptos_partidos;
ALTER TABLE mug_dptos_partidos ADD CONSTRAINT pk_mug_dptos_partidos PRIMARY KEY (dpto_partido);
-- ++++++++++++++++++++++++++ Fin tabla mug_dptos_partidos +++++++++++++++++++++++++++++

