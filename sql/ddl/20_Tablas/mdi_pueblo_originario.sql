-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: mdi_pueblo_originario
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mdi_pueblo_originario;
CREATE  TABLE mdi_pueblo_originario
(
	pueblo_originario INTEGER NOT NULL DEFAULT nextval('mdi_pueblo_originario_seq'::text) ,
	nombre Varchar
);

-- ALTER TABLE mdi_pueblo_originario DROP CONSTRAINT pk_mdi_pueblo_originario;
ALTER TABLE mdi_pueblo_originario ADD CONSTRAINT pk_mdi_pueblo_originario PRIMARY KEY (pueblo_originario);
-- ++++++++++++++++++++++++++ Fin tabla mdi_pueblo_originario +++++++++++++++++++++++++++++

