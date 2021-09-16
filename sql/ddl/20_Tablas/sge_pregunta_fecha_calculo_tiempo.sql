-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_fecha_calculo_tiempo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_pregunta_fecha_calculo_tiempo;
CREATE  TABLE sge_pregunta_fecha_calculo_tiempo
(
	pregunta_fecha Integer NOT NULL,
	pregunta_dependiente Integer NOT NULL
);

-- ALTER TABLE sge_pregunta_fecha_calculo_tiempo DROP CONSTRAINT pk_sge_pregunta_fecha_calculo_tiempo;
ALTER TABLE sge_pregunta_fecha_calculo_tiempo ADD CONSTRAINT pk_sge_pregunta_fecha_calculo_tiempo PRIMARY KEY (pregunta_fecha);
-- ++++++++++++++++++++++++++ Fin tabla sge_pregunta_fecha_calculo_tiempo +++++++++++++++++++++++++++++

