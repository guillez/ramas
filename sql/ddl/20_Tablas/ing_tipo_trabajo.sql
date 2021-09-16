-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_tipo_trabajo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_tipo_trabajo;
CREATE  TABLE ing_tipo_trabajo
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_tipo_trabajo DROP CONSTRAINT pk_ing_tipo_trabajo;
ALTER TABLE ing_tipo_trabajo ADD CONSTRAINT pk_ing_tipo_trabajo PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_tipo_trabajo +++++++++++++++++++++++++++++

