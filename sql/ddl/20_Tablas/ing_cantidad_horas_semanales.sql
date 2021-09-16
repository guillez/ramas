-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_cantidad_horas_semanales
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_cantidad_horas_semanales;
CREATE  TABLE ing_cantidad_horas_semanales
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_cantidad_horas_semanales DROP CONSTRAINT pk_ing_cantidad_horas_semanales;
ALTER TABLE ing_cantidad_horas_semanales ADD CONSTRAINT pk_ing_cantidad_horas_semanales PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_cantidad_horas_semanales +++++++++++++++++++++++++++++

