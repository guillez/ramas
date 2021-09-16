-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_genero
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_genero;
CREATE  TABLE ing_genero
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_genero DROP CONSTRAINT pk_ing_genero;
ALTER TABLE ing_genero ADD CONSTRAINT pk_ing_genero PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_genero +++++++++++++++++++++++++++++

