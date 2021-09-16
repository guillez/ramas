-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_relacion_carrera
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_relacion_carrera;
CREATE  TABLE ing_relacion_carrera
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_relacion_carrera DROP CONSTRAINT pk_ing_relacion_carrera;
ALTER TABLE ing_relacion_carrera ADD CONSTRAINT pk_ing_relacion_carrera PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_relacion_carrera +++++++++++++++++++++++++++++

