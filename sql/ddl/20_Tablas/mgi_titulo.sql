-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_titulo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgi_titulo;
CREATE  TABLE mgi_titulo
(
	titulo INTEGER NOT NULL DEFAULT nextval('mgi_titulo_seq'::text) ,
	nombre Varchar(255) NOT NULL,
	nombre_femenino Varchar(255) NOT NULL,
	codigo Varchar(20) NOT NULL,
	titulo_araucano Integer,
	estado Char(1) NOT NULL,
	unidad_gestion Varchar
);

-- ALTER TABLE mgi_titulo DROP CONSTRAINT pk_mgi_titulo;
ALTER TABLE mgi_titulo ADD CONSTRAINT pk_mgi_titulo PRIMARY KEY (titulo);
-- ++++++++++++++++++++++++++ Fin tabla mgi_titulo +++++++++++++++++++++++++++++

