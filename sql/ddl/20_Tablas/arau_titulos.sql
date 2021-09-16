-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: arau_titulos
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS arau_titulos;
CREATE  TABLE arau_titulos
(
	titulo_araucano Integer NOT NULL,
	nombre Varchar(255) NOT NULL,
	tipo_titulo Varchar(5)
);

-- ALTER TABLE arau_titulos DROP CONSTRAINT pk_arau_titulos;
ALTER TABLE arau_titulos ADD CONSTRAINT pk_arau_titulos PRIMARY KEY (titulo_araucano);
-- ++++++++++++++++++++++++++ Fin tabla arau_titulos +++++++++++++++++++++++++++++

