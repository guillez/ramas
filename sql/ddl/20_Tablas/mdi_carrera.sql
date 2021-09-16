-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mdi_carrera
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mdi_carrera;
CREATE  TABLE mdi_carrera
(
	codigo INTEGER NOT NULL DEFAULT nextval('mdi_carrera_seq'::text) ,
	descripcion Varchar
);

-- ALTER TABLE mdi_carrera DROP CONSTRAINT pk_mdi_carrera;
ALTER TABLE mdi_carrera ADD CONSTRAINT pk_mdi_carrera PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla mdi_carrera +++++++++++++++++++++++++++++

