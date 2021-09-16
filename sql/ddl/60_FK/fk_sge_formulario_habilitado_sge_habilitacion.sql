-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado
-- FK: fk_sge_formulario_habilitado_sge_habilitacion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_formulario_habilitado_sge_habilitacion;
CREATE INDEX ifk_sge_formulario_habilitado_sge_habilitacion ON  sge_formulario_habilitado (habilitacion);

-- ALTER TABLE sge_formulario_habilitado DROP CONSTRAINT fk_sge_formulario_habilitado_sge_habilitacion; 
ALTER TABLE sge_formulario_habilitado 
	ADD CONSTRAINT fk_sge_formulario_habilitado_sge_habilitacion FOREIGN KEY (habilitacion) 
	REFERENCES sge_habilitacion (habilitacion) deferrable;


