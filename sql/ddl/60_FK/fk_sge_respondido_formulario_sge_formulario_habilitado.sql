-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_formulario
-- FK: fk_sge_respondido_formulario_sge_formulario_habilitado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respondido_formulario_sge_formulario_habilitado;
CREATE INDEX ifk_sge_respondido_formulario_sge_formulario_habilitado ON  sge_respondido_formulario (formulario_habilitado);

-- ALTER TABLE sge_respondido_formulario DROP CONSTRAINT fk_sge_respondido_formulario_sge_formulario_habilitado; 
ALTER TABLE sge_respondido_formulario 
	ADD CONSTRAINT fk_sge_respondido_formulario_sge_formulario_habilitado FOREIGN KEY (formulario_habilitado) 
	REFERENCES sge_formulario_habilitado (formulario_habilitado) deferrable;


