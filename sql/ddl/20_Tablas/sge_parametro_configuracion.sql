-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_parametro_configuracion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_parametro_configuracion;
CREATE  TABLE sge_parametro_configuracion
(
	seccion Varchar NOT NULL,
	parametro Varchar NOT NULL,
	valor Varchar
);

-- ALTER TABLE sge_parametro_configuracion DROP CONSTRAINT pk_sge_parametro_configuracion;
ALTER TABLE sge_parametro_configuracion ADD CONSTRAINT pk_sge_parametro_configuracion PRIMARY KEY (seccion,parametro);
-- ++++++++++++++++++++++++++ Fin tabla sge_parametro_configuracion +++++++++++++++++++++++++++++

