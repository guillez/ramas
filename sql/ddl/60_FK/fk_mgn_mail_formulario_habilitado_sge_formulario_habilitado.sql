-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_mail_formulario_habilitado
-- FK: fk_mgn_mail_formulario_habilitado_sge_formulario_habilitado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgn_mail_formulario_habilitado_sge_formulario_habilitado;
CREATE INDEX ifk_mgn_mail_formulario_habilitado_sge_formulario_habilitado ON  mgn_mail_formulario_habilitado (formulario_habilitado);

-- ALTER TABLE mgn_mail_formulario_habilitado DROP CONSTRAINT fk_mgn_mail_formulario_habilitado_sge_formulario_habilitado; 
ALTER TABLE mgn_mail_formulario_habilitado 
	ADD CONSTRAINT fk_mgn_mail_formulario_habilitado_sge_formulario_habilitado FOREIGN KEY (formulario_habilitado) 
	REFERENCES sge_formulario_habilitado (formulario_habilitado) deferrable;


