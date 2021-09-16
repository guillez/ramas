-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_propuesta_ra
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgi_propuesta_ra;
CREATE  TABLE mgi_propuesta_ra
(
	responsable_academica Integer NOT NULL,
	propuesta Integer NOT NULL
);

-- ALTER TABLE mgi_propuesta_ra DROP CONSTRAINT pk_mgi_propuesta_ra;
ALTER TABLE mgi_propuesta_ra ADD CONSTRAINT pk_mgi_propuesta_ra PRIMARY KEY (responsable_academica,propuesta);
-- ++++++++++++++++++++++++++ Fin tabla mgi_propuesta_ra +++++++++++++++++++++++++++++

