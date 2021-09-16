-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_mail_formulario_habilitado
-- FK: fk_mgn_mail_formulario_habilitado_mgn_mail
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgn_mail_formulario_habilitado_mgn_mail;
CREATE INDEX ifk_mgn_mail_formulario_habilitado_mgn_mail ON  mgn_mail_formulario_habilitado (mail);

-- ALTER TABLE mgn_mail_formulario_habilitado DROP CONSTRAINT fk_mgn_mail_formulario_habilitado_mgn_mail; 
ALTER TABLE mgn_mail_formulario_habilitado 
	ADD CONSTRAINT fk_mgn_mail_formulario_habilitado_mgn_mail FOREIGN KEY (mail) 
	REFERENCES mgn_mail (mail) deferrable;


