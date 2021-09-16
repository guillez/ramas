-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_habilitacion
-- FK: fk_sge_habilitacion_sge_encuesta_estilo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_habilitacion_sge_encuesta_estilo;
CREATE INDEX ifk_sge_habilitacion_sge_encuesta_estilo ON  sge_habilitacion (estilo);

-- ALTER TABLE sge_habilitacion DROP CONSTRAINT fk_sge_habilitacion_sge_encuesta_estilo; 
ALTER TABLE sge_habilitacion 
	ADD CONSTRAINT fk_sge_habilitacion_sge_encuesta_estilo FOREIGN KEY (estilo) 
	REFERENCES sge_encuesta_estilo (estilo) deferrable;


