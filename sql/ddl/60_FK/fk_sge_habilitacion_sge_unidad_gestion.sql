-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_habilitacion
-- FK: fk_sge_habilitacion_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_habilitacion_sge_unidad_gestion;
CREATE INDEX ifk_sge_habilitacion_sge_unidad_gestion ON  sge_habilitacion (unidad_gestion);

-- ALTER TABLE sge_habilitacion DROP CONSTRAINT fk_sge_habilitacion_sge_unidad_gestion; 
ALTER TABLE sge_habilitacion 
	ADD CONSTRAINT fk_sge_habilitacion_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


