-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_mail_formulario_habilitado
-- FK: fk_mgn_mail_formulario_habilitado_sge_encuestado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgn_mail_formulario_habilitado_sge_encuestado;
CREATE INDEX ifk_mgn_mail_formulario_habilitado_sge_encuestado ON  mgn_mail_formulario_habilitado (encuestado);

-- ALTER TABLE mgn_mail_formulario_habilitado DROP CONSTRAINT fk_mgn_mail_formulario_habilitado_sge_encuestado; 
ALTER TABLE mgn_mail_formulario_habilitado 
	ADD CONSTRAINT fk_mgn_mail_formulario_habilitado_sge_encuestado FOREIGN KEY (encuestado) 
	REFERENCES sge_encuestado (encuestado) deferrable;


