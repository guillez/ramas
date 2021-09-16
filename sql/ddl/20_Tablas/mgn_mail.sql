-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_mail
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgn_mail;
CREATE  TABLE mgn_mail
(
	mail INTEGER NOT NULL DEFAULT nextval('mgn_mail_seq'::text) ,
	asunto Varchar(200) NOT NULL,
	contenido Text NOT NULL,
	nombre Varchar(100),
	hora_envio Time,
	fecha_envio Date
);

-- ALTER TABLE mgn_mail DROP CONSTRAINT pk_mgn_mail;
ALTER TABLE mgn_mail ADD CONSTRAINT pk_mgn_mail PRIMARY KEY (mail);
-- ++++++++++++++++++++++++++ Fin tabla mgn_mail +++++++++++++++++++++++++++++

