-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_aplicacion
-- FK: fk_sge_puntaje_aplicacion_sge_evaluacion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_puntaje_aplicacion_sge_evaluacion;
CREATE INDEX ifk_sge_puntaje_aplicacion_sge_evaluacion ON  sge_puntaje_aplicacion (evaluacion);

-- ALTER TABLE sge_puntaje_aplicacion DROP CONSTRAINT fk_sge_puntaje_aplicacion_sge_evaluacion; 
ALTER TABLE sge_puntaje_aplicacion 
	ADD CONSTRAINT fk_sge_puntaje_aplicacion_sge_evaluacion FOREIGN KEY (evaluacion) 
	REFERENCES sge_evaluacion (evaluacion) deferrable;


