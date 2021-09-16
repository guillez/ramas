-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_tipo_documento
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_tipo_documento;
CREATE  TABLE ing_tipo_documento
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_tipo_documento DROP CONSTRAINT pk_ing_tipo_documento;
ALTER TABLE ing_tipo_documento ADD CONSTRAINT pk_ing_tipo_documento PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_tipo_documento +++++++++++++++++++++++++++++

