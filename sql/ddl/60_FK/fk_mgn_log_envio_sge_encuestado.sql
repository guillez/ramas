-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_log_envio
-- FK: fk_mgn_log_envio_sge_encuestado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgn_log_envio_sge_encuestado;
CREATE INDEX ifk_mgn_log_envio_sge_encuestado ON  mgn_log_envio (encuestado);

-- ALTER TABLE mgn_log_envio DROP CONSTRAINT fk_mgn_log_envio_sge_encuestado; 
ALTER TABLE mgn_log_envio 
	ADD CONSTRAINT fk_mgn_log_envio_sge_encuestado FOREIGN KEY (encuestado) 
	REFERENCES sge_encuestado (encuestado) deferrable;


