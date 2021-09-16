-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_pregunta_dependencia_definicion
-- FK: fk_sge_pregunta_dependencia_definicion_sge_bloque
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_dependencia_definicion_sge_bloque;
CREATE INDEX ifk_sge_pregunta_dependencia_definicion_sge_bloque ON  sge_pregunta_dependencia_definicion (bloque);

-- ALTER TABLE sge_pregunta_dependencia_definicion DROP CONSTRAINT fk_sge_pregunta_dependencia_definicion_sge_bloque; 
ALTER TABLE sge_pregunta_dependencia_definicion 
	ADD CONSTRAINT fk_sge_pregunta_dependencia_definicion_sge_bloque FOREIGN KEY (bloque) 
	REFERENCES sge_bloque (bloque) deferrable;


