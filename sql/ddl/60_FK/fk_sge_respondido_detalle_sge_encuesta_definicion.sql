-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_detalle
-- FK: fk_sge_respondido_detalle_sge_encuesta_definicion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respondido_detalle_sge_encuesta_definicion;
CREATE INDEX ifk_sge_respondido_detalle_sge_encuesta_definicion ON  sge_respondido_detalle (encuesta_definicion);

-- ALTER TABLE sge_respondido_detalle DROP CONSTRAINT fk_sge_respondido_detalle_sge_encuesta_definicion; 
ALTER TABLE sge_respondido_detalle 
	ADD CONSTRAINT fk_sge_respondido_detalle_sge_encuesta_definicion FOREIGN KEY (encuesta_definicion) 
	REFERENCES sge_encuesta_definicion (encuesta_definicion) deferrable;


