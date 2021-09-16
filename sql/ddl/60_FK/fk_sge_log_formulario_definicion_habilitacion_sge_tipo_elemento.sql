-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_log_formulario_definicion_habilitacion
-- FK: fk_sge_log_formulario_definicion_habilitacion_sge_tipo_elemento
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_log_formulario_definicion_habilitacion_sge_tipo_elemento;
CREATE INDEX ifk_sge_log_formulario_definicion_habilitacion_sge_tipo_elemento ON  sge_log_formulario_definicion_habilitacion (tipo_elemento);

-- ALTER TABLE sge_log_formulario_definicion_habilitacion DROP CONSTRAINT fk_sge_log_formulario_definicion_habilitacion_sge_tipo_elemento; 
ALTER TABLE sge_log_formulario_definicion_habilitacion 
	ADD CONSTRAINT fk_sge_log_formulario_definicion_habilitacion_sge_tipo_elemento FOREIGN KEY (tipo_elemento) 
	REFERENCES sge_tipo_elemento (tipo_elemento) deferrable;


