-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje
-- FK: fk_sge_puntaje_sge_encuesta_atributo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_puntaje_sge_encuesta_atributo;
CREATE INDEX ifk_sge_puntaje_sge_encuesta_atributo ON  sge_puntaje (encuesta);

-- ALTER TABLE sge_puntaje DROP CONSTRAINT fk_sge_puntaje_sge_encuesta_atributo; 
ALTER TABLE sge_puntaje 
	ADD CONSTRAINT fk_sge_puntaje_sge_encuesta_atributo FOREIGN KEY (encuesta) 
	REFERENCES sge_encuesta_atributo (encuesta) deferrable;


