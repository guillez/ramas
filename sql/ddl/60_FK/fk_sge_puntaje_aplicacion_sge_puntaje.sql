-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_aplicacion
-- FK: fk_sge_puntaje_aplicacion_sge_puntaje
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_puntaje_aplicacion_sge_puntaje;
CREATE INDEX ifk_sge_puntaje_aplicacion_sge_puntaje ON  sge_puntaje_aplicacion (puntaje);

-- ALTER TABLE sge_puntaje_aplicacion DROP CONSTRAINT fk_sge_puntaje_aplicacion_sge_puntaje; 
ALTER TABLE sge_puntaje_aplicacion 
	ADD CONSTRAINT fk_sge_puntaje_aplicacion_sge_puntaje FOREIGN KEY (puntaje) 
	REFERENCES sge_puntaje (puntaje) deferrable;


