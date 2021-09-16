-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: ing_condicion_actividad
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS ing_condicion_actividad;
CREATE  TABLE ing_condicion_actividad
(
	codigo Integer NOT NULL,
	nombre Varchar(100)
);

-- ALTER TABLE ing_condicion_actividad DROP CONSTRAINT pk_ing_condicion_actividad;
ALTER TABLE ing_condicion_actividad ADD CONSTRAINT pk_ing_condicion_actividad PRIMARY KEY (codigo);
-- ++++++++++++++++++++++++++ Fin tabla ing_condicion_actividad +++++++++++++++++++++++++++++

