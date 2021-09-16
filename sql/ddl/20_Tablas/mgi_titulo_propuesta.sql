-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_titulo_propuesta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgi_titulo_propuesta;
CREATE  TABLE mgi_titulo_propuesta
(
	propuesta Integer NOT NULL,
	titulo Integer NOT NULL
);

-- ALTER TABLE mgi_titulo_propuesta DROP CONSTRAINT pk_mgi_titulo_propuesta;
ALTER TABLE mgi_titulo_propuesta ADD CONSTRAINT pk_mgi_titulo_propuesta PRIMARY KEY (propuesta,titulo);
-- ++++++++++++++++++++++++++ Fin tabla mgi_titulo_propuesta +++++++++++++++++++++++++++++

