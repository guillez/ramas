-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respondido_formulario
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_respondido_formulario;
CREATE  TABLE sge_respondido_formulario
(
	respondido_formulario INTEGER NOT NULL DEFAULT nextval('sge_respondido_formulario_seq'::text) ,
	formulario_habilitado Integer NOT NULL,
	ingreso Integer,
	fecha Date,
	codigo_recuperacion Integer,
	version_digest Varchar(16) NOT NULL,
	terminado Char(1),
	fecha_terminado Date
);

-- ALTER TABLE sge_respondido_formulario DROP CONSTRAINT pk_sge_respondido_formulario;
ALTER TABLE sge_respondido_formulario ADD CONSTRAINT pk_sge_respondido_formulario PRIMARY KEY (respondido_formulario);
-- ++++++++++++++++++++++++++ Fin tabla sge_respondido_formulario +++++++++++++++++++++++++++++

