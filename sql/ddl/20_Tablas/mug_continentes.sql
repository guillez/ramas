-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_continentes
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mug_continentes;
CREATE  TABLE mug_continentes
(
	continente Char(2) NOT NULL,
	nombre Varchar(20) NOT NULL
);

-- ALTER TABLE mug_continentes DROP CONSTRAINT pk_mug_continentes;
ALTER TABLE mug_continentes ADD CONSTRAINT pk_mug_continentes PRIMARY KEY (continente);
-- ++++++++++++++++++++++++++ Fin tabla mug_continentes +++++++++++++++++++++++++++++

