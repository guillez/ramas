-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuestado_titulo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_encuestado_titulo;
CREATE  TABLE sge_encuestado_titulo
(
	encuestado Integer NOT NULL,
	titulo Integer NOT NULL,
	anio Integer NOT NULL,
	fecha Date NOT NULL
);

-- ALTER TABLE sge_encuestado_titulo DROP CONSTRAINT pk_sge_encuestado_titulo;
ALTER TABLE sge_encuestado_titulo ADD CONSTRAINT pk_sge_encuestado_titulo PRIMARY KEY (encuestado,titulo);
-- ++++++++++++++++++++++++++ Fin tabla sge_encuestado_titulo +++++++++++++++++++++++++++++

