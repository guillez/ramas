-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: arau_instituciones
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS arau_instituciones;
CREATE  TABLE arau_instituciones
(
	institucion_araucano Integer NOT NULL,
	nombre Varchar(255) NOT NULL
);

-- ALTER TABLE arau_instituciones DROP CONSTRAINT pk_arau_instituciones;
ALTER TABLE arau_instituciones ADD CONSTRAINT pk_arau_instituciones PRIMARY KEY (institucion_araucano);
-- ++++++++++++++++++++++++++ Fin tabla arau_instituciones +++++++++++++++++++++++++++++

