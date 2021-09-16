-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_nivel_instruccion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_nivel_instruccion;
CREATE  TABLE ing_nivel_instruccion
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_nivel_instruccion DROP CONSTRAINT pk_ing_nivel_instruccion;
ALTER TABLE ing_nivel_instruccion ADD CONSTRAINT pk_ing_nivel_instruccion PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_nivel_instruccion +++++++++++++++++++++++++++++

