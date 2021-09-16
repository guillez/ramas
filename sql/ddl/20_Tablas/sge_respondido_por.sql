-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_respondido_por
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_respondido_por;
CREATE  TABLE sge_respondido_por
(
	respondido_formulario Integer NOT NULL,
	encuestado Integer NOT NULL
);

-- ALTER TABLE sge_respondido_por DROP CONSTRAINT pk_sge_respondido_por;
ALTER TABLE sge_respondido_por ADD CONSTRAINT pk_sge_respondido_por PRIMARY KEY (respondido_formulario,encuestado);
-- ++++++++++++++++++++++++++ Fin tabla sge_respondido_por +++++++++++++++++++++++++++++

