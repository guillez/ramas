-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_detalle
-- FK: fk_sge_respondido_detalle_sge_respondido_encuesta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respondido_detalle_sge_respondido_encuesta;
CREATE INDEX ifk_sge_respondido_detalle_sge_respondido_encuesta ON  sge_respondido_detalle (respondido_encuesta);

-- ALTER TABLE sge_respondido_detalle DROP CONSTRAINT fk_sge_respondido_detalle_sge_respondido_encuesta; 
ALTER TABLE sge_respondido_detalle 
	ADD CONSTRAINT fk_sge_respondido_detalle_sge_respondido_encuesta FOREIGN KEY (respondido_encuesta) 
	REFERENCES sge_respondido_encuesta (respondido_encuesta) deferrable;


