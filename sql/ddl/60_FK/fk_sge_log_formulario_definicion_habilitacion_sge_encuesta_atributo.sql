-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_log_formulario_definicion_habilitacion
-- FK: fk_sge_log_formulario_definicion_habilitacion_sge_encuesta_atributo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_log_formulario_definicion_habilitacion_sge_encuesta_atributo;
CREATE INDEX ifk_sge_log_formulario_definicion_habilitacion_sge_encuesta_atributo ON  sge_log_formulario_definicion_habilitacion (encuesta);

-- ALTER TABLE sge_log_formulario_definicion_habilitacion DROP CONSTRAINT fk_sge_log_formulario_definicion_habilitacion_sge_encuesta_atributo; 
ALTER TABLE sge_log_formulario_definicion_habilitacion 
	ADD CONSTRAINT fk_sge_log_formulario_definicion_habilitacion_sge_encuesta_atributo FOREIGN KEY (encuesta) 
	REFERENCES sge_encuesta_atributo (encuesta) deferrable;


