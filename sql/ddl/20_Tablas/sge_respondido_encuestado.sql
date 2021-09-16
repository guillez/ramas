-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_encuestado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_respondido_encuestado;
CREATE  TABLE sge_respondido_encuestado
(
	respondido_encuestado INTEGER NOT NULL DEFAULT nextval('sge_respondido_encuestado_seq'::text) ,
	formulario_habilitado Integer NOT NULL,
	respondido_formulario Integer,
	encuestado Integer NOT NULL,
	sistema Integer,
	codigo_externo Varchar(100),
	fecha Timestamp with time zone NOT NULL,
	terminado Char(1),
	ignorado Char(1),
	estado_sinc Char(4) DEFAULT 'PEND'
);

-- ALTER TABLE sge_respondido_encuestado DROP CONSTRAINT pk_sge_respondido_encuestado;
ALTER TABLE sge_respondido_encuestado ADD CONSTRAINT pk_sge_respondido_encuestado PRIMARY KEY (respondido_encuestado);
-- ++++++++++++++++++++++++++ Fin tabla sge_respondido_encuestado +++++++++++++++++++++++++++++

