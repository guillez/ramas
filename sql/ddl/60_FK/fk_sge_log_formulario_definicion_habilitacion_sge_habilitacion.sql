-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_log_formulario_definicion_habilitacion
-- FK: fk_sge_log_formulario_definicion_habilitacion_sge_habilitacion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_log_formulario_definicion_habilitacion_sge_habilitacion;
CREATE INDEX ifk_sge_log_formulario_definicion_habilitacion_sge_habilitacion ON  sge_log_formulario_definicion_habilitacion (habilitacion);

-- ALTER TABLE sge_log_formulario_definicion_habilitacion DROP CONSTRAINT fk_sge_log_formulario_definicion_habilitacion_sge_habilitacion; 
ALTER TABLE sge_log_formulario_definicion_habilitacion 
	ADD CONSTRAINT fk_sge_log_formulario_definicion_habilitacion_sge_habilitacion FOREIGN KEY (habilitacion) 
	REFERENCES sge_habilitacion (habilitacion) deferrable;


