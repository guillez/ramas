-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mdi_secundario_titulo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mdi_secundario_titulo;
CREATE  TABLE mdi_secundario_titulo
(
	titulo Integer NOT NULL,
	nombre Varchar(255) NOT NULL
);

-- ALTER TABLE mdi_secundario_titulo DROP CONSTRAINT pk_mdi_secundario_titulo;
ALTER TABLE mdi_secundario_titulo ADD CONSTRAINT pk_mdi_secundario_titulo PRIMARY KEY (titulo);
-- ++++++++++++++++++++++++++ Fin tabla mdi_secundario_titulo +++++++++++++++++++++++++++++

