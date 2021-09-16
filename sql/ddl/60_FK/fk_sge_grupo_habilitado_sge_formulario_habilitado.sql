-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_grupo_habilitado
-- FK: fk_sge_grupo_habilitado_sge_formulario_habilitado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_grupo_habilitado_sge_formulario_habilitado;
CREATE INDEX ifk_sge_grupo_habilitado_sge_formulario_habilitado ON  sge_grupo_habilitado (formulario_habilitado);

-- ALTER TABLE sge_grupo_habilitado DROP CONSTRAINT fk_sge_grupo_habilitado_sge_formulario_habilitado; 
ALTER TABLE sge_grupo_habilitado 
	ADD CONSTRAINT fk_sge_grupo_habilitado_sge_formulario_habilitado FOREIGN KEY (formulario_habilitado) 
	REFERENCES sge_formulario_habilitado (formulario_habilitado) deferrable;


