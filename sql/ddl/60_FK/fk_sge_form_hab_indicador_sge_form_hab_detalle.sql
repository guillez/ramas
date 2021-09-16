-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado_indicador
-- FK: fk_sge_form_hab_indicador_sge_form_hab_detalle
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_form_hab_indicador_sge_form_hab_detalle;
CREATE INDEX ifk_sge_form_hab_indicador_sge_form_hab_detalle ON  sge_formulario_habilitado_indicador (formulario_habilitado_detalle);

-- ALTER TABLE sge_formulario_habilitado_indicador DROP CONSTRAINT fk_sge_form_hab_indicador_sge_form_hab_detalle; 
ALTER TABLE sge_formulario_habilitado_indicador 
	ADD CONSTRAINT fk_sge_form_hab_indicador_sge_form_hab_detalle FOREIGN KEY (formulario_habilitado_detalle) 
	REFERENCES sge_formulario_habilitado_detalle (formulario_habilitado_detalle) deferrable;


