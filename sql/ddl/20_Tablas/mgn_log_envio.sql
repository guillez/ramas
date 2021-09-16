-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_log_envio
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgn_log_envio;
CREATE  TABLE mgn_log_envio
(
	log INTEGER NOT NULL DEFAULT nextval('mgn_log_envio_seq'::text) ,
	mail Integer NOT NULL,
	encuestado Integer NOT NULL,
	mensaje Text,
	hash Varchar(200) NOT NULL
);

-- ALTER TABLE mgn_log_envio DROP CONSTRAINT pk_mgn_log_envio;
ALTER TABLE mgn_log_envio ADD CONSTRAINT pk_mgn_log_envio PRIMARY KEY (log);
-- ++++++++++++++++++++++++++ Fin tabla mgn_log_envio +++++++++++++++++++++++++++++

