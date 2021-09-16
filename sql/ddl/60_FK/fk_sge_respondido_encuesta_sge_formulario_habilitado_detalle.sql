-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuesta
-- FK: fk_sge_respondido_encuesta_sge_formulario_habilitado_detalle
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respondido_encuesta_sge_formulario_habilitado_detalle;
CREATE INDEX ifk_sge_respondido_encuesta_sge_formulario_habilitado_detalle ON  sge_respondido_encuesta (formulario_habilitado_detalle);

-- ALTER TABLE sge_respondido_encuesta DROP CONSTRAINT fk_sge_respondido_encuesta_sge_formulario_habilitado_detalle; 
ALTER TABLE sge_respondido_encuesta 
	ADD CONSTRAINT fk_sge_respondido_encuesta_sge_formulario_habilitado_detalle FOREIGN KEY (formulario_habilitado_detalle) 
	REFERENCES sge_formulario_habilitado_detalle (formulario_habilitado_detalle) deferrable;


