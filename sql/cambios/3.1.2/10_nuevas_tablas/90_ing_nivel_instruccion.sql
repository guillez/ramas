-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-KOLLA 3 - Sistema de Seguimiento de Graduados
-- Version 3.1.X
-- Tabla: ing_nivel_instruccion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE ing_nivel_instruccion;

CREATE TABLE ing_nivel_instruccion
(
  codigo integer NOT NULL,
  nombre character varying(100),
  CONSTRAINT ing_nivel_instruccion_pkey PRIMARY KEY (codigo)
)
WITH (OIDS=TRUE);
ALTER TABLE ing_nivel_instruccion OWNER TO postgres;
GRANT ALL ON TABLE ing_nivel_instruccion TO postgres;
COMMENT ON TABLE ing_nivel_instruccion IS 'Tabla asociada - Datos Censales de Ingenierías';

