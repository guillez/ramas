-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-KOLLA 3 - Sistema de Seguimiento de Graduados
-- Version 3.1.X
-- Tabla: ing_no_trabaja_no_busca
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE ing_no_trabaja_no_busca;

CREATE TABLE ing_no_trabaja_no_busca
(
  codigo integer NOT NULL,
  nombre character varying(100),
  CONSTRAINT ing_no_trabaja_no_busca_pkey PRIMARY KEY (codigo)
)
WITH (OIDS=TRUE);
ALTER TABLE ing_no_trabaja_no_busca OWNER TO postgres;
GRANT ALL ON TABLE ing_no_trabaja_no_busca TO postgres;
COMMENT ON TABLE ing_no_trabaja_no_busca IS 'Tabla asociada - Datos Censales de Ingenierías';

