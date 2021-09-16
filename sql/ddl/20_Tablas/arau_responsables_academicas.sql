-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: arau_responsables_academicas
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS arau_responsables_academicas;
CREATE  TABLE arau_responsables_academicas
(
	ra_araucano Integer NOT NULL,
	nombre Varchar(255) NOT NULL,
	institucion_araucano Integer
);

-- ALTER TABLE arau_responsables_academicas DROP CONSTRAINT pk_arau_responsables_academicas;
ALTER TABLE arau_responsables_academicas ADD CONSTRAINT pk_arau_responsables_academicas PRIMARY KEY (ra_araucano);
-- ++++++++++++++++++++++++++ Fin tabla arau_responsables_academicas +++++++++++++++++++++++++++++

