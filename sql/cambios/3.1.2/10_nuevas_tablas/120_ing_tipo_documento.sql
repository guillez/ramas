-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-KOLLA 3 - Sistema de Seguimiento de Graduados
-- Version 3.1.X
-- Tabla: ing_tipo_documento
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE ing_tipo_documento;

CREATE TABLE ing_tipo_documento
(
  codigo integer NOT NULL,
  nombre character varying(100),
  CONSTRAINT ing_tipo_documento_pkey PRIMARY KEY (codigo)
)
WITH (OIDS=TRUE);
ALTER TABLE ing_tipo_documento OWNER TO postgres;
GRANT ALL ON TABLE ing_tipo_documento TO postgres;
COMMENT ON TABLE ing_tipo_documento IS 'Tabla asociada - Datos Censales de Ingenierías';

