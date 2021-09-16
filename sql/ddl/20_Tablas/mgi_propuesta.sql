-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_propuesta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgi_propuesta;
CREATE  TABLE mgi_propuesta
(
	propuesta INTEGER NOT NULL DEFAULT nextval('mgi_propuesta_seq'::text) ,
	nombre Varchar(255) NOT NULL,
	codigo Varchar(20) NOT NULL,
	estado Char(1) NOT NULL,
	unidad_gestion Varchar
);

-- ALTER TABLE mgi_propuesta DROP CONSTRAINT pk_mgi_propuesta;
ALTER TABLE mgi_propuesta ADD CONSTRAINT pk_mgi_propuesta PRIMARY KEY (propuesta);
-- ++++++++++++++++++++++++++ Fin tabla mgi_propuesta +++++++++++++++++++++++++++++

