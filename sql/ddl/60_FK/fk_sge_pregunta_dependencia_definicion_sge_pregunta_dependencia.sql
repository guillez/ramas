-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_pregunta_dependencia_definicion
-- FK: fk_sge_pregunta_dependencia_definicion_sge_pregunta_dependencia
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_pregunta_dependencia_definicion_sge_pregunta_dependencia;
CREATE INDEX ifk_sge_pregunta_dependencia_definicion_sge_pregunta_dependencia ON  sge_pregunta_dependencia_definicion (pregunta_dependencia);

-- ALTER TABLE sge_pregunta_dependencia_definicion DROP CONSTRAINT fk_sge_pregunta_dependencia_definicion_sge_pregunta_dependencia; 
ALTER TABLE sge_pregunta_dependencia_definicion 
	ADD CONSTRAINT fk_sge_pregunta_dependencia_definicion_sge_pregunta_dependencia FOREIGN KEY (pregunta_dependencia) 
	REFERENCES sge_pregunta_dependencia (pregunta_dependencia) deferrable;


