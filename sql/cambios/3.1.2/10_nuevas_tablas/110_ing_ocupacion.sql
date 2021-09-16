-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-KOLLA 3 - Sistema de Seguimiento de Graduados
-- Version 3.1.X
-- Tabla: ing_ocupacion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE ing_ocupacion;

CREATE TABLE ing_ocupacion
(
  codigo integer NOT NULL,
  nombre character varying(100),
  CONSTRAINT ing_ocupacion_pkey PRIMARY KEY (codigo)
)
WITH (OIDS=TRUE);
ALTER TABLE ing_ocupacion OWNER TO postgres;
GRANT ALL ON TABLE ing_ocupacion TO postgres;
COMMENT ON TABLE ing_ocupacion IS 'Tabla asociada - Datos Censales de Ingenierías';

