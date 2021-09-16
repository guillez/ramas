-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_ws_conexion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_ws_conexion;
CREATE  TABLE sge_ws_conexion
(
	conexion INTEGER NOT NULL DEFAULT nextval('sge_ws_conexion_seq'::text) ,
	unidad_gestion Varchar,
	conexion_nombre Varchar(100),
	ws_url Varchar(100),
	ws_user Varchar(60),
	ws_clave Varchar(200),
	activa Char(1),
	ws_tipo Char(4) NOT NULL DEFAULT 'rest'
);

-- ALTER TABLE sge_ws_conexion DROP CONSTRAINT pk_sge_ws_conexion;
ALTER TABLE sge_ws_conexion ADD CONSTRAINT pk_sge_ws_conexion PRIMARY KEY (conexion);
-- ++++++++++++++++++++++++++ Fin tabla sge_ws_conexion +++++++++++++++++++++++++++++

