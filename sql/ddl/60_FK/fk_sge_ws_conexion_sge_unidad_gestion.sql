-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_ws_conexion
-- FK: fk_sge_ws_conexion_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_ws_conexion_sge_unidad_gestion;
CREATE INDEX ifk_sge_ws_conexion_sge_unidad_gestion ON  sge_ws_conexion (unidad_gestion);

-- ALTER TABLE sge_ws_conexion DROP CONSTRAINT fk_sge_ws_conexion_sge_unidad_gestion; 
ALTER TABLE sge_ws_conexion 
	ADD CONSTRAINT fk_sge_ws_conexion_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


