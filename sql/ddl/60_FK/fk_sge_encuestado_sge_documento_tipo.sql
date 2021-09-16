-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuestado
-- FK: fk_sge_encuestado_sge_documento_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_encuestado_sge_documento_tipo;
CREATE INDEX ifk_sge_encuestado_sge_documento_tipo ON  sge_encuestado (documento_tipo);

-- ALTER TABLE sge_encuestado DROP CONSTRAINT fk_sge_encuestado_sge_documento_tipo; 
ALTER TABLE sge_encuestado 
	ADD CONSTRAINT fk_sge_encuestado_sge_documento_tipo FOREIGN KEY (documento_tipo) 
	REFERENCES sge_documento_tipo (documento_tipo) deferrable;


