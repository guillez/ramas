-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_grupo_habilitado
-- FK: fk_sge_grupo_habilitado_sge_grupo_definicion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_grupo_habilitado_sge_grupo_definicion;
CREATE INDEX ifk_sge_grupo_habilitado_sge_grupo_definicion ON  sge_grupo_habilitado (grupo);

-- ALTER TABLE sge_grupo_habilitado DROP CONSTRAINT fk_sge_grupo_habilitado_sge_grupo_definicion; 
ALTER TABLE sge_grupo_habilitado 
	ADD CONSTRAINT fk_sge_grupo_habilitado_sge_grupo_definicion FOREIGN KEY (grupo) 
	REFERENCES sge_grupo_definicion (grupo) deferrable;


