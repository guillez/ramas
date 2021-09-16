-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_definicion
-- FK: fk_sge_encuesta_definicion_sge_encuesta_atributo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_encuesta_definicion_sge_encuesta_atributo;
CREATE INDEX ifk_sge_encuesta_definicion_sge_encuesta_atributo ON  sge_encuesta_definicion (encuesta);

-- ALTER TABLE sge_encuesta_definicion DROP CONSTRAINT fk_sge_encuesta_definicion_sge_encuesta_atributo; 
ALTER TABLE sge_encuesta_definicion 
	ADD CONSTRAINT fk_sge_encuesta_definicion_sge_encuesta_atributo FOREIGN KEY (encuesta) 
	REFERENCES sge_encuesta_atributo (encuesta) deferrable;


