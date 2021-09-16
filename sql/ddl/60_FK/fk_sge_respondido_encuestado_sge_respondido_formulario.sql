-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_respondido_encuestado
-- FK: fk_sge_respondido_encuestado_sge_respondido_formulario
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respondido_encuestado_sge_respondido_formulario;
CREATE INDEX ifk_sge_respondido_encuestado_sge_respondido_formulario ON  sge_respondido_encuestado (respondido_formulario);

-- ALTER TABLE sge_respondido_encuestado DROP CONSTRAINT fk_sge_respondido_encuestado_sge_respondido_formulario; 
ALTER TABLE sge_respondido_encuestado 
	ADD CONSTRAINT fk_sge_respondido_encuestado_sge_respondido_formulario FOREIGN KEY (respondido_formulario) 
	REFERENCES sge_respondido_formulario (respondido_formulario) deferrable;


