-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado_indicador
-- FK: fk_sge_form_hab_indicador_sge_form_hab
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_form_hab_indicador_sge_form_hab;
CREATE INDEX ifk_sge_form_hab_indicador_sge_form_hab ON  sge_formulario_habilitado_indicador (formulario_habilitado);

-- ALTER TABLE sge_formulario_habilitado_indicador DROP CONSTRAINT fk_sge_form_hab_indicador_sge_form_hab; 
ALTER TABLE sge_formulario_habilitado_indicador 
	ADD CONSTRAINT fk_sge_form_hab_indicador_sge_form_hab FOREIGN KEY (formulario_habilitado) 
	REFERENCES sge_formulario_habilitado (formulario_habilitado) deferrable;


