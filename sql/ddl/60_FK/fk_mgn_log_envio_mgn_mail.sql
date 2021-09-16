-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_log_envio
-- FK: fk_mgn_log_envio_mgn_mail
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgn_log_envio_mgn_mail;
CREATE INDEX ifk_mgn_log_envio_mgn_mail ON  mgn_log_envio (mail);

-- ALTER TABLE mgn_log_envio DROP CONSTRAINT fk_mgn_log_envio_mgn_mail; 
ALTER TABLE mgn_log_envio 
	ADD CONSTRAINT fk_mgn_log_envio_mgn_mail FOREIGN KEY (mail) 
	REFERENCES mgn_mail (mail) deferrable;


