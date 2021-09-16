-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_grupo_habilitado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_grupo_habilitado;
CREATE  TABLE sge_grupo_habilitado
(
	grupo Integer NOT NULL,
	formulario_habilitado Integer NOT NULL
);

-- ALTER TABLE sge_grupo_habilitado DROP CONSTRAINT pk_sge_grupo_habilitado;
ALTER TABLE sge_grupo_habilitado ADD CONSTRAINT pk_sge_grupo_habilitado PRIMARY KEY (grupo,formulario_habilitado);
-- ++++++++++++++++++++++++++ Fin tabla sge_grupo_habilitado +++++++++++++++++++++++++++++

