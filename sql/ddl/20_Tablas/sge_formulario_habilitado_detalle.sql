-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado_detalle
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_formulario_habilitado_detalle;
CREATE  TABLE sge_formulario_habilitado_detalle
(
	formulario_habilitado_detalle INTEGER NOT NULL DEFAULT nextval('sge_formulario_habilitado_detalle_seq'::text) ,
	formulario_habilitado Integer NOT NULL,
	encuesta Integer NOT NULL,
	elemento Integer,
	orden Integer NOT NULL,
	tipo_elemento Integer
);

-- ALTER TABLE sge_formulario_habilitado_detalle DROP CONSTRAINT pk_sge_formulario_habilitado_detalle;
ALTER TABLE sge_formulario_habilitado_detalle ADD CONSTRAINT pk_sge_formulario_habilitado_detalle PRIMARY KEY (formulario_habilitado_detalle);
-- ++++++++++++++++++++++++++ Fin tabla sge_formulario_habilitado_detalle +++++++++++++++++++++++++++++

