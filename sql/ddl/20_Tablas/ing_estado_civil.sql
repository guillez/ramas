-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_estado_civil
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_estado_civil;
CREATE  TABLE ing_estado_civil
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_estado_civil DROP CONSTRAINT pk_ing_estado_civil;
ALTER TABLE ing_estado_civil ADD CONSTRAINT pk_ing_estado_civil PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_estado_civil +++++++++++++++++++++++++++++

