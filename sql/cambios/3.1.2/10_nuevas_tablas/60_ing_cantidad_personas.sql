-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-KOLLA 3 - Sistema de Seguimiento de Graduados
-- Version 3.1.X
-- Tabla: ing_cantidad_personas
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE ing_cantidad_personas;

CREATE TABLE ing_cantidad_personas
(
  codigo integer NOT NULL,
  nombre character varying(100),
  CONSTRAINT ing_cantidad_personas_pkey PRIMARY KEY (codigo)
)
WITH (OIDS=TRUE);
ALTER TABLE ing_cantidad_personas OWNER TO postgres;
GRANT ALL ON TABLE ing_cantidad_personas TO postgres;
COMMENT ON TABLE ing_cantidad_personas IS 'Tabla asociada - Datos Censales de Ingenierías';

