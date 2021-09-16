-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado
-- FK: fk_sge_formulario_habilitado_sge_concepto
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_formulario_habilitado_sge_concepto;
CREATE INDEX ifk_sge_formulario_habilitado_sge_concepto ON  sge_formulario_habilitado (concepto);

-- ALTER TABLE sge_formulario_habilitado DROP CONSTRAINT fk_sge_formulario_habilitado_sge_concepto; 
ALTER TABLE sge_formulario_habilitado 
	ADD CONSTRAINT fk_sge_formulario_habilitado_sge_concepto FOREIGN KEY (concepto) 
	REFERENCES sge_concepto (concepto) deferrable;


