-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado_detalle
-- FK: fk_sge_formulario_habilitado_detalle_sge_formulario_habilitado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_formulario_habilitado_detalle_sge_formulario_habilitado;
CREATE INDEX ifk_sge_formulario_habilitado_detalle_sge_formulario_habilitado ON  sge_formulario_habilitado_detalle (formulario_habilitado);

-- ALTER TABLE sge_formulario_habilitado_detalle DROP CONSTRAINT fk_sge_formulario_habilitado_detalle_sge_formulario_habilitado; 
ALTER TABLE sge_formulario_habilitado_detalle 
	ADD CONSTRAINT fk_sge_formulario_habilitado_detalle_sge_formulario_habilitado FOREIGN KEY (formulario_habilitado) 
	REFERENCES sge_formulario_habilitado (formulario_habilitado) deferrable;


