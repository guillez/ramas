-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_detalle
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_respondido_detalle;
CREATE  TABLE sge_respondido_detalle
(
	respondido_detalle INTEGER NOT NULL DEFAULT nextval('sge_respondido_detalle_seq'::text) ,
	respondido_encuesta Integer NOT NULL,
	encuesta_definicion Integer NOT NULL,
	respuesta_valor Varchar,
	respuesta_codigo Integer,
	moderada Char(1) NOT NULL DEFAULT 'N'
);

-- ALTER TABLE sge_respondido_detalle DROP CONSTRAINT pk_sge_respondido_detalle;
ALTER TABLE sge_respondido_detalle ADD CONSTRAINT pk_sge_respondido_detalle PRIMARY KEY (respondido_detalle);
-- ++++++++++++++++++++++++++ Fin tabla sge_respondido_detalle +++++++++++++++++++++++++++++

