-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: int_guarani_carrera
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS int_guarani_carrera;
CREATE  TABLE int_guarani_carrera
(
	fecha_proceso Char(10),
	carrera_nombre Varchar(255),
	carrera_codigo Varchar(5),
	carrera_estado Char(1),
	unidad_gestion Varchar
);


-- ++++++++++++++++++++++++++ Fin tabla int_guarani_carrera +++++++++++++++++++++++++++++

