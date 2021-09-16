-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_pregunta
-- FK: fk_sge_pregunta_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_sge_unidad_gestion;
CREATE INDEX ifk_sge_pregunta_sge_unidad_gestion ON  sge_pregunta (unidad_gestion);

-- ALTER TABLE sge_pregunta DROP CONSTRAINT fk_sge_pregunta_sge_unidad_gestion; 
ALTER TABLE sge_pregunta 
	ADD CONSTRAINT fk_sge_pregunta_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


