-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-KOLLA 3 - Sistema de Seguimiento de Graduados
-- Version 3.1.X
-- Tabla: ing_tipo_trabajo
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE ing_tipo_trabajo;

CREATE TABLE ing_tipo_trabajo
(
  codigo integer NOT NULL,
  nombre character varying(100),
  CONSTRAINT ing_tipo_trabajo_pkey PRIMARY KEY (codigo)
)
WITH (OIDS=TRUE);
ALTER TABLE ing_tipo_trabajo OWNER TO postgres;
GRANT ALL ON TABLE ing_tipo_trabajo TO postgres;
COMMENT ON TABLE ing_tipo_trabajo IS 'Tabla asociada - Datos Censales de Ingenierías';

