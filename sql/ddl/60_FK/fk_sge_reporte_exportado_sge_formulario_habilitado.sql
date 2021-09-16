-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_reporte_exportado
-- FK: fk_sge_reporte_exportado_sge_formulario_habilitado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_reporte_exportado_sge_formulario_habilitado;
CREATE INDEX ifk_sge_reporte_exportado_sge_formulario_habilitado ON  sge_reporte_exportado (formulario_habilitado);

-- ALTER TABLE sge_reporte_exportado DROP CONSTRAINT fk_sge_reporte_exportado_sge_formulario_habilitado; 
ALTER TABLE sge_reporte_exportado 
	ADD CONSTRAINT fk_sge_reporte_exportado_sge_formulario_habilitado FOREIGN KEY (formulario_habilitado) 
	REFERENCES sge_formulario_habilitado (formulario_habilitado) deferrable;


