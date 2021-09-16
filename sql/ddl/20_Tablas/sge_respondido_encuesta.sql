-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuesta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_respondido_encuesta;
CREATE  TABLE sge_respondido_encuesta
(
	respondido_encuesta INTEGER NOT NULL DEFAULT nextval('sge_respondido_encuesta_seq'::text) ,
	respondido_formulario Integer NOT NULL,
	formulario_habilitado_detalle Integer NOT NULL
);

-- ALTER TABLE sge_respondido_encuesta DROP CONSTRAINT pk_sge_respondido_encuesta;
ALTER TABLE sge_respondido_encuesta ADD CONSTRAINT pk_sge_respondido_encuesta PRIMARY KEY (respondido_encuesta);
-- ++++++++++++++++++++++++++ Fin tabla sge_respondido_encuesta +++++++++++++++++++++++++++++

