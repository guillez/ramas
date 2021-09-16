-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-KOLLA 3 - Sistema de Seguimiento de Graduados
-- Version 3.1.X
-- Tabla: ing_genero
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE ing_genero;

CREATE TABLE ing_genero
(
  codigo integer NOT NULL,
  nombre character varying(100),
  CONSTRAINT ing_genero_pkey PRIMARY KEY (codigo)
)
WITH (OIDS=TRUE);
ALTER TABLE ing_genero OWNER TO postgres;
GRANT ALL ON TABLE ing_genero TO postgres;
COMMENT ON TABLE ing_genero IS 'Tabla asociada - Datos Censales de Ingenierías';

