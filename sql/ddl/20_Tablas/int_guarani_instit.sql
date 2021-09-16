-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: int_guarani_instit
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS int_guarani_instit;
CREATE  TABLE int_guarani_instit
(
	fecha_proceso Char(10),
	institucion_nombre Varchar(255),
	institucion_codigo Varchar(50),
	institucion_araucano Integer
);


-- ++++++++++++++++++++++++++ Fin tabla int_guarani_instit +++++++++++++++++++++++++++++

