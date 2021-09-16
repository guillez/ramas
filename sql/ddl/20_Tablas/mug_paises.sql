-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_paises
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mug_paises;
CREATE  TABLE mug_paises
(
	pais Integer NOT NULL,
	nombre Varchar(60) NOT NULL,
	continente Char(2) NOT NULL,
	codigo_iso Char(2)
);

-- ALTER TABLE mug_paises DROP CONSTRAINT pk_mug_paises;
ALTER TABLE mug_paises ADD CONSTRAINT pk_mug_paises PRIMARY KEY (pais);
-- ++++++++++++++++++++++++++ Fin tabla mug_paises +++++++++++++++++++++++++++++

