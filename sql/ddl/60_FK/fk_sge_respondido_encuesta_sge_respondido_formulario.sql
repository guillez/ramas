-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuesta
-- FK: fk_sge_respondido_encuesta_sge_respondido_formulario
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respondido_encuesta_sge_respondido_formulario;
CREATE INDEX ifk_sge_respondido_encuesta_sge_respondido_formulario ON  sge_respondido_encuesta (respondido_formulario);

-- ALTER TABLE sge_respondido_encuesta DROP CONSTRAINT fk_sge_respondido_encuesta_sge_respondido_formulario; 
ALTER TABLE sge_respondido_encuesta 
	ADD CONSTRAINT fk_sge_respondido_encuesta_sge_respondido_formulario FOREIGN KEY (respondido_formulario) 
	REFERENCES sge_respondido_formulario (respondido_formulario) deferrable;


