-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: ing_ocupacion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_ocupacion;
CREATE  TABLE ing_ocupacion
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_ocupacion DROP CONSTRAINT pk_ing_ocupacion;
ALTER TABLE ing_ocupacion ADD CONSTRAINT pk_ing_ocupacion PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_ocupacion +++++++++++++++++++++++++++++

