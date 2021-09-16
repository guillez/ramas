-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_definicion
-- FK: fk_sge_encuesta_definicion_sge_bloque
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_encuesta_definicion_sge_bloque;
CREATE INDEX ifk_sge_encuesta_definicion_sge_bloque ON  sge_encuesta_definicion (bloque);

-- ALTER TABLE sge_encuesta_definicion DROP CONSTRAINT fk_sge_encuesta_definicion_sge_bloque; 
ALTER TABLE sge_encuesta_definicion 
	ADD CONSTRAINT fk_sge_encuesta_definicion_sge_bloque FOREIGN KEY (bloque) 
	REFERENCES sge_bloque (bloque) deferrable;


