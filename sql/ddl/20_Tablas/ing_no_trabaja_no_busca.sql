-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_no_trabaja_no_busca
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_no_trabaja_no_busca;
CREATE  TABLE ing_no_trabaja_no_busca
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_no_trabaja_no_busca DROP CONSTRAINT pk_ing_no_trabaja_no_busca;
ALTER TABLE ing_no_trabaja_no_busca ADD CONSTRAINT pk_ing_no_trabaja_no_busca PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_no_trabaja_no_busca +++++++++++++++++++++++++++++

