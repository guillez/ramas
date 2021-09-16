-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgn_mail_formulario_habilitado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgn_mail_formulario_habilitado;
CREATE  TABLE mgn_mail_formulario_habilitado
(
	mail Integer NOT NULL,
	formulario_habilitado Integer NOT NULL,
	encuestado Integer NOT NULL
);

-- ALTER TABLE mgn_mail_formulario_habilitado DROP CONSTRAINT pk_mgn_mail_formulario_habilitado;
ALTER TABLE mgn_mail_formulario_habilitado ADD CONSTRAINT pk_mgn_mail_formulario_habilitado PRIMARY KEY (mail,formulario_habilitado,encuestado);
-- ++++++++++++++++++++++++++ Fin tabla mgn_mail_formulario_habilitado +++++++++++++++++++++++++++++

