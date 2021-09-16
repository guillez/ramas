-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-KOLLA 3 - Sistema de Seguimiento de Graduados
-- Version 3.1.X
-- Tabla: ing_cantidad_horas_semanales
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE ing_cantidad_horas_semanales;

CREATE TABLE ing_cantidad_horas_semanales
(
  codigo integer NOT NULL,
  nombre character varying(100),
  CONSTRAINT ing_cantidad_horas_semanales_pkey PRIMARY KEY (codigo)
)
WITH (OIDS=TRUE);
ALTER TABLE ing_cantidad_horas_semanales OWNER TO postgres;
GRANT ALL ON TABLE ing_cantidad_horas_semanales TO postgres;
COMMENT ON TABLE ing_cantidad_horas_semanales IS 'Tabla asociada - Datos Censales de Ingenierías';

