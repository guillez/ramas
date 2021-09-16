-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado_indicador
-- FK: fk_sge_formulario_habilitado_indicador_sge_encuesta_definicion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_formulario_habilitado_indicador_sge_encuesta_definicion;
CREATE INDEX ifk_sge_formulario_habilitado_indicador_sge_encuesta_definicion ON  sge_formulario_habilitado_indicador (encuesta_definicion);

-- ALTER TABLE sge_formulario_habilitado_indicador DROP CONSTRAINT fk_sge_formulario_habilitado_indicador_sge_encuesta_definicion; 
ALTER TABLE sge_formulario_habilitado_indicador 
	ADD CONSTRAINT fk_sge_formulario_habilitado_indicador_sge_encuesta_definicion FOREIGN KEY (encuesta_definicion) 
	REFERENCES sge_encuesta_definicion (encuesta_definicion) deferrable;


