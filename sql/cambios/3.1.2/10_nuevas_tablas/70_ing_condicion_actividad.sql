-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-KOLLA 3 - Sistema de Seguimiento de Graduados
-- Version 3.1.X
-- Tabla: ing_condicion_actividad
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE ing_condicion_actividad;

CREATE TABLE ing_condicion_actividad
(
  codigo integer NOT NULL,
  nombre character varying(100),
  CONSTRAINT ing_condicion_actividad_pkey PRIMARY KEY (codigo)
)
WITH (OIDS=TRUE);
ALTER TABLE ing_condicion_actividad OWNER TO postgres;
GRANT ALL ON TABLE ing_condicion_actividad TO postgres;
COMMENT ON TABLE ing_condicion_actividad IS 'Tabla asociada - Datos Censales de Ingenierías';

