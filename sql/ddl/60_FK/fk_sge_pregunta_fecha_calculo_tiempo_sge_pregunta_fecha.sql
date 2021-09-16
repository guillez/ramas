-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_fecha_calculo_tiempo
-- FK: fk_sge_pregunta_fecha_calculo_tiempo_sge_pregunta_fecha
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_fecha_calculo_tiempo_sge_pregunta_fecha;
CREATE INDEX ifk_sge_pregunta_fecha_calculo_tiempo_sge_pregunta_fecha ON  sge_pregunta_fecha_calculo_tiempo (pregunta_fecha);

-- ALTER TABLE sge_pregunta_fecha_calculo_tiempo DROP CONSTRAINT fk_sge_pregunta_fecha_calculo_tiempo_sge_pregunta_fecha; 
ALTER TABLE sge_pregunta_fecha_calculo_tiempo 
	ADD CONSTRAINT fk_sge_pregunta_fecha_calculo_tiempo_sge_pregunta_fecha FOREIGN KEY (pregunta_fecha) 
	REFERENCES sge_pregunta (pregunta);


