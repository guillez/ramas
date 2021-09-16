-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_titulo_ra
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgi_titulo_ra;
CREATE  TABLE mgi_titulo_ra
(
	responsable_academica Integer NOT NULL,
	titulo Integer NOT NULL
);

-- ALTER TABLE mgi_titulo_ra DROP CONSTRAINT pk_mgi_titulo_ra;
ALTER TABLE mgi_titulo_ra ADD CONSTRAINT pk_mgi_titulo_ra PRIMARY KEY (responsable_academica,titulo);
-- ++++++++++++++++++++++++++ Fin tabla mgi_titulo_ra +++++++++++++++++++++++++++++

