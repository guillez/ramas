-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_atributo
-- FK: fk_sge_encuesta_atributo_sge_unidad_gestion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_encuesta_atributo_sge_unidad_gestion;
CREATE INDEX ifk_sge_encuesta_atributo_sge_unidad_gestion ON  sge_encuesta_atributo (unidad_gestion);

-- ALTER TABLE sge_encuesta_atributo DROP CONSTRAINT fk_sge_encuesta_atributo_sge_unidad_gestion; 
ALTER TABLE sge_encuesta_atributo 
	ADD CONSTRAINT fk_sge_encuesta_atributo_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
	REFERENCES sge_unidad_gestion (unidad_gestion) deferrable;


