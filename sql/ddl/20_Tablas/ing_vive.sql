-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_vive
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_vive;
CREATE  TABLE ing_vive
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_vive DROP CONSTRAINT pk_ing_vive;
ALTER TABLE ing_vive ADD CONSTRAINT pk_ing_vive PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_vive +++++++++++++++++++++++++++++

