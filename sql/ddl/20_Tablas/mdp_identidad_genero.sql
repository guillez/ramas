-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mdp_identidad_genero
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mdp_identidad_genero;
CREATE  TABLE mdp_identidad_genero
(
	identidad_genero Integer NOT NULL,
	nombre Varchar(255) NOT NULL
);

-- ALTER TABLE mdp_identidad_genero DROP CONSTRAINT pk_mdp_identidad_genero;
ALTER TABLE mdp_identidad_genero ADD CONSTRAINT pk_mdp_identidad_genero PRIMARY KEY (identidad_genero);
-- ++++++++++++++++++++++++++ Fin tabla mdp_identidad_genero +++++++++++++++++++++++++++++

