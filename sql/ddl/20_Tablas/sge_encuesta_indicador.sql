-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_indicador
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_encuesta_indicador;
CREATE  TABLE sge_encuesta_indicador
(
	encuesta_definicion Integer NOT NULL,
	encuesta Integer NOT NULL
);

-- ALTER TABLE sge_encuesta_indicador DROP CONSTRAINT pk_sge_encuesta_indicador;
ALTER TABLE sge_encuesta_indicador ADD CONSTRAINT pk_sge_encuesta_indicador PRIMARY KEY (encuesta_definicion,encuesta);
-- ++++++++++++++++++++++++++ Fin tabla sge_encuesta_indicador +++++++++++++++++++++++++++++

