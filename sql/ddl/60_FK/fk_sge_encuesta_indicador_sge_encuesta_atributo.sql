-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_encuesta_indicador
-- FK: fk_sge_encuesta_indicador_sge_encuesta_atributo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_encuesta_indicador_sge_encuesta_atributo;
CREATE INDEX ifk_sge_encuesta_indicador_sge_encuesta_atributo ON  sge_encuesta_indicador (encuesta);

-- ALTER TABLE sge_encuesta_indicador DROP CONSTRAINT fk_sge_encuesta_indicador_sge_encuesta_atributo; 
ALTER TABLE sge_encuesta_indicador 
	ADD CONSTRAINT fk_sge_encuesta_indicador_sge_encuesta_atributo FOREIGN KEY (encuesta) 
	REFERENCES sge_encuesta_atributo (encuesta) deferrable;


