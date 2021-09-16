-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_unidad_gestion;
CREATE  TABLE sge_unidad_gestion
(
	unidad_gestion Varchar NOT NULL,
	nombre Varchar NOT NULL
);

-- ALTER TABLE sge_unidad_gestion DROP CONSTRAINT pk_sge_unidad_gestion;
ALTER TABLE sge_unidad_gestion ADD CONSTRAINT pk_sge_unidad_gestion PRIMARY KEY (unidad_gestion);
-- ++++++++++++++++++++++++++ Fin tabla sge_unidad_gestion +++++++++++++++++++++++++++++

