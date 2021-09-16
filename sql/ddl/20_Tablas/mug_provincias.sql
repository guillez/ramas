-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_provincias
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mug_provincias;
CREATE  TABLE mug_provincias
(
	provincia Integer NOT NULL,
	nombre Varchar(60) NOT NULL,
	pais Integer NOT NULL
);

-- ALTER TABLE mug_provincias DROP CONSTRAINT pk_mug_provincias;
ALTER TABLE mug_provincias ADD CONSTRAINT pk_mug_provincias PRIMARY KEY (provincia);
-- ++++++++++++++++++++++++++ Fin tabla mug_provincias +++++++++++++++++++++++++++++

