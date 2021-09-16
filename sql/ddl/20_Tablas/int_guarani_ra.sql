-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_ra
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS int_guarani_ra;
CREATE  TABLE int_guarani_ra
(
	fecha_proceso Char(10),
	ra_nombre Varchar(255),
	ra_codigo Varchar(5) NOT NULL,
	ra_tipo Integer,
	ra_institucion Varchar(5),
	ra_localidad Integer,
	ra_calle Varchar(100),
	ra_numero Varchar(10),
	ra_cp Varchar(15),
	ra_telefono Varchar(50),
	ra_fax Varchar(50),
	ra_mail Varchar(100),
	unidad_gestion Varchar
);


-- ++++++++++++++++++++++++++ Fin tabla int_guarani_ra +++++++++++++++++++++++++++++

