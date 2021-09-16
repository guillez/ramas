-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_fecha_calculo_tiempo
-- FK: fk_sge_pregunta_fecha_calculo_tiempo_sge_pregunta_dependiente
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_fecha_calculo_tiempo_sge_pregunta_dependiente;
CREATE INDEX ifk_sge_pregunta_fecha_calculo_tiempo_sge_pregunta_dependiente ON  sge_pregunta_fecha_calculo_tiempo (pregunta_dependiente);

-- ALTER TABLE sge_pregunta_fecha_calculo_tiempo DROP CONSTRAINT fk_sge_pregunta_fecha_calculo_tiempo_sge_pregunta_dependiente; 
ALTER TABLE sge_pregunta_fecha_calculo_tiempo 
	ADD CONSTRAINT fk_sge_pregunta_fecha_calculo_tiempo_sge_pregunta_dependiente FOREIGN KEY (pregunta_dependiente) 
	REFERENCES sge_pregunta (pregunta);


