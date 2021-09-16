-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-KOLLA 3 - Sistema de Seguimiento de Graduados
-- Version 3.1.X
-- Tabla: ing_vive
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- DROP TABLE ing_vive;

CREATE TABLE ing_vive
(
  codigo integer NOT NULL,
  nombre character varying(100),
  CONSTRAINT ing_vive_pkey PRIMARY KEY (codigo)
)
WITH (OIDS=TRUE);
ALTER TABLE ing_vive OWNER TO postgres;
GRANT ALL ON TABLE ing_vive TO postgres;
COMMENT ON TABLE ing_vive IS 'Tabla asociada - Datos Censales de Ingenierías';
