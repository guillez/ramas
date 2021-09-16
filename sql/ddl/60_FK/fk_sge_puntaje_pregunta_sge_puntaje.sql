-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_puntaje_pregunta
-- FK: fk_sge_puntaje_pregunta_sge_puntaje
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_puntaje_pregunta_sge_puntaje;
CREATE INDEX ifk_sge_puntaje_pregunta_sge_puntaje ON  sge_puntaje_pregunta (puntaje);

-- ALTER TABLE sge_puntaje_pregunta DROP CONSTRAINT fk_sge_puntaje_pregunta_sge_puntaje; 
ALTER TABLE sge_puntaje_pregunta 
	ADD CONSTRAINT fk_sge_puntaje_pregunta_sge_puntaje FOREIGN KEY (puntaje) 
	REFERENCES sge_puntaje (puntaje) deferrable;


