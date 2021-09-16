-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuestado
-- FK: fk_sge_respondido_encuestado_sge_formulario_habilitado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respondido_encuestado_sge_formulario_habilitado;
CREATE INDEX ifk_sge_respondido_encuestado_sge_formulario_habilitado ON  sge_respondido_encuestado (formulario_habilitado);

-- ALTER TABLE sge_respondido_encuestado DROP CONSTRAINT fk_sge_respondido_encuestado_sge_formulario_habilitado; 
ALTER TABLE sge_respondido_encuestado 
	ADD CONSTRAINT fk_sge_respondido_encuestado_sge_formulario_habilitado FOREIGN KEY (formulario_habilitado) 
	REFERENCES sge_formulario_habilitado (formulario_habilitado) deferrable;


