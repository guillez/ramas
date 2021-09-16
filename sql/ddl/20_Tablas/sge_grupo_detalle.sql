-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_grupo_detalle
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_grupo_detalle;
CREATE  TABLE sge_grupo_detalle
(
	grupo Integer NOT NULL,
	encuestado Integer NOT NULL
);

-- ALTER TABLE sge_grupo_detalle DROP CONSTRAINT pk_sge_grupo_detalle;
ALTER TABLE sge_grupo_detalle ADD CONSTRAINT pk_sge_grupo_detalle PRIMARY KEY (grupo,encuestado);
-- ++++++++++++++++++++++++++ Fin tabla sge_grupo_detalle +++++++++++++++++++++++++++++

