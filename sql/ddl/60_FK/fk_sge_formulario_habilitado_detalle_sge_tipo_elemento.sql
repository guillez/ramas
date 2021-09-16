-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado_detalle
-- FK: fk_sge_formulario_habilitado_detalle_sge_tipo_elemento
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_formulario_habilitado_detalle_sge_tipo_elemento;
CREATE INDEX ifk_sge_formulario_habilitado_detalle_sge_tipo_elemento ON  sge_formulario_habilitado_detalle (tipo_elemento);

-- ALTER TABLE sge_formulario_habilitado_detalle DROP CONSTRAINT fk_sge_formulario_habilitado_detalle_sge_tipo_elemento; 
ALTER TABLE sge_formulario_habilitado_detalle 
	ADD CONSTRAINT fk_sge_formulario_habilitado_detalle_sge_tipo_elemento FOREIGN KEY (tipo_elemento) 
	REFERENCES sge_tipo_elemento (tipo_elemento) deferrable;


