-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_reporte_exportado
-- FK: fk_sge_reporte_exportado_sge_reporte_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_reporte_exportado_sge_reporte_tipo;
CREATE INDEX ifk_sge_reporte_exportado_sge_reporte_tipo ON  sge_reporte_exportado (reporte_tipo);

-- ALTER TABLE sge_reporte_exportado DROP CONSTRAINT fk_sge_reporte_exportado_sge_reporte_tipo; 
ALTER TABLE sge_reporte_exportado 
	ADD CONSTRAINT fk_sge_reporte_exportado_sge_reporte_tipo FOREIGN KEY (reporte_tipo) 
	REFERENCES sge_reporte_tipo (reporte_tipo) deferrable;


