-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_localidades
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mug_localidades;
CREATE  TABLE mug_localidades
(
	localidad Integer NOT NULL,
	nombre Varchar(100),
	nombre_abreviado Varchar(40) NOT NULL,
	dpto_partido Integer NOT NULL,
	ddn Integer
);

-- ALTER TABLE mug_localidades DROP CONSTRAINT pk_mug_localidades;
ALTER TABLE mug_localidades ADD CONSTRAINT pk_mug_localidades PRIMARY KEY (localidad);
-- ++++++++++++++++++++++++++ Fin tabla mug_localidades +++++++++++++++++++++++++++++

