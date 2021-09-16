-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: mgi_responsable_academica_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgi_responsable_academica_tipo;
CREATE  TABLE mgi_responsable_academica_tipo
(
	tipo_responsable_academica INTEGER NOT NULL DEFAULT nextval('mgi_responsable_academica_tipo_seq'::text) ,
	nombre Varchar(50) NOT NULL,
	descripcion Varchar(255)
);

-- ALTER TABLE mgi_responsable_academica_tipo DROP CONSTRAINT pk_mgi_responsable_academica_tipo;
ALTER TABLE mgi_responsable_academica_tipo ADD CONSTRAINT pk_mgi_responsable_academica_tipo PRIMARY KEY (tipo_responsable_academica);
-- ++++++++++++++++++++++++++ Fin tabla mgi_responsable_academica_tipo +++++++++++++++++++++++++++++

