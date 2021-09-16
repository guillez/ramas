-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_evaluacion
-- FK: fk_sge_evaluacion_sge_habilitacion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_evaluacion_sge_habilitacion;
CREATE INDEX ifk_sge_evaluacion_sge_habilitacion ON  sge_evaluacion (habilitacion);

-- ALTER TABLE sge_evaluacion DROP CONSTRAINT fk_sge_evaluacion_sge_habilitacion; 
ALTER TABLE sge_evaluacion 
	ADD CONSTRAINT fk_sge_evaluacion_sge_habilitacion FOREIGN KEY (habilitacion) 
	REFERENCES sge_habilitacion (habilitacion) deferrable;


