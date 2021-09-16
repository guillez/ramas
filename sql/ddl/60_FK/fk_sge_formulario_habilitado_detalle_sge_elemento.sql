-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado_detalle
-- FK: fk_sge_formulario_habilitado_detalle_sge_elemento
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_formulario_habilitado_detalle_sge_elemento;
CREATE INDEX ifk_sge_formulario_habilitado_detalle_sge_elemento ON  sge_formulario_habilitado_detalle (elemento);

-- ALTER TABLE sge_formulario_habilitado_detalle DROP CONSTRAINT fk_sge_formulario_habilitado_detalle_sge_elemento; 
ALTER TABLE sge_formulario_habilitado_detalle 
	ADD CONSTRAINT fk_sge_formulario_habilitado_detalle_sge_elemento FOREIGN KEY (elemento) 
	REFERENCES sge_elemento (elemento) deferrable;


