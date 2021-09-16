-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_institucion_tipo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgi_institucion_tipo;
CREATE  TABLE mgi_institucion_tipo
(
	tipo_institucion INTEGER NOT NULL DEFAULT nextval('mgi_institucion_tipo_seq'::text) ,
	nombre Varchar(50) NOT NULL,
	descripcion Varchar(255)
);

-- ALTER TABLE mgi_institucion_tipo DROP CONSTRAINT pk_mgi_institucion_tipo;
ALTER TABLE mgi_institucion_tipo ADD CONSTRAINT pk_mgi_institucion_tipo PRIMARY KEY (tipo_institucion);
-- ++++++++++++++++++++++++++ Fin tabla mgi_institucion_tipo +++++++++++++++++++++++++++++

