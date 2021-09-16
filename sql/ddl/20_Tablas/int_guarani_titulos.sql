-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_titulos
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS int_guarani_titulos;
CREATE  TABLE int_guarani_titulos
(
	fecha_proceso Char(10),
	titulo_nombre Varchar(255),
	titulo_nombre_femenino Varchar(255) NOT NULL,
	titulo_codigo Varchar(5),
	titulo_araucano Integer,
	titulo_estado Char(1),
	unidad_gestion Varchar
);


-- ++++++++++++++++++++++++++ Fin tabla int_guarani_titulos +++++++++++++++++++++++++++++

