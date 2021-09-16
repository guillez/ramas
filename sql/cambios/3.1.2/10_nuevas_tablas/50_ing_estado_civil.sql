-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-KOLLA 3 - Sistema de Seguimiento de Graduados
-- Version 3.1.X
-- Tabla: ing_estado_civil
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE ing_estado_civil;

CREATE TABLE ing_estado_civil
(
  codigo integer NOT NULL,
  nombre character varying(100),
  CONSTRAINT ing_estado_civil_pkey PRIMARY KEY (codigo)
)
WITH (OIDS=TRUE);
ALTER TABLE ing_estado_civil OWNER TO postgres;
GRANT ALL ON TABLE ing_estado_civil TO postgres;
COMMENT ON TABLE ing_estado_civil IS 'Tabla asociada - Datos Censales de Ingenierías';

