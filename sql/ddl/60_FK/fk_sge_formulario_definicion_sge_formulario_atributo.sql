-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_definicion
-- FK: fk_sge_formulario_definicion_sge_formulario_atributo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_formulario_definicion_sge_formulario_atributo;
CREATE INDEX ifk_sge_formulario_definicion_sge_formulario_atributo ON  sge_formulario_definicion (formulario);

-- ALTER TABLE sge_formulario_definicion DROP CONSTRAINT fk_sge_formulario_definicion_sge_formulario_atributo; 
ALTER TABLE sge_formulario_definicion 
	ADD CONSTRAINT fk_sge_formulario_definicion_sge_formulario_atributo FOREIGN KEY (formulario) 
	REFERENCES sge_formulario_atributo (formulario) deferrable;


