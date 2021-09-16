-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respuesta
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_respuesta;
CREATE  TABLE sge_respuesta
(
	respuesta INTEGER NOT NULL DEFAULT nextval('sge_respuesta_seq'::text) ,
	valor_tabulado Varchar(255) NOT NULL,
	unidad_gestion Varchar
);

-- ALTER TABLE sge_respuesta DROP CONSTRAINT pk_sge_respuesta;
ALTER TABLE sge_respuesta ADD CONSTRAINT pk_sge_respuesta PRIMARY KEY (respuesta);
-- ++++++++++++++++++++++++++ Fin tabla sge_respuesta +++++++++++++++++++++++++++++

