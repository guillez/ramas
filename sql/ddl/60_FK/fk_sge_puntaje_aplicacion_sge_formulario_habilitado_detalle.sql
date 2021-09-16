-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_aplicacion
-- FK: fk_sge_puntaje_aplicacion_sge_formulario_habilitado_detalle
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_puntaje_aplicacion_sge_formulario_habilitado_detalle;
CREATE INDEX ifk_sge_puntaje_aplicacion_sge_formulario_habilitado_detalle ON  sge_puntaje_aplicacion (formulario_habilitado_detalle);

-- ALTER TABLE sge_puntaje_aplicacion DROP CONSTRAINT fk_sge_puntaje_aplicacion_sge_formulario_habilitado_detalle; 
ALTER TABLE sge_puntaje_aplicacion 
	ADD CONSTRAINT fk_sge_puntaje_aplicacion_sge_formulario_habilitado_detalle FOREIGN KEY (formulario_habilitado_detalle) 
	REFERENCES sge_formulario_habilitado_detalle (formulario_habilitado_detalle) deferrable;


