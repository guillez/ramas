-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_aplicacion
-- FK: fk_sge_puntaje_aplicacion_sge_formulario_habilitado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_puntaje_aplicacion_sge_formulario_habilitado;
CREATE INDEX ifk_sge_puntaje_aplicacion_sge_formulario_habilitado ON  sge_puntaje_aplicacion (formulario_habilitado);

-- ALTER TABLE sge_puntaje_aplicacion DROP CONSTRAINT fk_sge_puntaje_aplicacion_sge_formulario_habilitado; 
ALTER TABLE sge_puntaje_aplicacion 
	ADD CONSTRAINT fk_sge_puntaje_aplicacion_sge_formulario_habilitado FOREIGN KEY (formulario_habilitado) 
	REFERENCES sge_formulario_habilitado (formulario_habilitado) deferrable;


