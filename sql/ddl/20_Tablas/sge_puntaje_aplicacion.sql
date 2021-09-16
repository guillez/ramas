-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_aplicacion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_puntaje_aplicacion;
CREATE  TABLE sge_puntaje_aplicacion
(
	puntaje_aplicacion INTEGER NOT NULL DEFAULT nextval('sge_puntaje_aplicacion_seq'::text) ,
	puntaje Integer NOT NULL,
	formulario_habilitado Integer,
	formulario_habilitado_detalle Integer,
	evaluacion Integer NOT NULL
);

-- ALTER TABLE sge_puntaje_aplicacion DROP CONSTRAINT pk_sge_puntaje_aplicacion;
ALTER TABLE sge_puntaje_aplicacion ADD CONSTRAINT pk_sge_puntaje_aplicacion PRIMARY KEY (puntaje_aplicacion);
-- ++++++++++++++++++++++++++ Fin tabla sge_puntaje_aplicacion +++++++++++++++++++++++++++++

