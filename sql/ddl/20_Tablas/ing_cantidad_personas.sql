-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: ing_cantidad_personas
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_cantidad_personas;
CREATE  TABLE ing_cantidad_personas
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_cantidad_personas DROP CONSTRAINT pk_ing_cantidad_personas;
ALTER TABLE ing_cantidad_personas ADD CONSTRAINT pk_ing_cantidad_personas PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_cantidad_personas +++++++++++++++++++++++++++++

