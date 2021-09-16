-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_definicion
-- FK: fk_sge_formulario_definicion_sge_tipo_elemento
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_formulario_definicion_sge_tipo_elemento;
CREATE INDEX ifk_sge_formulario_definicion_sge_tipo_elemento ON  sge_formulario_definicion (tipo_elemento);

-- ALTER TABLE sge_formulario_definicion DROP CONSTRAINT fk_sge_formulario_definicion_sge_tipo_elemento; 
ALTER TABLE sge_formulario_definicion 
	ADD CONSTRAINT fk_sge_formulario_definicion_sge_tipo_elemento FOREIGN KEY (tipo_elemento) 
	REFERENCES sge_tipo_elemento (tipo_elemento) deferrable;


